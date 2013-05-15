<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('account_id')); ?>:</b>
	<?php echo CHtml::encode($data->account_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_protected')); ?>:</b>
	<?php echo CHtml::encode($data->is_protected); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active_state')); ?>:</b>
	<?php echo CHtml::encode($data->active_state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vacation_mode_enabled')); ?>:</b>
	<?php echo CHtml::encode($data->vacation_mode_enabled); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vacation_start_time')); ?>:</b>
	<?php echo CHtml::encode($data->vacation_start_time); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('vacation_end_time')); ?>:</b>
	<?php echo CHtml::encode($data->vacation_end_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('can_use_relay')); ?>:</b>
	<?php echo CHtml::encode($data->can_use_relay); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('galaxy_credit')); ?>:</b>
	<?php echo CHtml::encode($data->galaxy_credit); ?>
	<br />

	*/ ?>

</div>