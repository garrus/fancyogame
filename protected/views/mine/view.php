<?php
$this->breadcrumbs=array(
	'Planet Mines'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List PlanetMine','url'=>array('index')),
	array('label'=>'Create PlanetMine','url'=>array('create')),
	array('label'=>'Update PlanetMine','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete PlanetMine','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PlanetMine','url'=>array('admin')),
);
?>

<h1>View PlanetMine #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'owner_id',
		'mine_blueprint_id',
		'planet_id',
		'trans_planet_id',
		'launch_time',
	),
)); ?>
