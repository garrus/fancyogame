<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('owner_id')); ?>:</b>
	<?php echo CHtml::encode($data->owner_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('departure_planet_id')); ?>:</b>
	<?php echo CHtml::encode($data->departure_planet_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('destination_planet_id')); ?>:</b>
	<?php echo CHtml::encode($data->destination_planet_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('task_type')); ?>:</b>
	<?php echo CHtml::encode($data->task_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('departure_time')); ?>:</b>
	<?php echo CHtml::encode($data->departure_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('arrival_time')); ?>:</b>
	<?php echo CHtml::encode($data->arrival_time); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('is_finished')); ?>:</b>
	<?php echo CHtml::encode($data->is_finished); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_aborted')); ?>:</b>
	<?php echo CHtml::encode($data->is_aborted); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('abort_time')); ?>:</b>
	<?php echo CHtml::encode($data->abort_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ships')); ?>:</b>
	<?php echo CHtml::encode($data->ships); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_departure_moon')); ?>:</b>
	<?php echo CHtml::encode($data->is_departure_moon); ?>
	<br />

	*/ ?>

</div>