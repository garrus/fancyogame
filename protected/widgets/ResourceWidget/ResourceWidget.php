<?php

class ResourceWidget extends \CWidget {

    public $planet;

    public function run(){

        $res_list = array();
        $resource = $this->planet->resources;
        foreach ($resource->toArray() as $res => $amount) :
        $res_list[] = array('label' => $res. ': '. $amount, 'url' => 'javascript:void(0);');
        endforeach;

        foreach ($this->planet->buildings->getProductionPerHour() as $res => $prod) :
        $res_list[] = array('label' => $res. '_: '. round($prod), 'url' => 'javascript:void(0);');
        endforeach;
        $energy = round($this->planet->buildings->getEnergyPerHour() - $this->planet->buildings->getEnergyCostPerHour(false));
        $res_list[] = array('label' => 'Energy Prod: '. $energy, 'url' => 'javascript:void(0);');

        $this->widget('bootstrap.widgets.TbMenu', array(
            'htmlOptions' => array('class' => 'nav-pills'),
            'items' => $res_list,
        ));
    }

}
