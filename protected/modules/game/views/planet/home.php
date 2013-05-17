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
$this->widget('bootstrap.widgets.TbDetailView',array(
    'data'=>$model,
    'attributes'=>array(
        'id',
        'owner_id',
        'galaxy',
        'system',
        'position',
        'name',
        'temperature',
        'is_colonized',
        'has_active_mine',
        'has_moon',
        'gas_production_rate',
        'mine_limit',
    ),
)); ?>