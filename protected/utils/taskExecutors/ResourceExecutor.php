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

		return Resources::c(array('metal' => 10 * mt_rand(1, 20), 'crystal' => 10 * mt_rand(1, 10), 'gas' => 5 * mt_rand(1, 10)));
	}


	/**
	 *
	 * @param Task $task
	 * @param ZPlanet $planet
	 * @param Resources $resources
	 * @return string
	 */
	public function getTaskEndTime($task, $planet, $resources){

	    $seconds = 15;//round($resources->totalAmount / $planet->buildings->getWorkRate() * 3600);
		return Utils::ensureDateTime($task->activate_time)->add(new DateInterval('PT'. $seconds. 'S'))->format('Y-m-d H:i:s');
	}

}
