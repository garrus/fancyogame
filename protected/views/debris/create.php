<?php
$this->breadcrumbs=array(
	'Planet Debrises'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PlanetDebris','url'=>array('index')),
	array('label'=>'Manage PlanetDebris','url'=>array('admin')),
);
?>

<h1>Create PlanetDebris</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>