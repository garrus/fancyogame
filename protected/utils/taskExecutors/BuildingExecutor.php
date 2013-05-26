<?php
class BuildingExecutor extends \TaskExecutor {

    /**
     * (non-PHPdoc)
     * @see TaskExecutor::acceptedTaskTypes()
     */
    public function acceptedTaskTypes(){
        return array(
            Task::TYPE_CONSTRUCT,
            );
    }

    /**
     * (non-PHPdoc)
     * @see TaskExecutor::execute()
     */
    public function execute($chain) {

        $task = $chain->task;
        if ($task->scenario == 'complete') {
            $chain->planet->buildings->modify($task->getObject(), 1);
        }

    	$chain->run();
    }

}
