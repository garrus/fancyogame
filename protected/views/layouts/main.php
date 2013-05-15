<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <?php Yii::app()->bootstrap->register();?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body style="">
<?php $this->widget('bootstrap.widgets.TbNavBar',array(
    'type' => 'inverse',
	'items'=>array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' =>array(
                array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'Sign Up', 'url'=>array('/site/signup'),'visible'=>Yii::app()->user->isGuest),
                array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
            ),
        )
	),
)); ?>

<div class="container" id="page" style="margin-top: 50px;">

	<?php echo $content; ?>

</div><!-- page -->

</body>
</html>
