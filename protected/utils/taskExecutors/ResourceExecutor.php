<?php
/**
 * This executor will handle effects on Resource
 *
 * @author user
 */
class ResourceExecutor extends TaskExecutor {


    /**
     * (non-PHPdoc)
     * @see TaskExecutor::execute()
     */
    public function execute($chain) {

        $task = $chain->task;
        if ($task->scenario != 'finished') {

            $planet = $chain->planet;

            $consumed_resource = $this->getTaskConsume($chain->task, $planet);
            $resource = $chain->simulate ? Utils::cleanClone($planet->resources) : $planet->resources;

            if (!$resource->sub($consumed_resource)) {
                $task->addError('requirement', Utils::modelError($resource));
                return;
            }
            $task->end_time = $this->getTaskEndTime($chain->task, $planet);
        }

        $chain->run();
    }


    /**
     *
     * @param ZTask $task
     * @param ZPlanet $planet
     * @return Resources
     */
    public function getTaskConsume($task, $planet) {

        return Resources::c(array());
    }

    /**
     *
     * @param ZTask $task
     * @param ZPlanet $planet
     * @return string
     */
    public function getTaskEndTime($task, $planet) {

        return date('Y-m-d H:i:s');
    }

}
