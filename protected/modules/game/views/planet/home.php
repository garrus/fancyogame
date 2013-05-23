<?php
$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
    'links' => array(
        'My Planets' => array('list'),
        CHtml::encode($model->name),
        )
    ));

?>
<h2><?php echo CHtml::encode($model->name);?> <?php echo $model->formatLocation();?></h2>

<?php
foreach (Yii::app()->user->getFlashes() as $key => $msg) :
    Utils::displayFlash($key, $msg);
endforeach;
?>

<?php
$res_list = array();

foreach ($model->resources->toArray() as $res => $amount) :
    $res_list[] = array('label' => $res. ': '. $amount, 'url' => 'javascript:void(0);');
endforeach;

foreach ($model->buildings->getProductionPerHour() as $res => $prod) :
    $res_list[] = array('label' => $res. '_: '. round($prod), 'url' => 'javascript:void(0);');
endforeach;
$energy = round($model->buildings->getEnergyPerHour() - $model->buildings->getEnergyCostPerHour(false));
$res_list[] = array('label' => 'Energy Prod: '. $energy, 'url' => 'javascript:void(0);');

$this->widget('bootstrap.widgets.TbMenu', array(
    'htmlOptions' => array('class' => 'nav-pills'),
    'items' => $res_list,
));

if (count($model->tasks)) :
    $task_count = array('queue' => 0, 'running' => 0);
    foreach ($model->tasks as $task) :
        if ($task->isActivated())
            ++$task_count['running'];
        else
            ++$task_count['queue'];
    endforeach;
    echo $task_count['running']. ' tasks in the running. ';
    if ($task_count['queue']) :
        echo $task_count['queue']. ' tasks in queue.';
    endif;
    unset($task_count);
endif;

echo 'Last updated: ', $model->planetData->last_update_time;

foreach ($model->buildings as $buildname => $level) :?>
<div class="row">
    <div class="span2"><?php echo ucwords(str_replace('_', ' ', $buildname));?></div>
    <div class="span2">Level: <em><?php echo $level;?></em></div>
    <div class="span3">
        <ul class="nav nav-pills">
            <li>
                <?php echo CHtml::link('Level Up', array('building/const', 'name' => $buildname));?>
                <?php echo $level!=0 ? CHtml::link('Level Down', array('building/deconst', 'name' => $buildname)) : ''?>
            </li>
        </ul>
    </div>
</div>

<?php endforeach;
