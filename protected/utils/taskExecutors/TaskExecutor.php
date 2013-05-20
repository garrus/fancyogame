<?php
/**
 * Base class of TaskExecutor
 *
 * @author user
 */
abstract class TaskExecutor extends CComponent {


    /**
     *
     * @param TaskExecutorChain $chain
     */
    abstract public function execute($chain);
}
