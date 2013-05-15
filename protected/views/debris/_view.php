<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('planet_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->planet_id),array('view','id'=>$data->planet_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('metal')); ?>:</b>
	<?php echo CHtml::encode($data->metal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('crystal')); ?>:</b>
	<?php echo CHtml::encode($data->crystal); ?>
	<br />


</div>