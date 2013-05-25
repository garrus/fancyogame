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
		$planet = $chain->planet;
		$resource = $planet->resources;

		switch ($task->scenario) {

			case 'checkrequirement':
				$resource = Utils::cleanClone($planet->resources);
				$consumed_resource = $this->getTaskConsume($task, $planet);
				if (!$resource->sub($consumed_resource)) {
					$task->addError('requirement', Utils::modelError($resource));
					return;
				}
				break;

			case 'activate':
				$consumed_resource = $this->getTaskConsume($task, $planet);
				if (!$resource->sub($consumed_resource)) {
					$task->addError('requirement', Utils::modelError($resource));
					return;
				}
				$task->end_time = $this->getTaskEndTime($task, $planet, $consumed_resource);
				break;

			default:
				break;
		}

		$chain->run();
	}


	/**
	 *
	 * @param Task $task
	 * @param ZPlanet $planet
	 * @return Resources
	 */
	public function getTaskConsume($task, $planet){


	    switch($task->getType()){
	        case Task::TYPE_CONSTRUCT:
	            return $planet->buildings->getItemConsume($task->getObject());
	        case Task::TYPE_DECONSTRUCT:
	            return $planet->buildings->getItemConsume($task->getObject())->times(0.8);
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


	/**
	 *
	 * @param Task $task
	 * @param ZPlanet $planet
	 * @param Resources $resources
	 * @return string
	 */
	public function getTaskEndTime($task, $planet, $resources){

	    $seconds = round($resources->totalAmount / $planet->buildings->getWorkRate() * 3600);
		return Utils::ensureDateTime($task->activate_time)->add(new DateInterval('PT'. $seconds. 'S'))->format('Y-m-d H:i:s');
	}

}
