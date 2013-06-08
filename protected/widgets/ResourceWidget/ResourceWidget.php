<?php

class ResourceWidget extends \CWidget {

    /**
     *
     * @var ZPlanet
     */
    public $planet;

    public function run(){

        $planet = $this->planet;
        $res = $planet->resources;
        $buildings = $planet->buildings;

        $prods = $buildings->getProductionPerHour();
        $energy_prod = $buildings->getEnergyPerHour();
        $energy_cost = $buildings->getEnergyCostPerHour(false);

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


        $energy_capacity = $buildings->getEnergyCapacity();
        $res_capacity = $buildings->getWarehouseCapacity();

        $factor = 1;
        if ($energy_prod < $energy_cost && $res->energy < 1) {
            $factor = $energy_prod / $energy_cost;
        }

        unset($energy_cost, $energy_prod, $prods, $res);

        $this->render('res_view', array(
            'factor' => $factor,
            'energy_data' => $energy_data,
            'res_data' => $res_data,
            'energy_capacity' => $energy_capacity,
            'res_capacity' => $res_capacity,
        ));
    }

}
