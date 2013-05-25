<?php
class TaskWidget extends \CWidget {

    public $tasks=array();

    public function run() {

        $queue = array();
        $running = array();

        foreach ($this->tasks as $task) {
            if ($task->isActivated()) {
                $running[] = $task->toArray();
            } else {
                $queue[] = $task->toArray();
            }
        }

        usort($running, function($task1, $task2){
            if (0 != ($ret = strcmp($task1['end_time'], $task2['end_time']))) {
                return $ret;
            }
            return strcmp($task1['activate_time'], $task2['activate_time']) ?:
                strcmp($task1['create_time'], $task2['create_time']);
        });
        usort($queue, function($task1, $task2){
            return strcmp($task1['create_time'], $task2['create_time']);
        });

        $this->render('task_view', array(
            'queue' => $queue,
            'running' => $running,
            ));
    }
}
