<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('designer_id')); ?>:</b>
	<?php echo CHtml::encode($data->designer_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_cost')); ?>:</b>
	<?php echo CHtml::encode($data->resource_cost); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('production_rate')); ?>:</b>
	<?php echo CHtml::encode($data->production_rate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('designed_life')); ?>:</b>
	<?php echo CHtml::encode($data->designed_life); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('requirement')); ?>:</b>
	<?php echo CHtml::encode($data->requirement); ?>
	<br />


</div>