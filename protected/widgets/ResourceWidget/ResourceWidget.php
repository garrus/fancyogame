<?php

class ResourceWidget extends \CWidget {

    /**
     *
     * @var ZPlanet
     */
    public $planet;

    public function run(){

        $prods = $this->planet->buildings->getProductionPerHour();
        $energy_prod = $this->planet->buildings->getEnergyPerHour();
        $energy_cost = $this->planet->buildings->getEnergyCostPerHour(false);
        $res = $this->planet->resources;

        $data = array(
            array(
                'label' => 'Energy',
                'storage' => $res->energy,
                'prod' => $energy_prod - $energy_cost,
            ),
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

        $this->render('res_view', array(
            'factor' => $factor,
            'data' => $data,
            ));



    }

}
