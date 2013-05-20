<?php
/**
 *
 * @property int $solar_plant
 * @property int $metal_refinery
 * @property int $crystal_factory
 * @property int $warehouse
 * @property int $gas_plant
 * @property int $gas_storage
 * @property int $lab
 * @property int $shipyard
 * @property int $war_academy
 * @property int $robot_factory
 * @property int $nuclear_plant
 *
 * @author user
 */
class Buildings extends \Collection {


    /**
     * (non-PHPdoc)
     * @see CFormModel::attributeNames()
     */
    public function attributeNames(){

        return array(
            'solar_plant',
            'metal_refinery',
            'crystal_factory',
            'warehouse',
            'gas_plant',
            'gas_storage',
            'lab',
            'shipyard',
            'war_academy',
            'robot_factory',
            'nuclear_plant',
        );
    }

    public function getProductionPerHour(){

        return array(
            'metal' => 30 * Calculator::level_pow($this->metal_refinery),
            'crystal' => 20 * Calculator::level_pow($this->crystal_factory),
            'gas' => 10 * Calculator::level_pow($this->gas_plant) - 3 * Calculator::level_pow($this->nuclear_plant, 1.05),
        );
    }

    public function getEnergyCostPerHour($returnDetails=false){

        $details = array(
            'metal_refinery' => 10 * Calculator::level_pow($this->metal_refinery),
            'crystal_factory' => 10 * Calculator::level_pow($this->crystal_factory),
            'gas_plant' => 15 * Calculator::level_pow($this->gas_plant),
        );

        return $returnDetails ? $details : array_sum($details);
    }

    public function getEnergyPerHour($returnDetails=false){

        $details = array(
            'solar_planet' => 20 * Calculator::level_pow($this->solar_plant),
            'nuclear_planet' => 30 * Calculator::level_pow($this->nuclear_plant, 1.2),
            );
        return $returnDetails ? $details : array_sum($details);
    }


    public function getWarehouseCapacity(){

        return 20000 * $this->warehouse * Calculator::level_pow($this->warehouse, 1.2);
    }

    public function getMaxGasStorage(){

        return 10000 * $this->warehouse * Calculator::level_pow($this->warehouse, 1.2);
    }

    /**
     * Return how many materials a workflow can handle in an hour
     *
     * @return number
     */
    public function getWorkRate(){

        return round(20000 + 10000 * Calculator::level_pow($this->robot_factory));
    }

    /**
     * Return how many materials a lab can handle in an hour
     *
     * @return number
     */
    public function getResearchRate(){

        return round(10000 + 5000 * Calculator::level_pow($this->lab, 1.05));
    }

}
