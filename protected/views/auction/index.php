<?php
$this->breadcrumbs=array(
	'Auctions',
);

$this->menu=array(
	array('label'=>'Create Auction','url'=>array('create')),
	array('label'=>'Manage Auction','url'=>array('admin')),
);
?>

<h1>Auctions</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
