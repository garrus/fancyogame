<?php
$this->breadcrumbs=array(
	'Fleets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Fleet','url'=>array('index')),
	array('label'=>'Create Fleet','url'=>array('create')),
	array('label'=>'Update Fleet','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Fleet','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Fleet','url'=>array('admin')),
);
?>

<h1>View Fleet #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'owner_id',
		'departure_planet_id',
		'destination_planet_id',
		'task_type',
		'departure_time',
		'arrival_time',
		'is_finished',
		'is_aborted',
		'abort_time',
		'ships',
		'is_departure_moon',
	),
)); ?>
