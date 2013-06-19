<?php
/**
 *
 * @property Resources $resources
 * @property Techs $techs
 * @property Buildings $buildings
 * @property Ships $ships
 * @property Defences $defences
 * @property TaskQueue $taskQueue
 *
 * @author user
 *
 */
class ZPlanet extends \Planet {


    /**
     *
     * @var TaskQueue
     */
    private $_taskQueue;

    /**
     *
     * @var Workflow
     */
    private $_workflow;


    /**
     * Return a static model
     *
     * @param string $className
     * @return ZPlanet
     */
    public static function model($className=__CLASS__) {

        return parent::model($className);
    }


    /**
     *
     * @return Resources
     */
    public function getResources(){

        return $this->planetData->getCollection('res');
    }


    /**
     *
     * @return Ships
     */
    public function getShips(){

        return $this->planetData->getCollection('ship');
    }

    /**
     *
     * @return Defences
     */
    public function getDefences(){

        return $this->planetData->getCollection('def');
    }

    /**
     *
     * @return Buildings
     */
    public function getBuildings(){

        return $this->planetData->getCollection('bd');
    }

    /**
     *
     * @return Mines
     */
    public function getMines(){

        return $this->planetData->getCollection('mine');
    }

    /**
     *
     * @return Techs
     */
    public function getTechs(){

        return $this->owner->playerData->getCollection('tech');
    }

    /**
     * @return Resources
     */
    public function getDebris(){

        return $this->planetData->getCollection('debris');
    }


    /**
     *
     * @return TaskQueue
     */
    public function getTaskQueue(){

    	if (!$this->_taskQueue) {
    	    $tasks = $this->tasks;
    	    usort($tasks, function($t1, $t2){
    	       $ret = $t2->is_running - $t1->is_running;
    	       if ($ret) {
    	           return $ret;
    	       }

    	       return strcmp($t1->create_time, $t2->create_time);
    	    });


	        $taskQueue = new TaskQueue($this->tasks, Utils::ensureDateTime($this->planetData->last_update_time));

	        $taskQueue->setMaxPendingTaskCount(Calculator::max_pending_task_count($this->buildings->computer_center));
	        $taskQueue->onTaskDeleted = array($this, 'dropTask');
	        return $this->_taskQueue = $taskQueue;
    	}
        return $this->_taskQueue;
    }

    /**
     * Drop a task
     *
     * @param Task $task
     * @param bool $delete
     */
    public function dropTask(Task $task, $delete=false){

        if ($this->hasRelated('tasks')) {
            $_tasks = $this->tasks;
            foreach ($_tasks as $index => $_task) {
                if ($task->id == $_task->id) {
                    unset($_tasks[$index]);
                }
            }
            $this->tasks = $_tasks;
        }
        if ($delete) {
            $task->delete();
        }
    }


    /**
     * Add a new task
     *
     * @param int $type
     * @param string $target
     * @param int $amount
     * @throws InvalidArgumentException
     */
    public function addNewTask($type, $target, $amount=1){

    	$workflow = $this->getWorkflow();
    	if (!$workflow->isRunning()) {
    		$workflow->run();
    	} else {
            $this->updateResources(new DateTime);
        }

        $taskQueue = $this->getTaskQueue();
    	if ($taskQueue->isFull()) {
    	    Yii::app()->user->setFlash('error_task_failed_to_added', 'You have reached your task queue\'s length limit.');
    	    return;
    	}

    	if (!TechTree::checkRequirement($this, $type, $target)) {
    	    Yii::app()->user->setFlash('error_task_failed_to_added', 'Tech requirement is not matched.');
    	    return;
    	}

    	$task = Task::createNew($this, $type, $target, $amount);
    	$task->refresh();
    	$taskQueue->enqueue($task);

    	Yii::app()->user->setFlash('success_task_added', 'Task "'. $task->getDescription(). '" is added successfully!');
    	$workflow->run();
    }


