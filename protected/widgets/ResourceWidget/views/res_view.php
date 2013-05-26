<?php
/**
 * @var $data array
 * @var $factor number
 */

?>
<div class="portlet_container" id="res-widget">
	<ul class="nav nav-list">
	    <li>
	        <span class="res_name"><?php echo $energy_data['label'];?></span>
	        <!-- <span class="res_storage"><?php echo $energy_data['storage'];?></span> -->
	        <?php $prod_class = 'label';
	            $prod_label = sprintf('%d/h', abs($energy_data['prod']));
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
            ?>
	        <div class="progress progress-striped progress-warning" style="margin-top: 5px;" title="<?php echo $energy_data['storage'];?> / <?php echo $energy_capacity;?>">
                <div class="bar" style="width: <?php echo $energy_capacity ? round(100 * $energy_data['storage'] / $energy_capacity) : 0;?>%;">
                </div>
            </div>
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
            echo '</li>';
        endforeach;?>
        <li>
            <div class="progress" style="margin-top: 5px;margin-bottom: 0px;">
                <div class="bar bar-danger" style="width:<?php echo $res_percent['Metal'];?>%;"></div>
                <div class="bar bar-info" style="width:<?php echo $res_percent['Crystal'];?>%;"></div>
                <div class="bar bar-success" style="width:<?php echo $res_percent['Gas'];?>%;"></div>
            </div>
        </li>
    </ul>
</div>