<?php
/**
 * @var $data array
 * @var $factor float
 * @var $energy_data array
 * @var $res_data array
 * @var $energy_capacity int
 * @var $res_capacity int
 */
?>
<div class="portlet_container" id="res-widget">
	<ul class="nav nav-list">
	    <li>
	        <span class="res_name"><?php echo $energy_data['label'];?></span>
	        <!-- <span class="res_storage"><?php echo $energy_data['storage'];?></span> -->
	        <?php
            $energyProdAbs = abs($energy_data['prod']);
            $progressOptions = array(
                'id' => 'energy-capacity-bar',
                'title' => "{$energy_data['storage']} / {$energy_capacity}",
                'data-type' => 'active',
                'data-color' => 'warning'
            );
            $energy_percent = $energy_capacity ? round(100 * $energy_data['storage'] / $energy_capacity) : 0;
            if ($energy_percent < 30) {
                $progressOptions['data-color'] = 'danger';
            } elseif ($energy_percent > 70) {
                $progressOptions['data-color'] = 'success';
            }
            $progressOptions['data-totaltime'] = round(($energy_capacity / $energyProdAbs) * 3600);
            if ($energy_data['prod'] > 0) {
                $progressOptions['data-elapsedtime'] = round(($energy_data['storage'] / $energyProdAbs) * 3600);
            } elseif ($energy_data['prod'] < 0) {
                $progressOptions['data-inverse'] = 1;
                $progressOptions['data-elapsedtime'] = round((($energy_capacity - $energy_data['storage']) / $energyProdAbs) * 3600);
            } else {
                $progressOptions['data-type'] = 'default';
                $progressOptions['data-elapsedtime'] = round(($energy_data['storage'] / $energyProdAbs) * 3600);
            }

            $prod_class = 'label';
            $prod_label = sprintf('%d/h', $energyProdAbs);
            if ($energy_data['prod'] < 0) {
                $prod_label = '<i class="icon icon-arrow-down"></i>'. $prod_label;
                $prod_class .= ' label-important';
            } elseif ($energy_data['prod'] != 0) {
                $prod_label = '<i class="icon icon-arrow-up"></i>'. $prod_label;
                $prod_class .= ' label-success';
            }
            echo '<span class="'. $prod_class. '">'. $prod_label. '</span>';

            $class = 'badge';
            if ($factor < 1) {
                $class .= ' badge-warning';
            } elseif ($factor > 1) {
                $class .= ' badge-success';
            }
            echo CHtml::tag('div', array('class' => $class, 'style' => 'margin-left: 20px;'), round($factor * 100). '%');

            $timerOptions = array(
                'id' => 'energy-consume-timer',
                'data-inverse' => 1,
                'data-time' => $progressOptions['data-totaltime'] - $progressOptions['data-elapsedtime'],
            );

            if ($energy_data['prod'] > 0 && $energy_capacity > $energy_data['storage']) :?>
                <br><small class="text-info">Fully charged in <?php echo CHtml::tag('span', $timerOptions);?></small>
            <?php elseif ($energy_data['prod'] < 0 && $energy_data['storage'] > 0) :?>
                <br><small class="text-warning">Exhausted in <?php echo CHtml::tag('span', $timerOptions);?></small>
            <?php endif;?>
            <?php

            echo CHtml::tag('div', $progressOptions);
            ?>
	    </li>

        <?php $res_percent = array();
        $text_class = array('Metal' => 'text-error', 'Crystal' => 'text-info', 'Gas' => 'text-success');
        foreach ($res_data as $item) :
            $res_percent[$item['label']] = round(100 * $item['storage'] / $res_capacity);

            echo CHtml::openTag('li', array('class' => $text_class[$item['label']]));
            echo CHtml::tag('span', array('class' => 'res_name'), $item['label']);
            echo CHtml::tag('span', array('class' => 'res_storage'), $item['storage']);

            $htmlOptions = array('class' => 'label');

            if ($factor != 1) {
                $htmlOptions['class'] .= ' label-warning';
            } elseif ($item['prod'] != 0) {
                $htmlOptions['class'] .= ' label-success';
            }

            $prod_label = '';
            if ($item['prod'] != 0) {
                $prod_label .= round($item['prod'] * $factor) . '/h';
            } else {
                $prod_label = '0/h';
            }

            echo CHtml::tag('span', $htmlOptions, $prod_label);
            echo CHtml::closeTag('li');
        endforeach;

        $total_percent = array_sum($res_percent);
        if ($total_percent != 0 && $total_percent > 100) {
            array_walk($res_percent, function(&$val, $key) use($total_percent){
                $val = round(100 * $val / $total_percent);
            });
        }
        ?>
        <li>
            <div id="res-warehouse-capacity" class="progress" style="margin-top: 5px;margin-bottom: 0;">
                <div class="bar bar-danger" data-title="<?php printf('%s: %d / %d', 'Metal', $res_data[0]['storage'], $res_capacity);?>" style="width:<?php echo $res_percent['Metal'];?>%;"></div>
                <div class="bar bar-info" data-title="<?php printf('%s: %d / %d', 'Crystal', $res_data[1]['storage'], $res_capacity);?>" style="width:<?php echo $res_percent['Crystal'];?>%;"></div>
                <div class="bar bar-success" data-title="<?php printf('%s: %d / %d', 'Gas', $res_data[2]['storage'], $res_capacity);?>" style="width:<?php echo $res_percent['Gas'];?>%;"></div>
            </div>
        </li>
    </ul>
</div>
<script type="text/javascript">
    $('#res-warehouse-capacity').children('div.bar').tooltip({container: '#res-widget'});
    $('#energy-capacity-bar').progressTimer();
    $('#energy-consume-timer').timer();
</script>