<?php
$this->breadcrumbs=array(
	'Auction Items'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AuctionItem','url'=>array('index')),
	array('label'=>'Manage AuctionItem','url'=>array('admin')),
);
?>

<h1>Create AuctionItem</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>