<?php
$this->breadcrumbs=array(
	'Relays'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Relay','url'=>array('index')),
	array('label'=>'Manage Relay','url'=>array('admin')),
);
?>

<h1>Create Relay</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>