<?php
$this->breadcrumbs=array(
	'Planet Moons'=>array('index'),
	$model->planet_id,
);

$this->menu=array(
	array('label'=>'List PlanetMoon','url'=>array('index')),
	array('label'=>'Create PlanetMoon','url'=>array('create')),
	array('label'=>'Update PlanetMoon','url'=>array('update','id'=>$model->planet_id)),
	array('label'=>'Delete PlanetMoon','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->planet_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PlanetMoon','url'=>array('admin')),
);
?>

<h1>View PlanetMoon #<?php echo $model->planet_id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'planet_id',
		'resources',
		'buildings',
		'ships',
		'building_queue',
		'area',
	),
)); ?>
