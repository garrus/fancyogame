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
     *
     */
    public function run(){

        if (!$this->isRunning() && !$this->hasWork()) {
            Yii::getLogger()->log('No running task, and no pending task. Quit running.');
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
                }
            }

            if ($nextTaskTime <= $now && $nextFinishedTask) {
                Yii::getLogger()->log('Task "'. $nextFinishedTask->getDescription(). '" complete at '. $nextTaskTime->format('Y-d-d H:i:s'));
                $this->completeTask($nextFinishedTask, $nextTaskTime);
            } else {
                Yii::getLogger()->log('No more tasks can be executed. Quit running.');
                // no more task can be executed, so no more working unit can be released
                // quit running loop
                break;
            }
        }

        // we'll append all hold task to task queue
        while (!$this->_holdTaskQueue->isEmpty()) {
            $this->_taskQueue->enqueue($this->_holdTaskQueue->dequeue());
        }
    }

    /**
     *
     * @return Task
     */
    private function sendInTask(DateTime $activateTime){

        $task = $this->_taskQueue->dequeue();
        if ($task) {
            if ($task->isActivated()) {
                return $this->_runningTasks[] = $task;
            } elseif ($this->activateTask($task, $activateTime)) {
                return $this->_runningTasks[] = $task;
            } else {
                if ($task->hasErrors()) {
                    Yii::getLogger()->log('Task "'. $task->getDescription(). '" dropped because of error: '. Utils::modelError($task), 'debug');
                    Yii::app()->user->setFlash('error_task_dropped', 'Task "'. $task->getDescription(). '" is dropped because of error: '. Utils::modelError($task));
                    $task->delete();
                } else {
                    Yii::getLogger()->log('Task "'. $task->getDescription(). '" is not activated because of conflict.');
                    Yii::app()->user->setFlash('notice_task_dropped', 'Task "'. $task->getDescription(). '" is dropped because there is a same task in the running.');
                    $this->_holdTaskQueue->enqueue($task);
                }
            }
        }
    }


    private function activateTask(Task $task, DateTime $activateTime){

    	if ($this->beforeActivateTask($task, $activateTime)) {
    	    $task->setScenario('activate');
    	    $task->is_running = 1;
    	    $task->activate_time = $activateTime->format('Y-m-d H:i:s');
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

    protected function beforeActivateTask(Task $task, DateTime $dateTime){

        foreach ($this->_runningTasks as $running_task) {
            if ($task->hasConflictWith($running_task)) {
                // this task has conflict with one running task.
                // it has to wait at the end of queue.
                return false;
            }
        }
    	return $this->_executorChain->checkResource($task);
    }

    public function onAfterActivateTask($task){

        $event = new WorkflowTaskEvent($this, $task);
        $this->raiseEvent('onAfterActivateTask', $event);
    }

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

    public function onCompleteTask(Task $task){

        // whenever we complete a task, we append all hold task to task queue
        while (!$this->_holdTaskQueue->isEmpty()) {
            $this->_taskQueue->enqueue($this->_holdTaskQueue->dequeue());
        }

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
     * @param DateTime $dateTime
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

