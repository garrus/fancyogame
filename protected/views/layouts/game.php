<?php /* @var $this Controller */ ?>
<!DOCTYPE html5>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <?php Yii::app()->bootstrap->register();?>
    <?php $cs = Yii::app()->clientScript;
    $cs->registerCssFile(Yii::app()->baseUrl. '/css/game.css');
    $cs->registerScriptFile(Yii::app()->baseUrl. '/js/common.js');
    ?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body style="background-color: black; color: #ccc; padding-top: 50px;">
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
                array('label' => 'Debug Menu', 'items' => array(
                    array('label' => 'Create Planet', 'url' => array('debug/createPlanet')),
                    array('label' => 'Fill Resource', 'url' => array('debug/fillResource'))
                )),

                array('label' => 'My Planets', 'url' => array('planet/list'), 'items' => $planet_items),
            ),
        )
	),
)); ?>

<div class="container" id="page" style="margin-top: 50px; min-height: 700px;">

	<?php echo $content; ?>

</div><!-- page -->

<div class="container" id="footer">
    <div class="label label-info">
        <?php echo count(Yii::getLogger()->getLogs(CLogger::LEVEL_TRACE, 'system.db.CDbCommand'));?> queries,
        <?php echo round(Yii::getLogger()->getExecutionTime() * 1000);?> ms,
        <?php echo round(Yii::getLogger()->getMemoryUsage()/1024/1024, 2);?> MB
    </div>
</div>
</body>
</html>
