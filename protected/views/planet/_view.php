<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('owner_id')); ?>:</b>
	<?php echo CHtml::encode($data->owner_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('galaxy')); ?>:</b>
	<?php echo CHtml::encode($data->galaxy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('system')); ?>:</b>
	<?php echo CHtml::encode($data->system); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('position')); ?>:</b>
	<?php echo CHtml::encode($data->position); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('temperature')); ?>:</b>
	<?php echo CHtml::encode($data->temperature); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('is_colonized')); ?>:</b>
	<?php echo CHtml::encode($data->is_colonized); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('has_active_mine')); ?>:</b>
	<?php echo CHtml::encode($data->has_active_mine); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('has_moon')); ?>:</b>
	<?php echo CHtml::encode($data->has_moon); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gas_production_rate')); ?>:</b>
	<?php echo CHtml::encode($data->gas_production_rate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mine_limit')); ?>:</b>
	<?php echo CHtml::encode($data->mine_limit); ?>
	<br />

	*/ ?>

</div>