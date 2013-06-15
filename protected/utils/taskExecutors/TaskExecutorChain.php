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
     */
    public function __construct(ZPlanet $planet) {

        $this->planet = $planet;
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

    /**
     *
     * @param Task $task
     * @return TaskExecutorChain
     */
    public function setTask(Task $task){
        $this->task = $task;
        $this->executorIndex = 0;
        return $this;
    }


    /**
     *
     * @param Task $task
     */
    public function runTask(Task $task){

        $this->setTask($task)->run();
    }


    /**
     * Check if the resource is enough for this task
     *
     * @param Task $task
     * @return boolean
     */
    public function checkResource(Task $task) {
        /** @var Resources $resource */
        $resource = Utils::cleanClone($this->planet->resources);
        $consumed_resource = ResourceExecutor::getTaskConsume($task, $this->planet);
        if (!$resource->sub($consumed_resource)) {
            $task->addError('requirement', Utils::modelError($resource));
        }

        return !$task->hasErrors();
    }


    /**
     *
     * @throws BadMethodCallException
     */
    public function run() {

        if (!$this->task) {
            throw new BadMethodCallException('No current task is set. (call TaskExecutorChain->setTask() first)');
        }

        Rotate_Executor:
        if ($this->offsetExists($this->executorIndex)) {
            $executor = $this->itemAt($this->executorIndex++);
            $types = $executor->acceptedTaskTypes();
            if (count($types) && !in_array($this->task->getType(), $types)) {
                goto Rotate_Executor;
            }
            $executor->execute($this);
        }
    }


}
