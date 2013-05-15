<?php
$this->breadcrumbs=array(
	'Auctions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Auction','url'=>array('index')),
	array('label'=>'Create Auction','url'=>array('create')),
	array('label'=>'Update Auction','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Auction','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Auction','url'=>array('admin')),
);
?>

<h1>View Auction #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'seller_id',
		'last_bidding_buyer_id',
		'recipient_planet_id',
		'category',
		'is_active',
		'start_time',
		'end_time',
		'start_price',
		'buy_it_now_price',
		'last_bid',
		'last_bid_time',
		'create_time',
	),
)); ?>
