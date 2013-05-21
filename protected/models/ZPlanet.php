<?php
/**
 *
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

        return $this->planetData->getResources();
    }


    /**
     *
     * @return Ships
     */
    public function getShips(){

        return $this->planetData->getShips();
    }

    /**
     *
     * @return Defences
     */
    public function getDefences(){

        return $this->planetData->getDefences();
    }

    /**
     *
     * @return Buildings
     */
    public function getBuildings(){

        return $this->planetData->getBuildings();
    }

    /**
     *
     * @return Techs
     */
    public function getTechs(){

        return $this->owner->getTechs();
    }
    
    private $_taskQueue;
    private $_workflow;

    /**
     *
     * @return TaskQueue
     */
    public function getTaskQueue(){

    	if (!$this->_taskQueue) {
	        $taskQueue = new TaskQueue($this->tasks, $this->planetData->last_update_time);

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
    	$task = Task::createNew($this->_planet, $type, $target, $amount=1);
    	$taskQueue->enqueue($task);
    
    	$workflow->run();
    }


    /**
     * @return Workflow
     */
    public function getWorkflow(){

    	if (!$this->_workflow) {
	    	$workflow = new Workflow($this->getTaskQueue());
	    	$workflow->onBeforeActivateTask = array($this, 'taskStageChange');
	    	$workflow->onTaskActivated = array($this, 'taskStageChange');
	    	$workflow->onTaskFinished = array($this, 'taskStageChange');
	    	
	    	return $this->_workflow = $workflow;
    	}
    	return $this->_workflow;
    }
    
    


    /**
     *
     * @param Task $task
     * @return TaskExecutorChain
     */
    private function createTaskExecutorChain($task){

        $chain = new TaskExecutorChain($this, $task);
        $chain->add(new ResourceExecutor());

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
    }


    /**
     *
     * @param CEvent $event
     */
    public function taskStageChange($event){

        $trans = self::getDbConnection()->beginTransaction();
        try {
            $this->createTaskExecutorChain($event->params)->run();
        } catch (Exception $e) {
            $trans->rollback();
            throw $e;
        }
        if ($event->params->hasErrors()) {
            $trans->rollback();
            return false;
        } else {
            $trans->commit();
            return true;
        }
    }

}
