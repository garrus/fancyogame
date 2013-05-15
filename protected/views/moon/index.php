<?php
$this->breadcrumbs=array(
	'Planet Moons',
);

$this->menu=array(
	array('label'=>'Create PlanetMoon','url'=>array('create')),
	array('label'=>'Manage PlanetMoon','url'=>array('admin')),
);
?>

<h1>Planet Moons</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
