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
 * @var Defences $defences
 */

?>

<div class="tab_view" id="tab_content_defences" style="display: none;">
    <?php
    foreach ($defences as $defName => $count) :
        $available = true;
        ob_start();
        $res_consumed = $defences->getItemConsume($defName, 1);
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