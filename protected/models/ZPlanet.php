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

    /**
     *
     * @return TaskQueue
     */
    public function getTaskQueue(){

        $taskQueue = new TaskQueue;
        foreach ($this->tasks as $task) {
            $taskQueue->enqueue($task);
        }
        $taskQueue->setLimit($this->getTechs()->getPendingTaskLimit());
        return $taskQueue;
    }


    /**
     *
     * @return Workflow
     */
    public function getWorkflow(){

        return new Workflow($this);
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


    /**
     *
     * @param Task $task
     */
    public function validateTaskRequirement($task){

        $trans = self::getDbConnection()->beginTransaction();
        try {
            $this->createTaskExecutorChain($task)->simulate(true)->run();
            $trans->rollback();
        } catch (Exception $e) {
            $trans->rollback();
            throw $e;
        }

        return $task->hasErrors('requirement');
    }

}
