<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('owner_id')); ?>:</b>
	<?php echo CHtml::encode($data->owner_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mine_blueprint_id')); ?>:</b>
	<?php echo CHtml::encode($data->mine_blueprint_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('planet_id')); ?>:</b>
	<?php echo CHtml::encode($data->planet_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('trans_planet_id')); ?>:</b>
	<?php echo CHtml::encode($data->trans_planet_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('launch_time')); ?>:</b>
	<?php echo CHtml::encode($data->launch_time); ?>
	<br />


</div>