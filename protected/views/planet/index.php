<?php
$this->breadcrumbs=array(
	'Planets',
);

$this->menu=array(
	array('label'=>'Create Planet','url'=>array('create')),
	array('label'=>'Manage Planet','url'=>array('admin')),
);
?>

<h1>Planets</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
