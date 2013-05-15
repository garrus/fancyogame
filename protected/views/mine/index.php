<?php
$this->breadcrumbs=array(
	'Planet Mines',
);

$this->menu=array(
	array('label'=>'Create PlanetMine','url'=>array('create')),
	array('label'=>'Manage PlanetMine','url'=>array('admin')),
);
?>

<h1>Planet Mines</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
