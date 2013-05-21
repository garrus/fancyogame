<?php

class ShipExecutor extends \TaskExecutor {

    /**
     * (non-PHPdoc)
     * @see TaskExecutor::execute()
     */
    public function execute($chain) {

        $task = $chain->task;
        if ($task->getType() == Task::TYPE_BUILD_SHIPS) {

        	switch ($task->scenario) {
        		case 'checkrequirement':
        			if ('' !== ($error = TechTree::checkRequirement($task->getObject(), $chain->planet->buildings, $chain->planet->techs))) {
        				$task->addError('requirement', $error);
        				return;
        			}
        		case 'complete':
        			$chain->planet->ships->modify($task->getObject(), $task->getAmount());
        			break;
        		default:
        			break;
        	}
        }

        $chain->run();
    }
}
