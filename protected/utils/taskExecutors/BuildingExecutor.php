<?php
class BuildingExecutor extends \TaskExecutor {

    /**
     * (non-PHPdoc)
     * @see TaskExecutor::execute()
     */
    public function execute($chain) {

        $task = $chain->task;
        if ($task->getType() == Task::TYPE_CONSTRUCT || $task->getType() == Task::TYPE_DECONSTRUCT) {

        	switch ($task->scenario) {
        		case 'checkrequirement':
        			if ($task->getType() == Task::TYPE_CONSTRUCT) {
        				$error = TechTree::checkRequirement($task->getObject(), $chain->planet->buildings, $chain->planet->techs);
        				if ($error) {
        					$task->addError('requirement', $error);
        					return;
        				}
        			}
        			break;
        		case 'complete':
        			$chain->planet->buildings->modify($task->getObject(), $task->getType() == Task::TYPE_CONSTRUCT ? 1 : -1);
        			break;
        		default:
        			break;
        	}
        }

        $chain->run();
    }

}
