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
 * @var Techs $techs
 */

?>
<div class="tab_view" id="tab_content_techs" style="display: none;">
    <?php
    foreach ($techs as $techName => $level) :
        $available = true;
        ob_start();
        $res_consumed = $techs->getItemConsume($techName);
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