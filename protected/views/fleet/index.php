<?php
$this->breadcrumbs=array(
	'Fleets',
);

$this->menu=array(
	array('label'=>'Create Fleet','url'=>array('create')),
	array('label'=>'Manage Fleet','url'=>array('admin')),
);
?>

<h1>Fleets</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
