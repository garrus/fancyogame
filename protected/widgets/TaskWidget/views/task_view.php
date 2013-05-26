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
        <?php foreach ($running as $task):?>
        <li>
            <label><?php echo $task['desc'];?></label>

            <?php echo CHtml::link('', array('task/cancel', 'id' => $task['id']), array('class' => 'icon icon-remove icon-white'));?>
            <small class="muted">Finished in
                <span class="badge badge-inverse">
                    <?php echo Utils::formatDiff($task['end_time']);?>
                </span>
            </small>
            <div class="progress progress-striped active">
                <div class="bar" style="width: <?php echo Utils::timelinePercentage($task['activate_time'], $task['end_time']);?>%;">
                </div>

            </div>
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