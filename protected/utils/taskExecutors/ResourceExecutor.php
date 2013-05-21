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
		if ($task->scenario != 'finished') {
			
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
					$task->end_time = $this->getTaskEndTime($task, $planet);
					break;
				
				default:
					break;
			}
		}
		
		$chain->run();
	}


	/**
	 *
	 * @param ZTask $task
	 * @param ZPlanet $planet
	 * @return Resources
	 */
	public function getTaskConsume($task, $planet){

		return Resources::c(array());
	}


	/**
	 *
	 * @param ZTask $task
	 * @param ZPlanet $planet
	 * @return string
	 */
	public function getTaskEndTime($task, $planet){

		return date('Y-m-d H:i:s');
	}

}
