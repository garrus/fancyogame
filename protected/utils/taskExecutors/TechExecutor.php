<?php

class TechExecutor extends \TaskExecutor {

    /**
     * (non-PHPdoc)
     * @see TaskExecutor::execute()
     */
    public function execute($chain) {

        $task = $chain->task;
        if ($task->type == Task::TYPE_RESEARCH) {

            if ($task->scenario == 'finished') {
                $chain->planet->techs->modify($task->target, 1);
            } else {
                // activate / check requirement
                if ('' !== ($error = TechTree::checkRequirement($task->name, $chain->planet->buildings, $chain->planet->techs))) {
                    $task->addError('requirement', $error);
                    return;
                }
            }
        }

        $chain->run();
    }

}
