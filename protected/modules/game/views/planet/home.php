<?php
/**
 * @var $this PlanetController
 * @var $model ZPlanet
 */
$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
    'links' => array(
        'My Planets' => array('list'),
        CHtml::encode($model->name),
        )
    ));
?>
<h2><?php echo CHtml::encode($model->name);?> <?php echo $model->formatLocation();?></h2>

<div class="row">
    <div class="span9" id="planet-items-tabs">
        <?php
        $res = $model->resources;
        $buildings = $model->buildings;
        $showTech = $buildings->lab > 0;
        $showShipAndDef = $buildings->shipyard > 0;
        $this->widget('bootstrap.widgets.TbTabs', array(
            'placement' => 'above',
            'events' => array('click' => 'js:switchTab'),
            'tabs' => array(
                array('label' => 'Buildings', 'active' => true, 'linkOptions' => array('data-content' => 'buildings')),
                array('label' => 'Technology', 'visible' => $showTech, 'linkOptions' => array('data-content' => 'techs')),
                array('label' => 'Ships', 'visible' => $showShipAndDef, 'linkOptions' => array('data-content' => 'ships')),
                array('label' => 'Defences', 'visible' => $showShipAndDef, 'linkOptions' => array('data-content' => 'defences')),
            ),
        ));

        $this->renderPartial('_tab_buildings', array('buildings' => $buildings, 'res' => $res));
        if ($showTech) {
            $this->renderPartial('_tab_techs', array('techs' => $model->techs, 'res' => $res));
        }
        if ($showShipAndDef) {
            $this->renderPartial('_tab_ships', array('ships' => $model->ships, 'res' => $res));
            $this->renderPartial('_tab_defences', array('defences' => $model->defences, 'res' => $res));
        }
        ?>
    </div>
    <div class="span3">
        <?php
        $this->widget('application.widgets.ResourceWidget.ResourceWidget', array(
            'planet' => $model,
        ));
        $this->widget('application.widgets.TaskWidget.TaskWidget', array(
            'tasks' => $model->tasks,
        ));
        ?>
    </div>
</div>

<?php $cs = Yii::app()->clientScript;
$cs->registerScript('task-link-events', <<<'SCRIPT_TEXT'
$('#planet-items-tabs').delegate('a.build-task-link', 'click', function(e){
    var $input = $('#build-amount-' + $(this).data('item'));
    var amount = $input.val();
    if (/^\d+$/.test(amount) && amount > 0) {
        this.href += '?amount=' + amount;
        return true;
    } else {
        $input[0].focus();
        $input.tooltip({
            title: 'Please input a positive integer here.',
            placement: 'right',
            trigger: 'manual'}).tooltip('show');
        setTimeout(function(){
            $input.tooltip('destroy');
        }, 2000);
        return false;
    }
});
SCRIPT_TEXT
, CClientScript::POS_READY);

$cs->registerScript('tab-switch', <<<'SCRIPT_TEXT'
function switchTab(event){
    var $targetTab = $('#tab_content_' + $(event.srcElement ? event.srcElement : event.target).data('content'));
    if ($targetTab.length) {
        $('.tab_view').hide();
        $targetTab.show();
    }
}
SCRIPT_TEXT
, CClientScript::POS_HEAD);?>

