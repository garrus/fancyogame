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

<?php
Yii::app()->clientScript->registerScript('tab-switch', <<<'SCRIPT'
function switchTab(event){
    var $targetTab = $('#tab_content_' + $(event.srcElement ? event.srcElement : event.target).data('content'));
    if ($targetTab.length) {
        $('.tab_view').hide();
        $targetTab.show();
    }
}
SCRIPT
, CClientScript::POS_HEAD);?>

<div class="row">
    <div class="span9" id="planet-items-tabs">
        <?php
            $resource = $model->resources;
            $this->widget('bootstrap.widgets.TbTabs', array(
                'placement' => 'above',
                'events' => array(
                    'click' => 'js:switchTab',
                ),
                'tabs' => array(
                    array(
                        'label' => 'Buildings',
                        'active' => true,
                        'linkOptions' => array('data-content' => 'buildings'),
                    ),
                    array(
                        'label' => 'Technology',
                        'linkOptions' => array('data-content' => 'techs'),
                    ),
                    array(
                        'label' => 'Ships',
                        'linkOptions' => array('data-content' => 'ships'),
                    ),
                    array(
                        'label' => 'Defences',
                        'linkOptions' => array('data-content' => 'defences'),
                    ),
                ),
            ));
        ?>
        <div class="tab_view" id="tab_content_buildings">
            <?php
            foreach ($model->buildings as $buildname => $level) :
                $available = true;
                ob_start();
                $res = $model->buildings->getItemConsume($buildname);
                foreach ($res->getAttributes(array('metal', 'crystal', 'gas', 'energy')) as $item => $cost) :
                    if ($cost != 0) :
                        $match_requirement = $cost < $resource->$item;
                        $available = $available && $match_requirement;
                        echo CHtml::tag('div', array('class' => 'label label-'. ($match_requirement? 'success' : 'important')), ucfirst($item). ': '. $cost);
                    endif;
                endforeach;
                $res_badges = ob_get_clean();
                ?>
            <div class="row" style="line-height: 32px;">

                <div class="span2"><?php echo ucwords(str_replace('_', ' ', $buildname));?></div>
                <div class="span1">Level: <em><?php echo $level;?></em></div>
                <div class="span1">
                    <?php echo CHtml::link('Construct', array('task/add', 'type' => Task::TYPE_CONSTRUCT, 'name' => $buildname), array('class' => 'btn btn-success btn-small '. ($available ? '' : 'disabled')));?>
                    <?php // echo $level!=0 ? CHtml::link('Level Down', array('building/deconst', 'name' => $buildname), array('class' => 'btn btn-warning btn-small')) : ''?>
                </div>
                <div class="span4">
                    <?php echo $res_badges;?>
                </div>
            </div>

            <?php endforeach;?>
        </div>

        <div class="tab_view" id="tab_content_ships" style="display: none;">
            <?php
            foreach ($model->ships as $shipName => $count) :
                $available = true;
                ob_start();
                $res = $model->ships->getItemConsume($shipName, 1);
                foreach ($res->getAttributes(array('metal', 'crystal', 'gas', 'energy')) as $item => $cost) :
                    if ($cost != 0) :
                        $match_requirement = $cost < $resource->$item;
                        $available = $available && $match_requirement;
                        echo CHtml::tag('div', array('class' => 'label label-'. ($match_requirement? 'success' : 'important')), ucfirst($item). ': '. $cost);
                    endif;
                endforeach;
                $res_badges = ob_get_clean();
                ?>
                <div class="row" style="line-height: 32px;">

                    <div class="span2"><?php echo ucwords(str_replace('_', ' ', $shipName));?></div>
                    <div class="span1">Count: <em><?php echo $count;?></em></div>
                    <div class="span2">
                        <?php
                        echo CHtml::link('Build', array(
                            'task/add',
                            'type' => Task::TYPE_BUILD_SHIPS,
                            'name' => $shipName
                        ), array(
                            'class' => 'build-task-link btn btn-success btn-small '. ($available ? '' : 'disabled'),
                            'data-item' => $shipName,
                        ));
                        echo CHtml::textField('Ships['. $shipName. ']', 0, array(
                            'id' => 'build-amount-'. $shipName,
                            'class' => 'item-amount-input',
                            'size' => 5,
                        ));
                        ?>
                    </div>
                    <div class="span4">
                        <?php echo $res_badges;?>
                    </div>
                </div>

            <?php endforeach;?>
        </div>

        <div class="tab_view" id="tab_content_defences" style="display: none;">
            <?php
            foreach ($model->defences as $defName => $count) :
                $available = true;
                ob_start();
                $res = $model->defences->getItemConsume($defName, 1);
                foreach ($res->getAttributes(array('metal', 'crystal', 'gas', 'energy')) as $item => $cost) :
                    if ($cost != 0) :
                        $match_requirement = $cost < $resource->$item;
                        $available = $available && $match_requirement;
                        echo CHtml::tag('div', array('class' => 'label label-'. ($match_requirement? 'success' : 'important')), ucfirst($item). ': '. $cost);
                    endif;
                endforeach;
                $res_badges = ob_get_clean();
                ?>
                <div class="row" style="line-height: 32px;">

                    <div class="span2"><?php echo ucwords(str_replace('_', ' ', $defName));?></div>
                    <div class="span1">Count: <em><?php echo $count;?></em></div>
                    <div class="span2">
                        <?php
                        echo CHtml::link('Build', array(
                            'task/add',
                            'type' => Task::TYPE_BUILD_DEFENCES,
                            'name' => $defName
                        ), array(
                            'class' => 'build-task-link btn btn-success btn-small '. ($available ? '' : 'disabled'),
                            'data-item' => $defName,
                        ));
                        echo CHtml::textField('Defences['. $defName. ']', 0, array(
                            'id' => 'build-amount-'. $defName,
                            'class' => 'item-amount-input',
                            'size' => 5,
                            'type' => 'number',
                        ));
                        ?>
                    </div>
                    <div class="span4">
                        <?php echo $res_badges;?>
                    </div>
                </div>

            <?php endforeach;?>
        </div>

        <div class="tab_view" id="tab_content_techs" style="display: none;">
            <?php
            foreach ($model->techs as $techName => $level) :
                $available = true;
                ob_start();
                $res = $model->techs->getItemConsume($techName);
                foreach ($res->getAttributes(array('metal', 'crystal', 'gas', 'energy')) as $item => $cost) :
                    if ($cost != 0) :
                        $match_requirement = $cost < $resource->$item;
                        $available = $available && $match_requirement;
                        echo CHtml::tag('div', array('class' => 'label label-'. ($match_requirement? 'success' : 'important')), ucfirst($item). ': '. $cost);
                    endif;
                endforeach;
                $res_badges = ob_get_clean();
                ?>
            <div class="row" style="line-height: 32px;">

                <div class="span2"><?php echo ucwords(str_replace('_', ' ', $techName));?></div>
                <div class="span1">Level: <em><?php echo $level;?></em></div>
                <div class="span1">
                    <?php echo CHtml::link('Research', array('task/add', 'type' => Task::TYPE_RESEARCH, 'name' => $techName), array('class' => 'btn btn-success btn-small '. ($available ? '' : 'disabled')));?>
                </div>
                <div class="span4">
                    <?php echo $res_badges;?>
                </div>
            </div>

            <?php endforeach;?>
        </div>

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

<?php Yii::app()->clientScript->registerScript('', <<<'SCRIPT_TEXT'
$('#planet-items-tabs').delegate('a.build-task-link', 'click', function(e){
    var $link = $(this);
    var $input = $('#build-amount-' + $link.data('item'));
    var amount = $input.val();
    if (/^\d+$/.test(amount) && amount > 0) {
        this.href += '?amount=' + amount;
    } else {
        $input[0].focus();
        $input.tooltip({title: 'Please input a positive integer here.', placement: 'right', trigger: 'manual'}).tooltip('show');
        setTimeout(function(){
            $input.tooltip('destroy');
        }, 2000);
        return false;
    }
});


SCRIPT_TEXT
, CClientScript::POS_READY);

