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
 * @property int $computer_center
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
            'lab',
            'shipyard',
            'war_academy',
            'robot_factory',
            'nuclear_plant',
            'computer_center',
        );
    }


    /**
     *
     * @param string $item
     * @return Resources
     */
    public function getItemConsume($item){

        return self::getItemConsumeOfLevel($item, $this->$item);
    }

    /**
     *
     * @param string $item building name
     * @param int $level
     * @return Resources
     */
    public static function getItemConsumeOfLevel($item, $level){

        $data = self::$_consumes[$item];
        $factor = pow($data['factor'], $level);
        unset($data['factor']);
        return Resources::c(array_map(function ($v) use($factor) {
            return round($v * $factor);
        }, $data));
    }



    private static $_consumes = array(

        'solar_plant'     => array('metal' => 75,      'crystal' => 30,     'gas' => 0,      'energy' => 0, 'factor' => 1.5),
        'metal_refinery'  => array('metal' => 60,      'crystal' => 15,     'gas' => 0,      'energy' => 0, 'factor' => 1.5),
        'crystal_factory' => array('metal' => 48,      'crystal' => 24,     'gas' => 0,      'energy' => 0, 'factor' => 1.6),
        'warehouse'       => array('metal' => 2000,    'crystal' => 2000,   'gas' => 0,      'energy' => 0, 'factor' => 2),
        'gas_plant'       => array('metal' => 225,     'crystal' => 75,     'gas' => 0,      'energy' => 0, 'factor' => 1.5),
        'lab'             => array('metal' => 200,     'crystal' => 400,    'gas' => 200,    'energy' => 0, 'factor' => 2),
        'shipyard'        => array('metal' => 400,     'crystal' => 200,    'gas' => 100,    'energy' => 0, 'factor' => 2),
        'war_academy'     => array('metal' => 1000000, 'crystal' => 500000, 'gas' => 100000, 'energy' => 0, 'factor' => 2),
        'robot_factory'   => array('metal' => 400,     'crystal' => 120,    'gas' => 200,    'energy' => 0, 'factor' => 2),
        'nuclear_plant'   => array('metal' => 900,     'crystal' => 360,    'gas' => 180,    'energy' => 0, 'factor' => 1.8),
        'computer_center' => array('metal' => 100,     'crystal' => 400,    'gas' => 200,    'energy' => 0, 'factor' => 3),
    );

}
