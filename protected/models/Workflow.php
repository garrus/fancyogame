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
     * @var int
     */
    private $_maxWorkingUnit=3;


    /**
     *
     * @param ZPlanet $planet
     */
    public function __construct(TaskQueue $taskQueue, $maxWorkingUnit=3){

        $this->_taskQueue = $taskQueue;
        $this->_maxWorkingUnit = $maxWorkingUnit;
        $this->onCompleteTask = array($taskQueue, 'taskComplete');
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

        return count($this->_runningTasks) < $this->_maxWorkingUnit;
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
     * Run!
     *
     */
    public function run(){

        if (empty($this->_runningTasks) && !$this->hasWork()) {
            // no running task, and no pending task
            return;
        }

        // push task
        $now = new DateTime;
        $nextTaskTime = $this->_taskQueue->getLastRunTime();

        while (true) {

            // in every working loop, at most one task can be executed.
            // this task is the one that has the earliest end time among
            // the tasks in working units.

            // at the beginning, we'll send in tasks until there is no more
            // free working unit.
            while ($this->hasFreeWorkingUnit() && $this->hasWork()) {
                // task will be activated and plant into working unit.
                // if it cannot be activated, it will be dropped.
                $this->sendInTask($nextTaskTime);
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
                $this->onCompleteTask($nextFinishedTask);
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

    	if ($this->beforeActivateTask($task)) {
    	
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
            $this->onActivateTask($task, $activateTime);
            if (!$task->save()) {
                throw new ModelError($task);
            }
            return true;
    	}
    	
	    if ($task->hasErrors()) {
            Yii::log('Task aborted. '. Utils::modelError($task));
            $task->delete();
        }
        return false;
    }
    
    protected function beforeActivateTask(Task $task){
    	
    	$this->onBeforeActivateTask($task);
    	return !$task->hasErrors();
    }
    
    public function onBeforeActivateTask(Task $task, DateTime $dateTime){

    	$task->setScenario('checkrequirement');
    	$this->raiseEvent('onBeforeActivateTask', new WorkflowTaskEvent($this, $task, $dateTime));
    }

    public function onActivateTask(Task $task, DateTime $dateTime){

        $task->setScenario('activate');
    	$this->raiseEvent('onActivateTask', new WorkflowTaskEvent($this, $task, $dateTime));
    }

    public function onCompleteTask(Task $task, DateTime $dateTime){

        $task->setScenario('complete');
    	$this->raiseEvent('onCompleteTask', new WorkflowTaskEvent($this, $task, $dateTime));
    }

}

/**
 * 
 * @property Workflow $sender
 * @property Task $task
 * @property DateTime $datetime
 * 
 * @author yaowenh
 */
class WorkflowTaskEvent extends CEvent{
    
    /**
     * Constructor
     * 
     * @param Workflow $sender
     * @param Task $task
     * @param DateTime $dateTime
     */
    public function __construct(Workflow $sender, Task $task, DateTime $dateTime){
        
        parent::__construct($sender, array(
            'task' => $task,
            'datetime' => $dateTime,
        ));
    }


    /**
     *
     * @return Task
     */
    public function getTask(){

        return $this->params['task'];
    }


    /**
     *
     * @return DateTime
     */
    public function getDateTime(){

        return $this->params['datetime'];
    }
    
}

