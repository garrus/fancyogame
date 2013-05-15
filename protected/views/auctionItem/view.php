<?php
$this->breadcrumbs=array(
	'Auction Items'=>array('index'),
	$model->auction_id,
);

$this->menu=array(
	array('label'=>'List AuctionItem','url'=>array('index')),
	array('label'=>'Create AuctionItem','url'=>array('create')),
	array('label'=>'Update AuctionItem','url'=>array('update','id'=>$model->auction_id)),
	array('label'=>'Delete AuctionItem','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->auction_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AuctionItem','url'=>array('admin')),
);
?>

<h1>View AuctionItem #<?php echo $model->auction_id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'auction_id',
		'depart_planet_id',
		'item_id',
		'count',
	),
)); ?>
