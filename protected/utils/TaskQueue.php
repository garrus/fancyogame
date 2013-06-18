<?php
class TaskQueue extends SplQueue {

    public $onTaskDeleted=null;

    /**
     * The length limit of this task queue
     * By default -1, means no limit.
     *
     * @var int
     */
    private $_limit = -1;

    /**
     *
     * @var DateTime
     */
    private $_lastRunTime;

    /**
     * @var int
     */
    private $_pendingTaskCount = 0;

    /**
     * @var array
     */
    private $_holdTasks = array();

    /**
     * @param array $tasks
     * @param DateTime $lastRunTime
     */
    public function __construct(array $tasks, DateTime $lastRunTime){

    	foreach ($tasks as $task) {
    		$this->enqueue($task);
    	}
    	$this->_lastRunTime = $lastRunTime;
    }

    /**
     * @return Task
     */
    public function dequeue(){
        $task = parent::dequeue();
        if (!$task->isActivated()) {
            --$this->_pendingTaskCount;
        }
        return $task;
    }

    /**
     * Put the task in hold queue
     *
     * @param Task $task
     */
    public function holdTask($task){
        if (!$task->isActivated()) {
            ++$this->_pendingTaskCount;
        }
        $this->_holdTasks[] = $task;
    }

    /**
     * Append all told tasks to queue
     */
    public function releaseHoldTasks(){
        foreach ($this->_holdTasks as $task) {
            parent::enqueue($task);
        }
    }

    /**
     * @return DateTime
     */
    public function getLastRunTime(){

    	return $this->_lastRunTime;
    }

    /**
     * @param DateTime $time
     */
    public function setLastRunTime($time){

        $this->_lastRunTime = $time;
    }

    /**
     * Set task limit
     *
     * @param int $limit should be an positive integer
     * @throws InvalidArgumentException
     */
    public function setMaxPendingTaskCount($limit){

        if (is_numeric($limit) && $limit >= 0) {
            $this->_limit = intval($limit);
        } else {
            throw new InvalidArgumentException('Parameter 1 should be a positive integer.');
        }
    }

    /**
     * Return if this task queue is full
     *
     * @return boolean
     */
    public function isFull(){

        return $this->_pendingTaskCount != 0 && $this->_limit != -1 && $this->_pendingTaskCount >= $this->_limit;
    }

    /**
     * @param Task $task
     * @throws InvalidArgumentException
     * @throws BadMethodCallException
     */
    public function enqueue($task){

        if (!$task instanceof Task) {
            throw new InvalidArgumentException('Expecting parameter 1 to be a Task, '. gettype($task). ' given.');
        }

        if (!$task->isActivated()) {
            if ($this->isFull()) {
                throw new BadMethodCallException('This task queue has reached its length limit.');
            }
            ++$this->_pendingTaskCount;
        }
        parent::enqueue($task);

        $task->attachEventHandler('onAfterDelete', array($this, 'onTaskDeleted'));
    }

    /**
     *
     * @param CModelEvent $event
     */
    public function onTaskDeleted($event){
        if ($this->onTaskDeleted) {
            if (is_callable($this->onTaskDeleted)) {
                call_user_func($this->onTaskDeleted, $event->sender);
            } else {
                $this->onTaskDeleted($event->sender);
            }
        }
    }

    /**
     *
     * @param WorkflowTaskEvent $event
     */
    public function taskComplete($event){
    	$this->_lastRunTime = $event->datetime;
    }

}

