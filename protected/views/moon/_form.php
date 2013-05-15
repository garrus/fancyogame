<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'planet-moon-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'planet_id',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'resources',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'buildings',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'ships',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'building_queue',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'area',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
