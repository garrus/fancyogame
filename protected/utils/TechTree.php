<?php
class TechTree {

    /**
     * Check if the tech tree requirement is matched for given item
     *
     * @param ZPlanet $planet
     * @param $type
     * @param string $itemName
     * @return boolean
     */
    public static function checkRequirement(ZPlanet $planet, $type, $itemName) {
        Yii::log(sprintf('%s [%d] checked requirements', $itemName, $type));
        foreach (self::fetchRequirement($type, $itemName) as $reqItem){
            list($col, $item, $amount) = $reqItem;
            Yii::log(sprintf('%s [%d] requires %s %s to be no less than %d', $itemName, $type, $col, $item, $amount));
            if ($planet->$col->$item < $amount) return false;
        }
        return true;
    }

    /**
     * @param int $type see constants TYPE_xxx of Task
     * @param $itemName
     * @return array
     */
    public static function fetchRequirement($type, $itemName){

        $no = self::$_typeItemMap[$type][$itemName];
        return isset(self::$_requirements[$no]) ?
            self::parseRequirement(self::$_requirements[$no]) : array();
    }

    /**
     * @param array $req
     * @throws UnexpectedValueException
     * @return array each element contains 3 elements:
     * [0] the type, buildings|techs|ships|defences
     * [1] the item name
     * [2] the required amount
     */
    private static function parseRequirement($req){
        $ret = array();
        foreach ($req as $no => $amount) {
            switch (true) {
                case $no < 100: // buildings
                    $ret[] = array('buildings', array_search($no, self::$_typeItemMap[Task::TYPE_CONSTRUCT]), $amount);
                    break;
                case $no < 200: // techs
                    $ret[] = array('techs', array_search($no, self::$_typeItemMap[Task::TYPE_RESEARCH]), $amount);
                    break;
                case $no < 300: // ships
                    $ret[] = array('ships', array_search($no, self::$_typeItemMap[Task::TYPE_BUILD_SHIPS]), $amount);
                    break;
                case $no < 400: // defences
                    $ret[] = array('defences', array_search($no, self::$_typeItemMap[Task::TYPE_BUILD_DEFENCES]), $amount);
                    break;
                default:
                    throw new UnexpectedValueException('Unexpected item index '. $no);
            }
        }
        return $ret;
    }

    private static $_typeItemMap = array(

        Task::TYPE_CONSTRUCT => array(

            'metal_refinery' => 1,
            'crystal_factory' => 2,
            'gas_plant' => 3,
            'solar_plant' => 4,
            'nuclear_plant' => 12,
            'robot_factory' => 14,
            'shipyard' => 21,
            'lab' => 31,
            'warehouse' => 51,
            'computer_center' => 52,
            'war_academy' => 53,

        ),

        Task::TYPE_RESEARCH => array(

            'spy' => 106,
            'military' => 109,
            'shield' => 110,
            'defence' => 111,
            'energy' => 113,
            'hyperspace' => 114,
            'combustion' => 115,
            'impulse_motor' => 117,
            'hyperspace_motor' => 118,
            'laser' => 120,
            'ionic' => 121,
            'buster' => 122,
            'intergalactic' => 123,
        ),

        Task::TYPE_BUILD_SHIPS => array(

            'light_cargo' => 202,
            'heavy_cargo' => 203,
            'light_hunter' => 204,
            'heavy_hunter' => 205,
            'crusher' => 206,
            'battleship' => 207,
            'colonizer' => 208,
            'recycler' => 209,
            'probe' => 210,
            'bomber' => 211,
            'destroyer' => 214,
            'destructor' => 213,
            'battle_cruiser' => 215,
            'solar_satellite' => 212,

        ),

        Task::TYPE_BUILD_DEFENCES => array(

            'rocket_launcher' => 401,
            'light_laser' => 402,
            'heavy_laser' => 403,
            'gauss_cannon' => 404,
            'neutron_cannon' => 405,
            'plasma_cannon' => 406,
            'light_shield' => 407,
            'heavy_shield' => 408,
        ),
    );

    private static $_requirements = array(
        // buildings
        12 => array(   3 =>   5, 113 =>   3), // nuclear plant
        21 => array(  14 =>   2, 52  =>   1), // shipyard
        31 => array(  52 =>   1), // lab

        // techs
        106 => array(  31 =>   3), // spy
        109 => array(  31 =>   4), // military
        110 => array( 113 =>   3,  31 =>   6), // shield
        111 => array(  31 =>   2), // defence
        113 => array(  31 =>   1), // energy
        114 => array( 113 =>   5, 110 =>   5,  31 =>   7), // hyperspace
        115 => array( 113 =>   1,  31 =>   1), // combustion
        117 => array( 113 =>   1,  31 =>   2), // impulse_motor
        118 => array( 114 =>   3,  31 =>   7), // hyperspace_motor
        120 => array(  31 =>   1, 113 =>   2), // laser
        121 => array(  31 =>   4, 120 =>   5, 113 =>   4), // ionic
        122 => array(  31 =>   5, 113 =>   8, 120 =>  10, 121 =>   5), // buster
        123 => array(  31 =>  10, 108 =>   8, 114 =>   8), // intergalactic

        // ships
        202 => array(  21 =>   2, 115 =>   2), // light_cargo
        203 => array(  21 =>   4, 115 =>   6), // heavy_cargo
        204 => array(  21 =>   1, 115 =>   1), // light_hunter
        205 => array(  21 =>   3, 111 =>   2, 117 =>   2), // heavy_hunter
        206 => array(  21 =>   5, 117 =>   4, 121 =>   2), // crusher
        207 => array(  21 =>   7, 118 =>   4), // battleship
        208 => array(  21 =>   4, 117 =>   3), // colonizer
        209 => array(  21 =>   4, 115 =>   6, 110 =>   2), // recycler
        210 => array(  21 =>   3, 115 =>   3, 106 =>   2), // probe
        211 => array( 117 =>   6,  21 =>   8, 122 =>   5), // bomber
        212 => array(  21 =>   1), // solar_satellite
        213 => array(  21 =>   9, 118 =>   6, 114 =>   5), // destructor
        214 => array(  21 =>  12, 118 =>   7, 114 =>   6, 199 =>   1), // destroyer
        215 => array( 114 =>   5, 120 =>  12, 118 =>   5,  21 =>   8), // battle_cruiser

        // defences
        401 => array(  21 =>   1), // rocket_launcher
        402 => array( 113 =>   1,  21 =>   2, 120 =>   3), // light_laser
        403 => array( 113 =>   3,  21 =>   4, 120 =>   6), // heavy_laser
        404 => array(  21 =>   6, 113 =>   6, 109 =>   3, 110 =>   1), // gauss_cannon
        405 => array(  21 =>   4, 121 =>   4), // neutron_cannon
        406 => array(  21 =>   8, 122 =>   7), // plasma_cannon
        407 => array( 110 =>   2,  21 =>   1), // light_shield
        408 => array( 110 =>   6,  21 =>   6), // heavy_shield
    );

}
