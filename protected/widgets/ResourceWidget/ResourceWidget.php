<?php

class ResourceWidget extends \CWidget {

    /**
     *
     * @var ZPlanet
     */
    public $planet;

    public function run(){

        $planet = $this->planet;
        $resInfo = $planet->getResourceInfo();
        $res = $planet->resources;
        $prods = $resInfo['res_prod_rate'];
        $energy_prod = $resInfo['energy_prod_rate'];
        $energy_cost = array_sum($resInfo['energy_consume_rate']);

        $energy_data = array(
            'label' => 'Energy',
            'storage' => $res->energy,
            'prod' => $energy_prod - $energy_cost,
        );

        $res_data = array(
            array(
                'label' => 'Metal',
                'storage' => $res->metal,
                'prod' => $prods['metal'],
                ),
            array(
                'label' => 'Crystal',
                'storage' => $res->crystal,
                'prod' => $prods['crystal'],
                ),
            array(
                'label' => 'Gas',
                'storage' => $res->gas,
                'prod' => $prods['gas'],
                ),
            );
        $factor = 1;
        if ($energy_prod < $energy_cost && $res->energy < 1) {
            $factor = $energy_prod / $energy_cost;
        }

        unset($energy_cost, $energy_prod, $prods, $res);

        $this->render('res_view', array(
            'factor' => $factor,
            'energy_data' => $energy_data,
            'res_data' => $res_data,
            'energy_capacity' => $resInfo['energy_capacity'],
            'res_capacity' => $resInfo['res_capacity'],
        ));
    }

}
