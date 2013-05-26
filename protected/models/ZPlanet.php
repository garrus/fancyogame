<?php
/**
 *
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
     * @return PlanetBuilder
     */
    public static function createPlanetBuilder(){

        return new PlanetBuilder();
    }


    /**
     *
     * @return Resources
     */
    public function getResources(){

        return $this->planetData->getCollection('resources');
    }


    /**
     *
     * @return Ships
     */
    public function getShips(){

        return $this->planetData->getCollection('ships');
    }

    /**
     *
     * @return Defences
     */
    public function getDefences(){

        return $this->planetData->getCollection('defences');
    }

    /**
     *
     * @return Buildings
     */
    public function getBuildings(){

        return $this->planetData->getCollection('buildings');
    }

    /**
     *
     * @return Mines
     */
    public function getMines(){

        return $this->planetData->getMines();
    }

    /**
     *
     * @return Techs
     */
    public function getTechs(){

        return $this->owner->playerData->getCollection('techs');
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

	        $taskQueue->setLimit($this->getTechs()->getPendingTaskLimit());
	        $taskQueue->onTaskDeleted = array($this, 'dropTask');
	        return $this->_taskQueue = $taskQueue;
    	}
    	return $this->_taskQueue;
    }

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
     * Constructor
     *
     * @param int $type
     * @param string $target
     * @param int $amount
     * @throws InvalidArgumentException
     */
    public function addNewTask($type, $target, $amount=1){

    	$workflow = $this->getWorkflow();
    	if ($workflow->isRunning()) {
    		$workflow->run();
    	}

    	$taskQueue = $this->getTaskQueue();

    	if ($taskQueue->isFull()) {
    	    Yii::app()->user->setFlash('error_task_failed_to_added', 'You have reached your task queue\'s length limit.');
    	    return;
    	} else {

    	    Yii::getLogger('Task queue count: '. $taskQueue->count(), ', limit is '. $this->getTechs()->getPendingTaskLimit(), 'warning');

    	}

    	if (TechTree::checkRequirement($this, $type, $target, $amount)) {
    	    Yii::app()->user->setFlash('error_task_failed_to_added', 'Tech requirement is not matched.');
    	    return;
    	}

    	$task = Task::createNew($this, $type, $target, $amount);
    	$task->refresh();
    	$taskQueue->enqueue($task);

    	Yii::app()->user->setFlash('success_task_added', 'Task "'. $task->getDescription(). '" is added successfully!');
    	$workflow->run();
    }


    public function cancelTask($id){

        $workflow = $this->getWorkflow();
        if ($workflow->isRunning()) {
            $workflow->run();
        }

        $task = Task::model()->findByPk($id);
        if ($task) {
            if ($task->isActivated()) {
                $consume_res = ResourceExecutor::getTaskConsume($task, $this);
                $timeline_percent = Utils::timelinePercentage($task->activate_time, $task->end_time);

                if ($timeline_percent != 0) {
                    $factor = $timeline_percent / 100;

                    switch ($task->getType()) {
                        case Task::TYPE_CONSTRUCT:
                            $this->resources->add($consume_res->times($factor));
                            break;
                        case Task::TYPE_BUILD_SHIPS:
                            $collection = $this->ships;
                        case Task::TYPE_BUILD_DEFENCES:
                            if (isset($collection)) {
                                $collection = $this->defences;
                            }
                            $this->resources->add($consume_res->times($factor));
                            $finished_count = floor($factor * $task->getAmount());
                            $collection->modify($factor, $finished_count);
                            break;
                        case Task::TYPE_RESEARCH:
                        default:
                            break;
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
	    	return $this->_workflow = new Workflow(
	    	    $this->getTaskQueue(),
	    	    $this->createTaskExecutorChain(),
	    	    $this->techs->getMaxWorkingUnit()
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
     * @param Task $task
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
     * @param WorkflowTaskEvent $event
     */
    public function taskStageChange($event){

        $this->updateResources($event->datetime);

        $task = $event->task;
        $trans = self::getDbConnection()->beginTransaction();
        try {
            $this->createTaskExecutorChain($task)->run();
        } catch (Exception $e) {
            $trans->rollback();
            throw $e;
        }
        if ($task->hasErrors()) {
            $trans->rollback();
        } else {
            $trans->commit();
        }
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
     */
    protected function updateResources($tillTime) {

        $last_update_time = Utils::ensureDateTime($this->planetData->last_update_time);
        if ($tillTime > $last_update_time) {

            $lastUpdateTime = clone $last_update_time;
            $hours = Utils::getHours($lastUpdateTime->diff($tillTime));
           // if ($hours < 5 / 3600) return;

            $resources = $this->resources;
            $buildings = $this->buildings;
            $energy_costs = $buildings->getEnergyCostPerHour(true);
            $energy_produce = $buildings->energyPerHour;// * $this->techs->energy_tech * 1.1;

            $prods = $buildings->productionPerHour;

            if ($prods['metal'] + $prods['crystal'] > $this->mine_limit * 1000) {
                $defactor = $this->mine_limit * 1000 / ($prods['metal'] + $prods['crystal']);
                $prods['metal'] *= $defactor;
                $prods['crystal'] *= $defactor;
                $energy_costs['metal_refinery'] *= $defactor;
                $energy_costs['crystal_factory'] *= $defactor;
            }

            if ($prods['gas'] > $this->gas_production_rate * 1000) {
                $defactor = $this->gas_production_rate * 1000 / $prods['gas'];
                $prods['gas'] *= $defactor;
                $energy_costs['gas'] *= $defactor;
            }

            $energy_diff = $hours * ($energy_produce - array_sum($energy_costs));

            if ($energy_diff + $resources->energy > $buildings->getEnergyCapacity()){
                $energy_diff = $buildings->getEnergyCapacity() - $resources->energy;
            } elseif ($energy_diff < 0 && $energy_diff + $resources->energy < 0) {
                $hours *= ($resources->energy + $energy_produce) / array_sum($energy_costs);
                $energy_diff = -$resources->energy;
            }
            // TODO take energy override tech into consideration

            $res_diff = array_map(function($rate) use($hours){
                return $rate * $hours;
            }, $prods);
            $res_diff['energy'] = $energy_diff;

            foreach ($res_diff as $item => $value) {
                $res_diff[$item] = floor($value);
                $res_diff[$item.'_decimal'] = intval(($value - floor($value)) * 100000);
            }

            $this->planetData->last_update_time = $tillTime->format('Y-m-d H:i:s');
            if (false == $resources->modify($res_diff)) {
                throw new ModelError($resources);
            }

        }
    }

}
