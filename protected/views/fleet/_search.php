<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'owner_id',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'departure_planet_id',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'destination_planet_id',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'task_type',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'departure_time',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'arrival_time',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'is_finished',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'is_aborted',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'abort_time',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'ships',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'is_departure_moon',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
