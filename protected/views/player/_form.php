<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'player-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'account_id',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'is_protected',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'active_state',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'vacation_mode_enabled',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'vacation_start_time',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'vacation_end_time',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'can_use_relay',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'galaxy_credit',array('class'=>'span5','maxlength'=>10)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
