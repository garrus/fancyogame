<?php
class TaskWidget extends \CWidget {

    /**
     * @var Task[]
     */
    public $tasks=array();

    public function run() {

        $queue = array();
        $running = array();

        foreach ($this->tasks as $task) {
            if ($task->isActivated()) {
                $running[] = $task;
            } else {
                $queue[] = $task;
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

    /**
     * @param $type int
     * @param $target string
     * @param $amount int
     * @return string
     */
    public function getTaskDescription($type, $target, $amount){

        switch ($type) {
            case Task::TYPE_BUILD_SHIPS:
                $verb = 'Building';
                break;
            case Task::TYPE_BUILD_DEFENCES:
                $verb = 'Building';
                break;
            case Task::TYPE_CONSTRUCT:
                $verb = 'Constructing';
                break;
            case Task::TYPE_RESEARCH:
                $verb = 'Researching on tech';
                break;
            default:
                return '';
        }

        $desc = sprintf('<small class="muted">%s</small> <em>%s</em>', $verb, ucwords(str_replace('_', ' ', $target)));
        if ($type == Task::TYPE_BUILD_DEFENCES
            || $type == Task::TYPE_BUILD_SHIPS) {
            $desc .= ' ['. $amount. ']';
        }

        return $desc;
    }
}
