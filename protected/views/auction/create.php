<?php
$this->breadcrumbs=array(
	'Auctions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Auction','url'=>array('index')),
	array('label'=>'Manage Auction','url'=>array('admin')),
);
?>

<h1>Create Auction</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>