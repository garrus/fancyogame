<?php
$this->breadcrumbs=array(
	'Relays'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Relay','url'=>array('index')),
	array('label'=>'Create Relay','url'=>array('create')),
	array('label'=>'Update Relay','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Relay','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Relay','url'=>array('admin')),
);
?>

<h1>View Relay #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'discoverer_id',
		'galaxy',
		'system',
		'discover_time',
	),
)); ?>
