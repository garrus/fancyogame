<?php

class TechExecutor extends \TaskExecutor {


    /**
     * (non-PHPdoc)
     * @see TaskExecutor::acceptedTaskTypes()
     */
    public function acceptedTaskTypes(){
        return array(Task::TYPE_RESEARCH);
    }

    /**
     * (non-PHPdoc)
     * @see TaskExecutor::execute()
     */
    public function execute($chain) {

        $task = $chain->task;
        if ($task->scenario == 'complete') {
            $chain->planet->techs->modify($task->getObject(), 1);
        }

        $chain->run();
    }

}
