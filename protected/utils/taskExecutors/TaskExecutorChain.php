<?php

class TaskExecutorChain extends \CTypedList {

    /**
     *
     * @var ZPlanet
     */
    public $planet;

    /**
     *
     * @var Task
     */
    public $task;

    /**
     * Whether run in simulation mode. If true, the executors
     * won't change anything.
     *
     * @var boolean
     */
    public $simulate=false;

    /**
     * @var integer the index of the executors that is to be executed when calling {@link run()}.
     */
    public $executorIndex=0;

    /**
     * Constructor
     *
     * @param ZPlanet $planet
     * @param Task $task
     */
    public function __construct(ZPlanet $planet, Task $task) {

        $this->planet = $planet;
        $this->task = $task;

        parent::__construct('TaskExecutor');
    }

    /**
     *
     * @param boolean $flag
     * @return TaskExecutorChain
     */
    public function simulate($flag){

        $this->simulate = (boolean) $flag;
        return $this;
    }

    public function setTask(Task $task){
        $this->task = $task;
        $this->executorIndex = 0;
    }

    public function run() {

        if ($this->offsetExists($this->executorIndex)) {
            $executor = $this->itemAt($this->executorIndex++);
            $executor->execute($this);
        }
    }


}
