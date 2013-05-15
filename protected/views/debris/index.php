<?php
$this->breadcrumbs=array(
	'Planet Debrises',
);

$this->menu=array(
	array('label'=>'Create PlanetDebris','url'=>array('create')),
	array('label'=>'Manage PlanetDebris','url'=>array('admin')),
);
?>

<h1>Planet Debrises</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
