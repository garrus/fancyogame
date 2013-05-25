<?php
/**
 * @var $data array
 * @var $factor number
 */
?>
<div class="portlet_container" id="res-widget">
	<ul class="nav nav-list">
        <?php foreach ($data as $item) :
            echo '<li>';
            echo '<div class="res_name">', CHtml::encode($item['label']), '</div>';
            echo '<div class="res_storage">', $item['storage'], '</div>';

            $prod_class = 'label';
            if ($item['prod'] < 0) {
                $prod_class .= ' label-important';
            } else {
                if ($factor != 1) {
                    $prod_class .= ' label-warning';
                } elseif ($item['prod'] != 0) {
                    $prod_class .= ' label-success';
                }
            }

            if ($item['prod'] < 0) {
                $prod_label = sprintf('(%d) 0/h', $item['prod']);
            } else {
                $prod_label = $factor == 1 ? round($item['prod']) . '/h' : sprintf('(%d) %d/h', $item['prod'], $item['prod'] * $factor);
            }
            echo CHtml::tag('span', array('class' => $prod_class), $prod_label);


            echo '</li>';
        endforeach;?>
    </ul>
</div>