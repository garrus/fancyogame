<?php
/**
 * Base class of TaskExecutor
 *
 * @author user
 */
abstract class TaskExecutor extends CComponent {

    /**
     *
     * @return array a list of task types. empty means all types
     */
    public function acceptedTaskTypes(){
        return array();
    }

    /**
     *
     * @param TaskExecutorChain $chain
     */
    abstract public function execute($chain);
}
