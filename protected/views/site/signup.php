<?php
/* @var $this SignupFormController */
/* @var $model SignupForm */
/* @var $form CActiveForm */
?>
<h1>Sign Up</h1>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'signup-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation' =>true,
	'clientOptions' => array('validateOnSubmit' => true),
)); ?>
	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'email',array('class'=>'span5','maxlength'=>64)); ?>

	<?php echo $form->textFieldRow($model,'login_name',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->passwordFieldRow($model,'password',array('class'=>'span5','maxlength'=>32)); ?>

	<?php echo $form->passwordFieldRow($model,'confirm_password',array('class'=>'span5','maxlength'=>32)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Sign Up',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
