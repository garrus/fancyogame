<?php
class Resources extends Collection {

    /**
     * (non-PHPdoc)
     * @see CFormModel::attributeNames()
     */
    public function attributeNames(){

        return array('metal', 'crystal', 'gas', 'energy');
    }

}
