<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'planet-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'owner_id',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'galaxy',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'system',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'position',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'temperature',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'is_colonized',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'has_active_mine',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'has_moon',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'gas_production_rate',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'mine_limit',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
