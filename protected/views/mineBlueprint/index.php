<?php
$this->breadcrumbs=array(
	'Mine Blueprints',
);

$this->menu=array(
	array('label'=>'Create MineBlueprint','url'=>array('create')),
	array('label'=>'Manage MineBlueprint','url'=>array('admin')),
);
?>

<h1>Mine Blueprints</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
