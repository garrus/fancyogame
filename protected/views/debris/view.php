<?php
$this->breadcrumbs=array(
	'Planet Debrises'=>array('index'),
	$model->planet_id,
);

$this->menu=array(
	array('label'=>'List PlanetDebris','url'=>array('index')),
	array('label'=>'Create PlanetDebris','url'=>array('create')),
	array('label'=>'Update PlanetDebris','url'=>array('update','id'=>$model->planet_id)),
	array('label'=>'Delete PlanetDebris','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->planet_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PlanetDebris','url'=>array('admin')),
);
?>

<h1>View PlanetDebris #<?php echo $model->planet_id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'planet_id',
		'metal',
		'crystal',
	),
)); ?>
