<?php
class Workflow extends CComponent {

    /**
     *
     * @var TaskQueue
     */
    private $_taskQueue;

    /**
     *
     * @var TaskExecutorChain
     */
    private $_executorChain;

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

    private $_holdTaskQueue;


    /**
     *
     * @param TaskQueue $taskQueue
     * @param TaskExecutorChain $chain
     * @param int $maxWorkingUnit
     * @internal param \ZPlanet $planet
     */
    public function __construct(TaskQueue $taskQueue, TaskExecutorChain $chain, $maxWorkingUnit=3){

        $this->_taskQueue = $taskQueue;
        $this->_executorChain = $chain;
        $this->_maxWorkingUnit = $maxWorkingUnit;
        $this->_holdTaskQueue = new SplQueue;
        $this->attachEventHandler('onCompleteTask', array($taskQueue, 'taskComplete'));
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
     * @param null|DateTime $tillDateTime if set, will only run to this time point
     */
    public function run($tillDateTime=null){

        if (!$tillDateTime) {
            $tillDateTime = new DateTime;
        }
        if (!$this->isRunning() && !$this->hasWork()) {
            Yii::getLogger()->log('No running task, and no pending task. Quit running.');
            // no running task, and no pending task
            $this->_taskQueue->setLastRunTime($tillDateTime);
            return;
        }

        // if there is next task to be activated, this is the activate time
        $activateTime = $this->_taskQueue->getLastRunTime()->format('Y-m-d H:i:s');
        $tillTime = $tillDateTime->format('Y-m-d H:i:s');
        while (true) {
            // in every working loop, at most one task can be executed.
            // this task is the one that has the earliest end time among
            // the tasks in working units.

            // at the beginning, we'll send in tasks until there is no more
            // free working unit.
            while ($this->hasFreeWorkingUnit() && $this->hasWork()) {
                // task will be activated and plant into working unit.
                // if it cannot be activated, it will be dropped or hold.
                $this->sendInTask($activateTime);
            }

            // now let's find the earliest to-be-complete task
            $task = $this->getEarliestCompleteTask($tillTime);
            if ($task) {
                $this->completeTask($task);
                $activateTime = $task->end_time;
            } else {
                //Yii::getLogger()->log('No more tasks can be executed. Quit running.');
                // no more task can be executed, so no more working unit can be released
                // quit running loop
                break;
            }
        }

        $this->_taskQueue->setLastRunTime($tillDateTime);
    }

    /**
     * Find the earliest to-be-complete task from the running ones
     *
     * @param null|string $tillTime Y-m-d H:i:s  if this parameter is set, the
     * calculation time range is limited down to $tillTime
     * @return null|Task
     */
    private function getEarliestCompleteTask($tillTime=null){

        $completeTime = $tillTime;
        $completeTask = null;
        foreach ($this->_runningTasks as $task) {
            /** @var Task $task */
            if (!$completeTime || $completeTime >= $task->end_time) {
                $completeTime = $task->end_time;
                $completeTask = $task;
            }
        }

        return $completeTask;
    }

    /**
     *
     * @param string $activateTime Y-m-d H:i:s
     */
    private function sendInTask($activateTime){

        $task = $this->_taskQueue->dequeue();
        if ($task) {
            if ($task->isActivated()) {
                $this->_runningTasks[] = $task;
            } elseif ($this->activateTask($task, $activateTime)) {
                $this->_runningTasks[] = $task;
            } else {
                if ($task->hasErrors()) {
                    Yii::getLogger()->log('Task "'. $task->getDescription(). '" dropped because of error: '. Utils::modelError($task), 'debug');
                    Yii::app()->user->setFlash('error_task_dropped', 'Task "'. $task->getDescription(). '" is dropped because of error: '. Utils::modelError($task));
                    $task->delete();
                } else {
                    Yii::getLogger()->log('Task "'. $task->getDescription(). '" is on hold because another same task is running.');
                    Yii::app()->user->setFlash('notice_task_dropped', 'Task "'. $task->getDescription(). '" is dropped because there is a same task in the running.');
                    $this->_taskQueue->holdTask($task);
                }
            }
        }
    }


    /**
     * @param Task $task
     * @param string $activateTime Y-m-d H:i:s
     * @return bool
     * @throws ModelError
     */
    private function activateTask($task, $activateTime){

    	if ($this->beforeActivateTask($task)) {
    	    $task->setScenario('activate');
    	    $task->is_running = 1;
    	    $task->activate_time = $activateTime;
    	    $this->_executorChain->runTask($task);
            if (!$task->save()) {
                throw new ModelError($task);
            }

            $this->onAfterActivateTask($task);
            Yii::getLogger()->log('Task "'. $task->getDescription(). '" activated on '. $task->activate_time. ', end on '. $task->end_time);

            return true;
    	} else {
    	    return false;
    	}
    }

    /**
     * @param Task $task
     * @return bool
     */
    protected function beforeActivateTask($task){

        foreach ($this->_runningTasks as $running_task) {
            if ($task->hasConflictWith($running_task)) {
                // this task has conflict with one running task.
                // it has to wait at the end of queue.
                return false;
            }
        }
    	return $this->_executorChain->checkResource($task);
    }

    /**
     * @param Task $task
     */
    public function onAfterActivateTask($task){

        $event = new WorkflowTaskEvent($this, $task);
        $this->raiseEvent('onAfterActivateTask', $event);
    }

    /**
     * @param Task $task
     */
    public function completeTask($task){

        $task->scenario = 'complete';
        $this->_executorChain->runTask($task);
        foreach ($this->_runningTasks as $index => $_task) {
            if ($task->id == $_task->id) {
                unset($this->_runningTasks[$index]);
            }
        }
        $task->delete();

        $this->onCompleteTask($task);
    }

    /**
     * @param Task $task
     */
    public function onCompleteTask($task){

        // whenever we complete a task, we append all hold task to task queue
        $this->_taskQueue->releaseHoldTasks();
        Yii::getLogger()->log('Task "'. $task->getDescription(). '" complete at '. $task->end_time);

        $event = new WorkflowTaskEvent($this, $task);
        $this->raiseEvent('onCompleteTask', $event);
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
     * @internal param \DateTime $dateTime
     */
    public function __construct(Workflow $sender, Task $task){

        switch ($task->scenario) {
            case 'activate':
                $datetime = Utils::ensureDateTime($task->activate_time);
                break;
            case 'complete':
                $datetime = Utils::ensureDateTime($task->end_time);
                break;
            default:
                $datetime = new DateTime;
                break;
        }

        parent::__construct($sender, array(
            'task' => $task,
            'datetime' => $datetime,
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

