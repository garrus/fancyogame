<?php
$this->breadcrumbs=array(
	'Auctions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Auction','url'=>array('index')),
	array('label'=>'Create Auction','url'=>array('create')),
	array('label'=>'View Auction','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Auction','url'=>array('admin')),
);
?>

<h1>Update Auction <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>