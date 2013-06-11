<?php
/**
 * @var $queue array
 * @var $running array
 */
$now = new DateTime();

if (count($queue) + count($running) == 0) return;
?>

<div class="portlet_container" id="task-widget">
    <?php if (count($running)):?>
    <ol>
        <?php
        $now = time();
        foreach ($running as $task):
            $endTime = Utils::ensureDateTime($task['end_time'])->getTimestamp();
            $activeTime = Utils::ensureDateTime($task['activate_time'])->getTimestamp();
            ?>
        <li>
            <label><?php echo $task['desc'];?></label>

            <?php echo CHtml::link('', array('task/cancel', 'id' => $task['id']), array('class' => 'icon icon-remove icon-white'));?>
            <small class="muted">Finished in
            <?php
                echo CHtml::tag('span', array(
                    'class' => 'task-timer badge badge-inverse',
                    'data-inverse' => '1',
                    'data-time' => $endTime - $now,
                ))
            ?>
            </small>
            <?php
                echo CHtml::tag('div', array(
                    'class' => 'task-progress-bar',
                    'data-type' => 'active',
                    'data-elapsedtime' => $now - $activeTime,
                    'data-totaltime' => $endTime - $activeTime,
                    'data-taskId' => $task['id']
                ));
            ?>
        </li>
        <?php endforeach;?>
    </ol>
    <?php endif;?>
    <?php if (count($queue)):?>
    <h6>Task in queue:</h6>
    <ol>
        <?php foreach ($queue as $task):?>
        <li>
            <label><?php echo $task['desc'];?></label>
            <!-- <small class="muted">Created on <?php echo $task['create_time'];?></small> -->
            <?php echo CHtml::link('', array('task/cancel', 'id' => $task['id']), array('class' => 'icon icon-remove icon-white'));?>
        </li>
        <?php endforeach;?>
    </ol>
    <?php endif;?>

</div>

<script type="text/javascript">
    $('.task-progress-bar').progressTimer();
    $('.task-timer').timer();
    $(document).bind('progress-timer.ring', function(e, data){
        if (data.hasClass('task-progress-bar')) location.reload();
    });
</script>