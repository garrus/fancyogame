<?php
$this->breadcrumbs=array(
	'Mine Blueprints'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List MineBlueprint','url'=>array('index')),
	array('label'=>'Create MineBlueprint','url'=>array('create')),
	array('label'=>'View MineBlueprint','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage MineBlueprint','url'=>array('admin')),
);
?>

<h1>Update MineBlueprint <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>