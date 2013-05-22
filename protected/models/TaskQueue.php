<?php
class TaskQueue extends SplQueue {

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
    
    public function __construct(array $tasks, DateTime $lastRunTime){
    	
    	foreach ($tasks as $task) {
    		$this->enqueue($task);
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

        return $this->_limit > 0 && $this->count() >= $this->_limit;
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
            parent::push($value);
        } else {
            throw new InvalidArgumentException('Expecting parameter 1 to be a WorkflowTask, '. gettype($value). ' given.');
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

