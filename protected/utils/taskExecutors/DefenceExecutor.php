<?php

class DefenceExecutor extends \TaskExecutor {

    /**
     * (non-PHPdoc)
     * @see TaskExecutor::acceptedTaskTypes()
     */
    public function acceptedTaskTypes(){
        return array(Task::TYPE_BUILD_DEFENCES);
    }

    /**
     * (non-PHPdoc)
     * @see TaskExecutor::execute()
     */
    public function execute($chain) {

        $task = $chain->task;
        if ($task->scenario == 'complete') {
            $chain->planet->defences->modify($task->getObject(), $task->getAmount());
        }

        $chain->run();
    }

}
