<?php

class Techs extends \Collection {


    public function getPendingTaskLimit(){

        return 3;
    }


    public function getMaxWorkingUnit(){

        return 3;
    }

    public function attributeNames(){

        return array(
            'spy',
            'computer',
            'military',
            'defence',
            'shield',
            'energy',
            'hyperspace',
            'combustion',
            'impulse_motor',
            'hyperspace_motor',
            'laser',
            'ionic',
            'buster',
            'intergalactic',
            'expedition',
        );
    }

    /**
     *
     * @param string $item
     * @return Resources
     */
    public function getItemConsume($item){

        return self::getItemConsumeOfLevel($item, $this->$item + 1);
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
        'spy' => array ( 'metal' =>     200, 'crystal' =>    1000, 'gas' =>     200, 'energy' =>    0, 'factor' =>   2),
        'computer' => array ( 'metal' =>       0, 'crystal' =>     400, 'gas' =>     600, 'energy' =>    0, 'factor' =>   2),
        'military' => array ( 'metal' =>     800, 'crystal' =>     200, 'gas' =>       0, 'energy' =>    0, 'factor' =>   2),
        'defence' => array ( 'metal' =>     200, 'crystal' =>     600, 'gas' =>       0, 'energy' =>    0, 'factor' =>   2),
        'shield' => array ( 'metal' =>    1000, 'crystal' =>       0, 'gas' =>       0, 'energy' =>    0, 'factor' =>   2),
        'energy' => array ( 'metal' =>       0, 'crystal' =>     800, 'gas' =>     400, 'energy' =>    0, 'factor' =>   2),
        'hyperspace' => array ( 'metal' =>       0, 'crystal' =>    4000, 'gas' =>    2000, 'energy' =>    0, 'factor' =>   2),
        'combustion' => array ( 'metal' =>     400, 'crystal' =>       0, 'gas' =>     600, 'energy' =>    0, 'factor' =>   2),
        'impulse_motor' => array ( 'metal' =>    2000, 'crystal' =>    4000, 'gas' =>    6000, 'energy' =>    0, 'factor' =>   2),
        'hyperspace_motor' => array ( 'metal' =>   10000, 'crystal' =>   20000, 'gas' =>    6000, 'energy' =>    0, 'factor' =>   2),
        'laser' => array ( 'metal' =>     200, 'crystal' =>     100, 'gas' =>       0, 'energy' =>    0, 'factor' =>   2),
        'ionic' => array ( 'metal' =>    1000, 'crystal' =>     300, 'gas' =>     100, 'energy' =>    0, 'factor' =>   2),
        'buster' => array ( 'metal' =>    2000, 'crystal' =>    4000, 'gas' =>    1000, 'energy' =>    0, 'factor' =>   2),
        'intergalactic' => array ( 'metal' =>  240000, 'crystal' =>  400000, 'gas' =>  160000, 'energy' =>    0, 'factor' =>   2),
        'expedition' => array ( 'metal' =>    4000, 'crystal' =>    8000, 'gas' =>    4000, 'energy' =>    0, 'factor' =>   2),
    );



}