    /**
     * Cancel the specified task
     *
     * @param int $id
     */
    public function cancelTask($id){

        $workflow = $this->getWorkflow();
        if (!$workflow->isRunning()) {
            $workflow->run();
        } else {
            $this->updateResources(new DateTime);
        }

        /** @var Task $task */
        $task = Task::model()->findByPk($id);
        if ($task) {
            if ($task->isActivated()) {
                $consume_res = ResourceExecutor::getTaskConsume($task, $this);
                $timeline_percent = Utils::timelinePercentage($task->activate_time, $task->end_time);

                if ($timeline_percent != 0) {
                    // not fully consumed. we need to return some resources
                    $complete_factor = $timeline_percent / 100;

                    $type = $task->type;
                    if ($type != Task::TYPE_RESEARCH) {
                        // the resources that should be returned
                        $this->resources->add($consume_res->times(1 - $complete_factor));
                    }
                    if ($type == Task::TYPE_BUILD_SHIPS || $type == Task::TYPE_BUILD_DEFENCES) {
                        $collection = $type == Task::TYPE_BUILD_SHIPS ? $this->ships : $this->defences;
                        $collection->modify($task->target, floor($complete_factor * $task->getAmount()));
                    }
                }
            }
            $this->_workflow = null;
            $this->_taskQueue = null;
            $this->dropTask($task, true);
        }
    }


    /**
     * @return Workflow
     */
    public function getWorkflow(){

    	if (!$this->_workflow) {
            $buildings = $this->buildings;
	    	return $this->_workflow = new Workflow(
	    	    $this->getTaskQueue(),
	    	    $this->createTaskExecutorChain(),
	    	    Calculator::max_workflow_thread_count($buildings->computer_center, $buildings->robot_factory)
	    	    );
    	}
    	return $this->_workflow;
    }

    /**
     *
     * @var TaskExecutorChain
     */
    private $_chain;

    /**
     *
     * @internal param \Task $task
     * @return TaskExecutorChain
     */
    private function createTaskExecutorChain(){

        Yii::import('application.utils.taskExecutors.*');

        $chain = $this->_chain = new TaskExecutorChain($this);
        $chain->add(new ResourceExecutor());
        $chain->add(new TechExecutor());
        $chain->add(new BuildingExecutor());
        $chain->add(new ShipExecutor());
        $chain->add(new DefenceExecutor());

        return $chain;
    }

    /**
     *
     */
    public function __invoke(){

        //TODO take flees event into consideration
        // solution: pass in a date time
        $this->getWorkflow()->run();
        $this->updateResources(new DateTime());
    }

    /**
     * Update the resources of this planet to given time
     *
     * @param DateTime $tillTime
     * @throws ModelError
     */
    protected function updateResources($tillTime) {

        $last_update_time = Utils::ensureDateTime($this->planetData->last_update_time);
        if ($tillTime > $last_update_time) {

            $this->planetData->last_update_time = $tillTime->format('Y-m-d H:i:s');

            $data = $this->getResourceInfo();

            // planetData will be saved on success
            $this->resources->update($data, Utils::getHours($last_update_time->diff($tillTime)));
        }
    }

    /**
     * Get all information of planet about resource
     *
     * @return array
     */
    public function getResourceInfo(){

        $b = $this->buildings;
        $t = $this->techs;
        $s = $this->ships;

        $info = array();
        $info['mine_limit'] = $this->getMineLimit();
        $info['gas_limit'] = $this->getGasLimit();
        $info['energy_prod_rate'] = Calculator::energy_prod_rate(
            $b->solar_plant, $b->nuclear_plant, $s->solar_satellite, array(
                'energy_tech' => $t->energy, 'planet_temperature' => $this->temperature));
        $info['energy_capacity'] = Calculator::energy_capacity($b->solar_plant, $t->energy);
        $info['energy_consume_rate'] = Calculator::energy_consume_rate_detail(
            $b->metal_refinery, $b->crystal_factory, $b->gas_plant);
        $info['res_prod_rate'] = Calculator::resource_prod_rate(
            $b->metal_refinery, $b->crystal_factory, $b->gas_plant, $b->nuclear_plant);
        $info['res_capacity'] = Calculator::resource_capacity($b->warehouse);

        return $info;
    }

}
