<?php
$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
    'links' => array(
        'My Planets' => array('list'),
        CHtml::encode($model->name),
        )
    ));
?>
<h2><?php echo CHtml::encode($model->name);?> <?php echo $model->formatLocation();?></h2>

<?php
Yii::app()->clientScript->registerScript('home-funcs', <<<'SCRIPT'

function switchTab(event){
    $('.tab_view').hide();
    $('#tab_content_' + $(event.srcElement).data('content')).show();
}
SCRIPT
, CClientScript::POS_HEAD);


?>


<div class="row">
    <div class="span9">
        <?php
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
                        'label' => 'Ships',
                        'linkOptions' => array('data-content' => 'ships'),
                    ),
                    array(
                        'label' => 'Technology',
                        'linkOptions' => array('data-content' => 'techs'),
                    ),
                ),
            ));
        ?>
        <div class="tab_view" id="tab_content_buildings">
            <?php
            $resource = $model->resources;
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
                $this->widget('bootstrap.widgets.TbDetailView', array(
                    'data' => $model->ships,
                    'attributes' => $model->ships->attributeNames(),
                ));
                //echo CVarDumper::dumpAsString($model->ships->toArray(), 10, true);
            ?>
        </div>

        <div class="tab_view" id="tab_content_defences" style="display: none;">
            <?php
                //echo CVarDumper::dumpAsString($model->defenses->toArray(), 10, true);
            ?>
        </div>

        <div class="tab_view" id="tab_content_techs" style="display: none;">
            <?php
            $resource = $model->resources;
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

