<?php
$this->breadcrumbs=array(
	'Auction Items'=>array('index'),
	$model->auction_id=>array('view','id'=>$model->auction_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AuctionItem','url'=>array('index')),
	array('label'=>'Create AuctionItem','url'=>array('create')),
	array('label'=>'View AuctionItem','url'=>array('view','id'=>$model->auction_id)),
	array('label'=>'Manage AuctionItem','url'=>array('admin')),
);
?>

<h1>Update AuctionItem <?php echo $model->auction_id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>