<?php
$this->breadcrumbs=array(
	'Planets'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Planet','url'=>array('index')),
	array('label'=>'Create Planet','url'=>array('create')),
	array('label'=>'View Planet','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Planet','url'=>array('admin')),
);
?>

<h1>Update Planet <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>