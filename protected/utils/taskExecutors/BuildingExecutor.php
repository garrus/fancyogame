<?php
class BuildingExecutor extends \TaskExecutor {

    /**
     * (non-PHPdoc)
     * @see TaskExecutor::execute()
     */
    public function execute($chain) {

        $task = $chain->task;
        if ($task->type == Task::TYPE_CONSTRUCT || $task->type == Task::TYPE_DECONSTRUCT) {

            if ($task->scenario == 'finished') {
                $chain->planet->buildings->modify($task->target, $task->type == Task::TYPE_CONSTRUCT ? 1 : -1);
            } else {
                // activate / check requirement
                if ($task->type == Task::TYPE_CONSTRUCT
                    && $error = TechTree::checkRequirement($task->name, $chain->planet->buildings, $chain->planet->techs)
                    ) {
                    $task->addError('requirement', $error);
                    return;
                }
            }
        }

        $chain->run();
    }

}
