<?php
class TaskWidget extends \CWidget {

    public $tasks;

    public function run() {

        if (count($this->tasks)) :
            $task_count = array(
                'queue' => 0,
                'running' => 0);
            foreach ($this->tasks as $task) :
                if ($task->isActivated())
                    ++$task_count['running'];
                else
                    ++$task_count['queue'];
            endforeach
            ;
            echo $task_count['running'] . ' tasks in the running. ';
            if ($task_count['queue']) :
                echo $task_count['queue'] . ' tasks in queue.';

        endif;
            unset($task_count);

        endif;
    }

}
