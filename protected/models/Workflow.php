<?php
class Workflow extends CComponent {

    /**
     *
     * @var TaskQueue
     */
    private $_taskQueue;

    /**
     *
     * @var array()
     */
    private $_runningTasks=array();

    /**
     *
     * @var ZPlanet
     */
    private $_planet;


    /**
     *
     * @param ZPlanet $planet
     */
    public function __construct(ZPlanet $planet){

        $this->_planet = $planet;
        $this->_taskQueue = $planet->getTaskQueue();

        $this->onTaskActivated = array($planet, 'taskStageChange');
        $this->onTaskFinished = array($planet, 'taskStageChange');
    }

    /**
     * Return if the workflow is running
     *
     * @return boolean
     */
    public function isRunning(){

        return !empty($this->_runningTasks);
    }

    /**
     * Return if there is free working unit
     *
     * @return boolean
     */
    private function hasFreeWorkingUnit(){

        return count($this->_runningTasks) < $this->_techs->getMaxParallelTask();
    }

    /**
     * Return if there is more work waiting to be done
     *
     * @return boolean
     */
    private function hasWork(){

        return !$this->_taskQueue->isEmpty();
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

        if ($this->isRunning()) {
            $this->run();
        }

        if ($this->_taskQueue->isFull()) {
            throw new CException('The task queue\'s length has reached its limit.');
        }
        $task = Task::createNew($this->_planet, $type, $target, $amount=1);
        $this->_taskQueue->enqueue($task);

        $this->hasFreeWorkingUnit() && $this->run();
    }

    /**
     * Run!
     *
     */
    public function run(){

        if (empty($this->_runningTasks) && !$this->hasWork()) {
            return;
        }

        // push task
        $now = new DateTime;
        $nextTaskTime = $this->_planet->getLastUpdateTime();

        while(true) {

            // in every working loop, at most one task can be executed.
            // this task is the one that has the earliest end time among
            // the tasks in working units.

            // at the beginning, we'll send in tasks until there is no more
            // free working unit.
            while($this->hasFreeWorkingUnit()){
                if ($this->hasWork()) {
                    // task will be activated and plant into working unit.
                    // if it cannot be activated, it will be dropped.
                    $this->sendInTask($nextTaskTime);
                }
            }

            // now let's find the earliest finished task
            $nextTaskTime = $now;
            $nextFinishedTask = null;
            $taskIndex = null;
            foreach ($this->_runningTasks as $index => $task) {
                $taskEndTime = $task->getEndTime();
                if ($nextTaskTime >= $taskEndTime) {
                    $nextTaskTime = $taskEndTime;
                    $nextFinishedTask = $task;
                    $taskIndex = $index;
                }
            }

            if ($nextTaskTime <= $now && $nextFinishedTask) {
                $this->onTaskFinished($nextFinishedTask);
                unset($this->_runningTasks[$index]);
            } else {
                // no more task can be executed, so no more working unit can be released
                // quit running loop
                break;
            }
        }

    }

    /**
     *
     * @return Task
     */
    private function sendInTask(DateTime $activateTime){

        $task = $this->_taskQueue->dequeue();
        if ($task) {
            if ($task->isActivated() || $this->activateTask($task, $activateTime)) {
                return $this->_runningTasks[] = $task;
            }
        }
    }


    private function activateTask(Task $task, DateTime $activateTime){

        if ($this->_planet->validateTaskRequirement($task)) {
            foreach ($this->_runningTasks as $running_task) {
                if ($task->hasConflictWith($running_task)) {
                    // this task has conflict with one running task.
                    // it has to wait at the end of queue.
                    $this->_taskQueue->push($task);
                    return false;
                }
            }

            $task->is_running = 1;
            $task->activate_time = $activateTime->format('Y-m-d H:i:s');
            $this->onTaskActivated(new CEvent($this, $task));
            if (!$task->save()) {
                throw new ModelError($task);
            }
            return true;
        } else {
            Yii::log('Task aborted. '. Utils::modelError($task));
            $task->delete();
            return false;
        }
    }

    public function onTaskActivated(Task $task){

        $task->setScenario('activated');
        $this->raiseEvent(new CEvent($this, $task));
    }

    public function onTaskFinished(Task $task){

        $task->setScenario('finished');
        $this->raiseEvent(new CEvent($this, $task));
    }


}
