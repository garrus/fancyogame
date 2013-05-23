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

        return $this->owner->getTechs();
    }


    /**
     *
     * @return TaskQueue
     */
    public function getTaskQueue(){

    	if (!$this->_taskQueue) {
	        $taskQueue = new TaskQueue($this->tasks, Utils::ensureDateTime($this->planetData->last_update_time));

	        $taskQueue->setLimit($this->getTechs()->getPendingTaskLimit());
	        return $this->_taskQueue = $taskQueue;
    	}
    	return $this->_taskQueue;
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
    		throw new CException('The task queue\'s length has reached its limit.');
    	}

    	$task = Task::createNew($this, $type, $target, $amount);
    	foreach($this->tasks as $_task){
    	    if ($task->hasConflictWith($_task)) {
    	        $task->delete();
    	        Yii::app()->user->setFlash('error_task_failed_to_added', 'Task "'. $task->getDescription(). '" is not added because there is a same task in the running.');
    	        return;
    	    }
    	}

    	$task->refresh();
    	$taskQueue->enqueue($task);

    	Yii::app()->user->setFlash('success_task_added', 'Task "'. $task->getDescription(). '" is added successfully!');
    	$workflow->run();
    }


    /**
     * @return Workflow
     */
    public function getWorkflow(){

    	if (!$this->_workflow) {
	    	$workflow = new Workflow($this->taskQueue, $this->techs->getMaxWorkingUnit());
	    	$workflow->onBeforeActivateTask = array($this, 'taskStageChange');
	    	$workflow->onActivateTask = array($this, 'taskStageChange');
	    	$workflow->onCompleteTask = array($this, 'taskStageChange');

	    	return $this->_workflow = $workflow;
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
    private function createTaskExecutorChain($task){

        if (!$this->_chain) {

            Yii::import('application.utils.taskExecutors.*');

            $chain = $this->_chain = new TaskExecutorChain($this, $task);
            $chain->add(new ResourceExecutor());
            $chain->add(new TechExecutor());
            $chain->add(new BuildingExecutor());
            $chain->add(new ShipExecutor());
            $chain->add(new DefenceExecutor());
        } else {
            $this->_chain->setTask($task);
        }
        return $this->_chain;

        /*
        switch ($task->type) {
            case Task::TYPE_RESEARCH:
                $chain->add(new TechExecutor());
                break;
            case Task::TYPE_CONSTRUCT:
            case Task::TYPE_DECONSTRUCT:
                $chain->add(new BuildingExecutor());
                break;
            case Task::TYPE_BUILD_SHIPS:
                $chain->add(new ShipExecutor());
                break;
            case Task::TYPE_BUILD_DEFENCES:
                $chain->add(new DefenceExecutor());
            default:
                break;
        }

        return $chain;
        */
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
            return false;
        } else {
            $trans->commit();
            return true;
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
        //$this->planetData->last_update_time = new CDbExpression('CURRENT_TIMESTAMP');
        //$this->planetData->save();
        //$this->planetData->refresh();
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
