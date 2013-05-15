<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('planet_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->planet_id),array('view','id'=>$data->planet_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resources')); ?>:</b>
	<?php echo CHtml::encode($data->resources); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('buildings')); ?>:</b>
	<?php echo CHtml::encode($data->buildings); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ships')); ?>:</b>
	<?php echo CHtml::encode($data->ships); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('building_queue')); ?>:</b>
	<?php echo CHtml::encode($data->building_queue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('area')); ?>:</b>
	<?php echo CHtml::encode($data->area); ?>
	<br />


</div>