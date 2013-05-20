<?php

class DefenceExecutor extends \TaskExecutor {

    /**
     * (non-PHPdoc)
     * @see TaskExecutor::execute()
     */
    public function execute($chain) {

        $task = $chain->task;
        if ($task->type == Task::TYPE_BUILD_DEFENCES) {

            if ($task->scenario == 'finished') {
                $chain->planet->defences->modify($task->target, $task->amount);
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
