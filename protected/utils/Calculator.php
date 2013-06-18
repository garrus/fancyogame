<?php
/**
 * Class Calculator
 * All about calculation
 */
class Calculator {

    private static $_result_cache=array();

    /**
     * @param int $level
     * @param float $factor
     * @return int|string
     */
    public static function level_pow($level, $factor=1.1){

        if ($level != 0) {
            $id = 'level_pow'. $level. $factor;
            if (!isset(self::$_result_cache[$id])) {
                return self::$_result_cache[$id] = $level. pow($factor, $level);
            }
            return self::$_result_cache[$id];
        } else {
            return 0;
        }
    }

    /**
     * @param int $solar_plant
     * @param int $nuclear_plant
     * @param int $solar_satellite
     * @param array $factors
     * @return number
     */
    public static function energy_prod_rate($solar_plant, $nuclear_plant, $solar_satellite, array $factors=array()){

        return array_sum(self::energy_prod_rate_detail($solar_plant, $nuclear_plant, $solar_satellite, $factors));
    }

    /**
     * @param int $solar_plant
     * @param int $nuclear_plant
     * @param int $solar_satellite
     * @param array $factors
     * @return array
     */
    public static function energy_prod_rate_detail($solar_plant, $nuclear_plant, $solar_satellite, array $factors=array()){

        $f = array_replace(array(
            'planet_temperature' => 273,
            'energy_tech' => 0,
            'nuclear_prod_rate' => 1,
        ), $factors);

        return array(
            'solar_planet' => 20 * self::level_pow($solar_plant) * (1 + $f['energy_tech'] * 0.03),
            'nuclear_planet' => 30 * self::level_pow($nuclear_plant, 1.2) * $f['nuclear_prod_rate'] * (1 + $f['energy_tech'] * 0.1),
            'solar_satellite' => 20 * $solar_satellite * (1 + $f['energy_tech'] * 0.04) * $f['planet_temperature'] / 273,
        );
    }

    /**
     * @param int $solar_plant
     * @param int $energy_tech
     * @return float
     */
    public static function energy_capacity($solar_plant, $energy_tech){

        if ($energy_tech < 5) {
            return 0;
        }

        return round(300 * (1 + 0.3 * ($energy_tech - 5)) * $solar_plant / 3);
    }

    /**
     * @param int $metal_refinery
     * @param int $crystal_factory
     * @param int $gas_plant
     * @param array $factors
     * @return number
     */
    public static function energy_consume_rate($metal_refinery, $crystal_factory, $gas_plant, array $factors=array()){

        return array_sum(self::energy_consume_rate_detail($metal_refinery, $crystal_factory, $gas_plant, $factors));
    }

    /**
     * @param int $metal_refinery
     * @param int $crystal_factory
     * @param int $gas_plant
     * @param array $factors
     * @return array
     */
    public static function energy_consume_rate_detail($metal_refinery, $crystal_factory, $gas_plant, array $factors=array()){

        $f = array_replace(array(
            'metal_prod_rate' => 1,
            'crystal_prod_rate' => 1,
            'gas_prod_rate' => 1,
        ), $factors);

        return array(
            'metal_refinery' => 9 * self::level_pow($metal_refinery) * $f['metal_prod_rate'],
            'crystal_factory' => 11 * self::level_pow($crystal_factory) * $f['crystal_prod_rate'],
            'gas_plant' => 15 * self::level_pow($gas_plant) * $f['gas_prod_rate'],
        );
    }

    /**
     * @param int $metal_refinery
     * @param int $crystal_factory
     * @param int $gas_plant
     * @param int $nuclear_plant
     * @param array $factors
     * @return array
     */
    public static function resource_prod_rate($metal_refinery, $crystal_factory, $gas_plant, $nuclear_plant, array $factors=array()){

        $f = array_replace(array(
            'metal_prod_rate' => 1,
            'crystal_prod_rate' => 1,
            'gas_prod_rate' => 1,
            'nuclear_prod_rate' => 1,
        ), $factors);

        return array(
            'metal' => 30 * self::level_pow($metal_refinery) * $f['metal_prod_rate'],
            'crystal' => 20 * self::level_pow($crystal_factory) * $f['crystal_prod_rate'],
            'gas' => 15 * self::level_pow($gas_plant) * $f['gas_prod_rate']
                - 3 * self::level_pow($nuclear_plant, 1.05) * $f['nuclear_prod_rate']
        );
    }

    /**
     * @param int $warehouse
     * @return int
     */
    public static function resource_capacity($warehouse){

        return 20000 + 20000 * self::level_pow($warehouse, 1.5);
    }


    /**
     * Calculate how many material can be handled in an hour during building construction
     *
     * @param int $robot_factory
     * @return float
     */
    public static function construct_rate($robot_factory){

        return round(20000 * (1 + $robot_factory + sqrt($robot_factory + 1)));
    }

    /**
     * Calculate how many material can be handled in an hour during building ships/defences
     *
     * @param int $shipyard
     * @param int $robot_factory
     * @return float
     */
    public static function build_rate($shipyard, $robot_factory){

        return round(30000 + 8000 * self::level_pow($shipyard, 1.3) + 5000 * self::level_pow($robot_factory));
    }

    /**
     * Return how many materials a lab can handle in an hour
     *
     * @param int $lab
     * @return number
     */
    public static function research_rate($lab){

        return round(1000 + 3000 * self::level_pow($lab, 1.1));
    }

    /**
     * calculate how many pending tasks this planet can manage at most
     *
     * @param $computer_center
     * @return int
     */
    public static function max_pending_task_count($computer_center){

        return floor($computer_center / 2);
    }

    /**
     * calculate how many fleets this planet can manage at most
     *
     * @param int $computer_center
     * @return mixed
     */
    public static function max_fleet_count($computer_center){

        return 1 + $computer_center;
    }

    /**
     * calculate how many workflow threads this planet can manage at most
     *
     * @param int $computer_center
     * @param int $robot_factory
     * @return int
     */
    public static function max_workflow_thread_count($computer_center, $robot_factory){

        $count = 1;

        if ($computer_center !=0) {
            ++$count;
        } else {
            return $count;
        }
        if ($computer_center > 2 && $robot_factory > 3) {
            ++$count;
        } else {
            return $count;
        }

        if ($computer_center > 4 && $robot_factory > 7) {
            ++$count;
        } else {
            return $count;
        }

        if ($computer_center > 7 && $robot_factory > 11) {
            ++$count;
        }

        return $count;
    }
}
