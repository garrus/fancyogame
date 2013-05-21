<?php

class TechExecutor extends \TaskExecutor {

    /**
     * (non-PHPdoc)
     * @see TaskExecutor::execute()
     */
    public function execute($chain) {

        $task = $chain->task;
        if ($task->getType() == Task::TYPE_RESEARCH) {

        	switch ($task->scenario) {
        		case 'checkrequirement':
        			if ('' !== ($error = TechTree::checkRequirement($task->getObject(), $chain->planet->buildings, $chain->planet->techs))) {
        				$task->addError('requirement', $error);
        				return;
        			}
        		case 'complete':
        			$chain->planet->techs->modify($task->getObject(), 1);
        			break;
        		default:
        			break;
        	}
        }

        $chain->run();
    }

}
