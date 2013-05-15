<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'fleet-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

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
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
