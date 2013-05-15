<?php
$this->breadcrumbs=array(
	'Mine Blueprints'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List MineBlueprint','url'=>array('index')),
	array('label'=>'Create MineBlueprint','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('mine-blueprint-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Mine Blueprints</h1>

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
	'id'=>'mine-blueprint-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'designer_id',
		'name',
		'resource_cost',
		'production_rate',
		'designed_life',
		/*
		'requirement',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
