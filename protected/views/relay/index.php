<?php
$this->breadcrumbs=array(
	'Relays',
);

$this->menu=array(
	array('label'=>'Create Relay','url'=>array('create')),
	array('label'=>'Manage Relay','url'=>array('admin')),
);
?>

<h1>Relays</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
