<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'relay-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'discoverer_id',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'galaxy',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'system',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'discover_time',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
