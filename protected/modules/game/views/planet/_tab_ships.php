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
 * @var Ships $ships
 */
?>

<div class="tab_view" id="tab_content_ships" style="display: none;">
    <?php
    foreach ($ships as $shipName => $count) :
        $available = true;
        ob_start();
        $res_consumed = $ships->getItemConsume($shipName, 1);
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