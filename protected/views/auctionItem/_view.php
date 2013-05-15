<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('auction_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->auction_id),array('view','id'=>$data->auction_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('depart_planet_id')); ?>:</b>
	<?php echo CHtml::encode($data->depart_planet_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_id')); ?>:</b>
	<?php echo CHtml::encode($data->item_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('count')); ?>:</b>
	<?php echo CHtml::encode($data->count); ?>
	<br />


</div>