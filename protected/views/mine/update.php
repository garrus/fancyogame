<?php
$this->breadcrumbs=array(
	'Planet Mines'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PlanetMine','url'=>array('index')),
	array('label'=>'Create PlanetMine','url'=>array('create')),
	array('label'=>'View PlanetMine','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage PlanetMine','url'=>array('admin')),
);
?>

<h1>Update PlanetMine <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>