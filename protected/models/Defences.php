<?php
/**
 * Class Defences
 * @property int rocket_launcher
 * @property int light_laser
 * @property int heavy_laser
 * @property int gauss_cannon
 * @property int neutron_cannon
 * @property int plasma_cannon
 * @property int light_shield
 * @property int heavy_shield
 */
class Defences extends \Collection {

    private static $_consumes = array(
        'rocket_launcher' => array('metal' => 2000, 'crystal' => 0, 'gas' => 0, 'energy' => 0),
        'light_laser' => array('metal' => 1500, 'crystal' => 500, 'gas' => 0, 'energy' => 0),
        'heavy_laser' => array('metal' => 6000, 'crystal' => 2000, 'gas' => 0, 'energy' => 0),
        'gauss_cannon' => array('metal' => 20000, 'crystal' => 15000, 'gas' => 2000, 'energy' => 0),
        'neutron_cannon' => array('metal' => 2000, 'crystal' => 6000, 'gas' => 0, 'energy' => 0),
        'plasma_cannon' => array('metal' => 50000, 'crystal' => 50000, 'gas' => 30000, 'energy' => 0),
        'light_shield' => array('metal' => 10000, 'crystal' => 10000, 'gas' => 0, 'energy' => 0),
        'heavy_shield' => array('metal' => 50000, 'crystal' => 50000, 'gas' => 0, 'energy' => 0),
    );


    /**
     * (non-PHPdoc)
     * @see CFormModel::attributeNames()
     */
    public function attributeNames(){

        return array(
            'rocket_launcher',
            'light_laser',
            'heavy_laser',
            'gauss_cannon',
            'neutron_cannon',
            'plasma_cannon',
            'light_shield',
            'heavy_shield',
        );
    }


    /**
     * @param $item
     * @param int|null $count
     * @return Resources
     */
    public function getItemConsume($item, $count=null){

        return self::getItemConsumeOfCount($item, $count ?: $this->$item);
    }

    /**
     *
     * @param string $item defence name
     * @param int $count
     * @return Resources
     */
    public static function getItemConsumeOfCount($item, $count){

        return Resources::c(array_map(function ($v) use ($count) {
            return round($v * $count);
        }, self::$_consumes[$item]));
    }

}
