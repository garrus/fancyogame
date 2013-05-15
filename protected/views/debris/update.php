<?php
$this->breadcrumbs=array(
	'Planet Debrises'=>array('index'),
	$model->planet_id=>array('view','id'=>$model->planet_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PlanetDebris','url'=>array('index')),
	array('label'=>'Create PlanetDebris','url'=>array('create')),
	array('label'=>'View PlanetDebris','url'=>array('view','id'=>$model->planet_id)),
	array('label'=>'Manage PlanetDebris','url'=>array('admin')),
);
?>

<h1>Update PlanetDebris <?php echo $model->planet_id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>