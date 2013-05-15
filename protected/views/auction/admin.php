<?php
$this->breadcrumbs=array(
	'Auctions'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Auction','url'=>array('index')),
	array('label'=>'Create Auction','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('auction-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Auctions</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'auction-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'seller_id',
		'last_bidding_buyer_id',
		'recipient_planet_id',
		'category',
		'is_active',
		/*
		'start_time',
		'end_time',
		'start_price',
		'buy_it_now_price',
		'last_bid',
		'last_bid_time',
		'create_time',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
