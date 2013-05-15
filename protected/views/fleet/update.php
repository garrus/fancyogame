<?php
$this->breadcrumbs=array(
	'Fleets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Fleet','url'=>array('index')),
	array('label'=>'Create Fleet','url'=>array('create')),
	array('label'=>'View Fleet','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Fleet','url'=>array('admin')),
);
?>

<h1>Update Fleet <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>