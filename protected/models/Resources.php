<?php
class Resources extends Collection {

    /**
     * (non-PHPdoc)
     * @see CFormModel::attributeNames()
     */
    public function attributeNames(){

        return array('metal', 'metal_decimal', 'crystal', 'crystal_decimal', 'gas', 'gas_decimal', 'energy', 'energy_decimal');
    }

    /**
     * (non-PHPdoc)
     * @see CModel::beforeValidate()
     */
    protected function beforeValidate(){

        $int_items = array('metal', 'crystal', 'gas', 'energy');

        foreach ($int_items as $itemName) {

            $decimal_name = $itemName. '_decimal';

            while ($this->$decimal_name < 0) {
                $this->$decimal_name += 100000;
                --$this->$itemName;
            }

            while ($this->$decimal_name >= 100000) {
                $this->$decimal_name -= 100000;
                ++$this->$itemName;
            }
        }

        return parent::beforeValidate();
    }

    /**
     * (non-PHPdoc)
     * @see Collection::toArray()
     */
    public function toArray(){

        return $this->getAttributes(array('metal', 'crystal', 'gas', 'energy'));
    }

}
