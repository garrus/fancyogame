<?php
$this->breadcrumbs=array(
	'Players'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Player','url'=>array('index')),
	array('label'=>'Create Player','url'=>array('create')),
	array('label'=>'Update Player','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Player','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Player','url'=>array('admin')),
);
?>

<h1>View Player #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'account_id',
		'name',
		'is_protected',
		'active_state',
		'vacation_mode_enabled',
		'vacation_start_time',
		'vacation_end_time',
		'can_use_relay',
		'galaxy_credit',
	),
)); ?>
