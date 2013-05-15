<?php
$this->breadcrumbs=array(
	'Mine Blueprints'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List MineBlueprint','url'=>array('index')),
	array('label'=>'Manage MineBlueprint','url'=>array('admin')),
);
?>

<h1>Create MineBlueprint</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>