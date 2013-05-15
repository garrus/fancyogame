<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discoverer_id')); ?>:</b>
	<?php echo CHtml::encode($data->discoverer_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('galaxy')); ?>:</b>
	<?php echo CHtml::encode($data->galaxy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('system')); ?>:</b>
	<?php echo CHtml::encode($data->system); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discover_time')); ?>:</b>
	<?php echo CHtml::encode($data->discover_time); ?>
	<br />


</div>