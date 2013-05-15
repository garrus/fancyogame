<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
<hr>
<?php if (Yii::app()->user->isGuest) :?>
<h3>Please Login</h3>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->textFieldRow($model,'username',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->passwordFieldRow($model,'password',array('class'=>'span5','maxlength'=>32)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Login',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
<?php else :
	if (Yii::app()->user->hasState('last_login_time')) :
		echo '<h4>Welcome back, ', Yii::app()->user->name, '!</h4>';
		echo '<p>Your last login time is ', Yii::app()->user->last_login_time;
		echo ' from IP address ', Yii::app()->user->last_login_ip, '</p>';
	else:
		echo '<h4>Welcome new player, ', Yii::app()->user->name, '!</h4>';
	endif;
endif;?>