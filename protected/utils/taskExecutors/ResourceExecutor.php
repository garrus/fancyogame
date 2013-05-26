<?php


/**
 * This executor will handle effects on Resource
 *
 * @author user
 */
class ResourceExecutor extends TaskExecutor {


	/**
	 * (non-PHPdoc)
	 *
	 * @see TaskExecutor::execute()
	 */
	public function execute($chain){

		$task = $chain->task;
		if ($task->scenario == 'activate') {
		    $planet = $chain->planet;
		    $res = $planet->resources;
		    $consumed_resource = self::getTaskConsume($task, $planet);
		    if (!$res->sub($consumed_resource)) {
		        $task->addError('requirement', Utils::modelError($res));
		        return;
		    }

		    $seconds = round($consumed_resource->totalAmount / $planet->buildings->getWorkRate() * 3600);
		    $end_time = Utils::ensureDateTime($task->activate_time)->add(new DateInterval('PT'. $seconds. 'S'));
		    $task->end_time = $end_time->format('Y-m-d H:i:s');
		}

		$chain->run();
	}


	/**
	 *
	 * @param Task $task
	 * @param ZPlanet $planet
	 * @return Resources
	 */
	public static function getTaskConsume($task, $planet){

	    switch($task->getType()){
	        case Task::TYPE_CONSTRUCT:
	            return $planet->buildings->getItemConsume($task->getObject());

	        case Task::TYPE_RESEARCH:
	            return $planet->techs->getItemConsume($task->getObject());

	        case Task::TYPE_BUILD_SHIPS:
	            return Ships::getItemConsumeOfCount($task->getObject(), $task->getAmount());

	        case Task::TYPE_BUILD_DEFENCES:
	            return Defences::getItemConsumeOfCount($task->getObject(), $task->getAmount());

	        default:
	            return new Resources();
	    }
	}

}
