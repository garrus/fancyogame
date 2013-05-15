<?php
$this->breadcrumbs=array(
	'Planet Mines'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PlanetMine','url'=>array('index')),
	array('label'=>'Manage PlanetMine','url'=>array('admin')),
);
?>

<h1>Create PlanetMine</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>