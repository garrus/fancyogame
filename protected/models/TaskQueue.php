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


    private $_pendingTaskCount = 0;

    public function __construct(array $tasks, DateTime $lastRunTime){

    	foreach ($tasks as $task) {
    		$this->enqueue($task);
    		if (!$task->isActivated()) {
    		    ++$this->_pendingTaskCount;
    		}
    	}
    	$this->_lastRunTime = $lastRunTime;

    }

    public function getLastRunTime(){

    	return $this->_lastRunTime;
    }

    /**
     * Set task limit
     *
     * @param int $limit
     * @throws InvalidArgumentException
     */
    public function setLimit($limit){

        if (is_numeric($limit) && $limit >= 1) {
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

        return $this->_limit > 0 && $this->_pendingTaskCount >= $this->_limit;
    }

    /**
     * (non-PHPdoc)
     * @see SplQueue::enqueue()
     */
    public function enqueue($value){

        if ($this->isFull()) {
            throw new BadMethodCallException('This task queue has reached its length limit.');
        }

        if ($value instanceof Task) {
            $this->push($value);
        } else {
            throw new InvalidArgumentException('Expecting parameter 1 to be a WorkflowTask, '. gettype($value). ' given.');
        }
    }

    /**
     * (non-PHPdoc)
     * @param Task $task
     * @see SplQueue::push()
     */
    public function push($task){
        parent::push($task);
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
    public function taskActivate($event){
        --$this->_pendingTaskCount;
    }

    /**
     *
     * @param WorkflowTaskEvent $event
     */
    public function taskComplete($event){

    	$this->_lastRunTime = $event->datetime;

    }

}

