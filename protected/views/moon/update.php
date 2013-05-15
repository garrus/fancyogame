<?php
$this->breadcrumbs=array(
	'Planet Moons'=>array('index'),
	$model->planet_id=>array('view','id'=>$model->planet_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PlanetMoon','url'=>array('index')),
	array('label'=>'Create PlanetMoon','url'=>array('create')),
	array('label'=>'View PlanetMoon','url'=>array('view','id'=>$model->planet_id)),
	array('label'=>'Manage PlanetMoon','url'=>array('admin')),
);
?>

<h1>Update PlanetMoon <?php echo $model->planet_id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>