<?php
$this->breadcrumbs=array(
	'Fleets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Fleet','url'=>array('index')),
	array('label'=>'Manage Fleet','url'=>array('admin')),
);
?>

<h1>Create Fleet</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>