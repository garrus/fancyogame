<?php
$this->breadcrumbs=array(
	'Auction Items',
);

$this->menu=array(
	array('label'=>'Create AuctionItem','url'=>array('create')),
	array('label'=>'Manage AuctionItem','url'=>array('admin')),
);
?>

<h1>Auction Items</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
