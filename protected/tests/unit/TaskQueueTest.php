<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 13-6-11
 * Time: 下午8:33
 * To change this template use File | Settings | File Templates.
 */
class TaskQueueTest extends \CTestCase {


    public function testQueue(){

        $pendingTask = new Task;
        $pendingTask->is_running = 0;
        $activeTask = new Task;
        $activeTask->is_running = 1;

        // 4 pending tasks and 3 active tasks
        $tasks = array(
            $pendingTask,$pendingTask,$pendingTask,$pendingTask,
            $activeTask,$activeTask,$activeTask,
        );
        shuffle($tasks);

        $datetime = new DateTime;
        $queue = new TaskQueue($tasks, $datetime);
        $this->assertFalse($queue->isFull());
        $this->assertCount(count($tasks), $queue);
        $this->assertEquals($datetime, $queue->getLastRunTime());

        $queue->setLimit(2);
        $this->assertTrue($queue->isFull());
        $queue->enqueue($activeTask);
        $this->assertCount(count($tasks) + 1, $queue);

        try {
            $queue->enqueue($pendingTask);
            $this->fail('There should be an expected BadMethodCallException here.');
        } catch (BadMethodCallException $e) {

        }
        $queue->setLimit(5);
        $queue->enqueue($pendingTask);
        $this->assertCount(count($tasks) + 2, $queue);

        // now there should be 5 pending tasks and 4 active tasks
        $reflection = new ReflectionObject($queue);
        $property = $reflection->getProperty('_pendingTaskCount');
        $property->setAccessible(true);

        $this->assertEquals(5, $property->getValue($queue));
        $pending = 5;
        while (!$queue->isEmpty()) {
            $task = $queue->dequeue();
            if (!$task->isActivated()) {
                --$pending;
            }
            $this->assertEquals($pending, $property->getValue($queue));
        }
    }

}