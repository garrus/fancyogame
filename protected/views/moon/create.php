<?php
$this->breadcrumbs=array(
	'Planet Moons'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PlanetMoon','url'=>array('index')),
	array('label'=>'Manage PlanetMoon','url'=>array('admin')),
);
?>

<h1>Create PlanetMoon</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>