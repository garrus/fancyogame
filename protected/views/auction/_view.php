<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('seller_id')); ?>:</b>
	<?php echo CHtml::encode($data->seller_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_bidding_buyer_id')); ?>:</b>
	<?php echo CHtml::encode($data->last_bidding_buyer_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('recipient_planet_id')); ?>:</b>
	<?php echo CHtml::encode($data->recipient_planet_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category')); ?>:</b>
	<?php echo CHtml::encode($data->category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_active')); ?>:</b>
	<?php echo CHtml::encode($data->is_active); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_time')); ?>:</b>
	<?php echo CHtml::encode($data->start_time); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('end_time')); ?>:</b>
	<?php echo CHtml::encode($data->end_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_price')); ?>:</b>
	<?php echo CHtml::encode($data->start_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('buy_it_now_price')); ?>:</b>
	<?php echo CHtml::encode($data->buy_it_now_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_bid')); ?>:</b>
	<?php echo CHtml::encode($data->last_bid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_bid_time')); ?>:</b>
	<?php echo CHtml::encode($data->last_bid_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	*/ ?>

</div>