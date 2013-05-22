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
	    	$workflow = new Workflow($this->taskQueue, $this->techs->getMaxWorkingUnit());
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
     * (non-PHPdoc)
     * @see CActiveRecord::update()
     */
    public function update(){
        
        //TODO take flees event into consideration
        // solution: pass in a date time
        
        $this->getWorkflow()->run();
    }
    
    /**
     * Update the resources of this planet to given time
     * 
     * @param DateTime $tillTime
     */
    protected function updateRecources($tillTime) {
        
        $last_update_time = Utils::ensureDateTime($this->planetData->last_update_time);
        if ($tillTime > $last_update_time) {
        
            $lastUpdateTime = clone $last_update_time;
            
            
            $resources = $this->resources;
            $buildings = $this->buildings;
            $energy_costs = $buildings->getEnergyCostPerHour(true);
            $energy_produce = $buildings->energyPerHour * $this->techs->energy_tech * 1.1;
            
            $prods = $buildings->productionPerHour;
            
            if ($prods['metal'] + $prods['crystal'] > $this->mine_limit * 1000) {
                $defactor = $this->mine_limit * 1000 / $prods['metal'] + $prods['crystal'];
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
            
            $hours = Utils::getHours($lastUpdateTime->diff($tillTime));
            $energy_diff = $hours * ($energy_produce - array_sum($energy_costs));
            if ($energy_diff < 0 && $energy_diff + $resources->energy < 0) {
                $hours *= $resources->energy / (-$energy_diff);
                $energy_diff = -$resources->energy;
            } elseif ($energy_diff > 0 && $energy_diff + $resources->energy < $buildings->getEnergyCapacity()){
                $energy_diff = $buildings->getEnergyCapacity() - $resources->energy;
            }
            // TODO take energy override tech into consideration
            
            $res_diff = array_map(function($rate) use($hours){
                return $rate * $hours;
            }, $prods);
            $res_diff['energy'] = $energy_diff;
            
            $this->planetData->last_update_time = $tillTime->format('Y-m-d H:i:s');
            if (false == $resources->modify($res_diff)) {
                throw new ModelError($resources);
            }
        }
    }

}
