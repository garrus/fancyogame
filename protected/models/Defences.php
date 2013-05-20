<?php

class Defences extends \Collection {

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


}
