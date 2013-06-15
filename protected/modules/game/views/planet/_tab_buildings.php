<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 13-6-15
 * Time: 上午11:02
 * To change this template use File | Settings | File Templates.
 *
 * @var PlanetController $this
 * @var Resources $res
 * @var Buildings $buildings
 */
?>
<div class="tab_view" id="tab_content_buildings">
    <?php
    foreach ($buildings as $buildName => $level) :
        $available = true;
        ob_start();
        $res_consumed = $buildings->getItemConsume($buildName);
        foreach ($res_consumed->getAttributes(array('metal', 'crystal', 'gas', 'energy')) as $item => $cost) :
            if ($cost != 0) :
                $match_requirement = $cost < $res->$item;
                $available = $available && $match_requirement;
                echo CHtml::tag('div', array('class' => 'label label-'. ($match_requirement? 'success' : 'important')), ucfirst($item). ': '. $cost);
            endif;
        endforeach;
        $res_badges = ob_get_clean();
        ?>
        <div class="row" style="line-height: 32px;">

            <div class="span2"><?php echo ucwords(str_replace('_', ' ', $buildName));?></div>
            <div class="span1">Level: <em><?php echo $level;?></em></div>
            <div class="span1">
                <?php echo CHtml::link('Construct', array('task/add', 'type' => Task::TYPE_CONSTRUCT, 'name' => $buildName), array('class' => 'btn btn-success btn-small '. ($available ? '' : 'disabled')));?>
            </div>
            <div class="span4">
                <?php echo $res_badges;?>
            </div>
        </div>

    <?php endforeach;?>
</div>