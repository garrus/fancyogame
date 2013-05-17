<?php
$this->breadcrumbs=array(
	'Planets'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Planet','url'=>array('index')),
	array('label'=>'Create Planet','url'=>array('create')),
	array('label'=>'Update Planet','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Planet','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Planet','url'=>array('admin')),
);
?>

<h1>View Planet #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
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
