<?php
$this->breadcrumbs=array(
	'Mine Blueprints'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List MineBlueprint','url'=>array('index')),
	array('label'=>'Create MineBlueprint','url'=>array('create')),
	array('label'=>'Update MineBlueprint','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete MineBlueprint','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MineBlueprint','url'=>array('admin')),
);
?>

<h1>View MineBlueprint #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'designer_id',
		'name',
		'resource_cost',
		'production_rate',
		'designed_life',
		'requirement',
	),
)); ?>
