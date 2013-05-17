<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <?php Yii::app()->bootstrap->register();?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body style="background-color: black; color: #ccc;">
<?php 
$planet_items = array();
$current_planet = Yii::app()->actx->getPlanet();
if ($current_planet) {
    $planet_items[] = array('label' => $current_planet->name. ' '. $current_planet->formatLocation(), 'url' => array('planet/enter', 'id' => $current_planet->id));
    $planet_items[] = array('divider' => true);
}
foreach ($this->player->planets as $planet) {
    if ($current_planet && $current_planet->id == $planet->id) continue;
    $planet_items[] = array('label' => $planet->name. ' '. $planet->formatLocation(), 'url' => array('planet/enter', 'id' => $planet->id));
}

$this->widget('bootstrap.widgets.TbNavBar',array(
    'type' => 'inverse',
	'items'=>array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'htmlOptions' => array('class' => 'pull-right'),
            'items' =>array(
                array('label' => 'Home', 'url' => array('/site/index')),
                array('label' => 'Create Planet', 'url' => array('planet/create')),
                array('label' => 'My Planets', 'url' => array('planet/list'), 'items' => $planet_items),
            ),
        )
	),
)); ?>

<div class="container" id="page" style="margin-top: 50px;">

	<?php echo $content; ?>

</div><!-- page -->

</body>
</html>