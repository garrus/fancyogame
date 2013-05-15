<?php
$this->breadcrumbs=array(
	'Planets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Planet','url'=>array('index')),
	array('label'=>'Manage Planet','url'=>array('admin')),
);
?>

<h1>Create Planet</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>