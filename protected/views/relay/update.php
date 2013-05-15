<?php
$this->breadcrumbs=array(
	'Relays'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Relay','url'=>array('index')),
	array('label'=>'Create Relay','url'=>array('create')),
	array('label'=>'View Relay','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Relay','url'=>array('admin')),
);
?>

<h1>Update Relay <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>