<?php

class Ships extends \Collection {

    private static $_consumes = array(

        'light_cargo' => array('metal' => 2000, 'crystal' => 2000, 'gas' => 0, 'energy' => 0, 'consumption' => 20 , 'speed' => 5000, 'capacity' => 5000),
        'hevery_cargo' => array('metal' => 6000, 'crystal' => 6000, 'gas' => 0, 'energy' => 0, 'consumption' => 50 , 'speed' => 7500, 'capacity' => 25000),
        'light_hunter' => array('metal' => 3000, 'crystal' => 1000, 'gas' => 0, 'energy' => 0, 'consumption' => 20 , 'speed' => 12500, 'capacity' => 50),
        'heavy_hunter' => array('metal' => 6000, 'crystal' => 4000, 'gas' => 0, 'energy' => 0, 'consumption' => 75 , 'speed' => 10000, 'capacity' => 100),
        'crusher' => array('metal' => 20000, 'crystal' => 7000, 'gas' => 2000, 'energy' => 0, 'consumption' => 300 , 'speed' => 15000, 'capacity' => 800),
        'battleship' => array('metal' => 45000, 'crystal' => 15000, 'gas' => 0, 'energy' => 0, 'consumption' => 500 , 'speed' => 10000, 'capacity' => 1500),
        'colonizer' => array('metal' => 10000, 'crystal' => 20000, 'gas' => 10000, 'energy' => 0, 'consumption' => 1000, 'speed' => 2500, 'capacity' => 7500),
        'recycler' => array('metal' => 10000, 'crystal' => 6000, 'gas' => 2000, 'energy' => 0, 'consumption' => 300 , 'speed' => 2000, 'capacity' => 20000),
        'probe' => array('metal' => 0, 'crystal' => 1000, 'gas' => 0, 'energy' => 0, 'consumption' => 1 , 'speed' => 100000000, 'capacity' => 5),
        'bomber' => array('metal' => 50000, 'crystal' => 25000, 'gas' => 15000, 'energy' => 0, 'consumption' => 1000, 'speed' => 4000, 'capacity' => 500),
        'destroyer' => array('metal' => 0, 'crystal' => 2000, 'gas' => 500, 'energy' => 0, 'consumption' => 0 , 'speed' => 0, 'capacity' => 0),
        'destructor' => array('metal' => 60000, 'crystal' => 50000, 'gas' => 15000, 'energy' => 0, 'consumption' => 1000, 'speed' => 5000, 'capacity' => 2000),
        'battle_cruiser' => array('metal' => 30000, 'crystal' => 40000, 'gas' => 15000, 'energy' => 0, 'consumption' => 250 , 'speed' => 10000, 'capacity' => 750),
    );


    /**
     * (non-PHPdoc)
     * @see CFormModel::attributeNames()
     */
    public function attributeNames(){

        return array(
            'light_cargo',
            'hevery_cargo',
            'light_hunter',
            'heavy_hunter',
            'crusher',
            'battleship',
            'colonizer',
            'recycler',
            'probe',
            'bomber',
            'destroyer',
            'destructor',
            'battle_cruiser',
        );
    }


    /**
     *
     * @param string $item
     * @return Resources
     */
    public function getItemConsume($item){

        return self::getItemConsumeOfCount($item, $this->$item);
    }

    /**
     *
     * @param string $item ship name
     * @param int $count
     * @return Resources
     */
    public static function getItemConsumeOfCount($item, $count){

        return Resources::c(array_map(function($v) use($count){return round($v * $count);}, self::$_consumes[$item]));
    }

}
