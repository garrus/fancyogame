<?php
/**
 * Class Resources
 *
 * @property int $metal
 * @property int $crystal
 * @property int $gas
 * @property int $energy
 *
 * @internal property int metal_decimal
 * @internal property int crystal_decimal
 * @internal property int gas_decimal
 * @internal property int energy_decimal
 */
class Resources extends Collection {

    /**
     * @return array
     */
    public function attributeNames(){

        return array('metal', 'metal_decimal', 'crystal', 'crystal_decimal', 'gas', 'gas_decimal', 'energy', 'energy_decimal');
    }

    /**
     * @return bool
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
     * @return array
     */
    public function toArray(){

        return $this->getAttributes(array('metal', 'crystal', 'gas', 'energy'));
    }

    /**
     * @param float $hours
     * @param array $resourceInfo
     * @throws ModelError
     */
    public function update(array $resourceInfo, $hours) {

        extract($resourceInfo);
        /**
         * @var int $mine_limit             @see Planet::getMineLimit()
         * @var int $gas_limit              @see Planet::getGasLimit()
         * @var int $energy_capacity        @see Calculator::energy_capacity()
         * @var int $res_capacity           @see Calculator::resource_capacity()
         * @var int $energy_prod_rate       @see Calculator::energy_prod_rate()
         * @var array $energy_consume_rate  @see Calculator::energy_consume_rate_detail()
         * @var array $res_prod_rate        @see Calculator::resource_prod_rate()
         */

        if ($res_prod_rate['metal'] + $res_prod_rate['crystal'] > $mine_limit) {
            $defactor = $mine_limit / ($res_prod_rate['metal'] + $res_prod_rate['crystal']);
            $res_prod_rate['metal'] *= $defactor;
            $res_prod_rate['crystal'] *= $defactor;
            $energy_consume_rate['metal_refinery'] *= $defactor;
            $energy_consume_rate['crystal_factory'] *= $defactor;
        }

        if ($res_prod_rate['gas'] > $gas_limit) {
            $defactor = $gas_limit / $res_prod_rate['gas'];
            $res_prod_rate['gas'] *= $defactor;
            $energy_consume_rate['gas'] *= $defactor;
        }

        $energy_diff = $hours * ($energy_prod_rate - array_sum($energy_consume_rate));

        if ($energy_diff + $this->energy > $energy_capacity){
            $energy_diff = $energy_capacity - $this->energy;
        } elseif ($energy_diff < 0 && $energy_diff + $this->energy < 0) {
            $hours *= ($this->energy + $energy_prod_rate) / array_sum($energy_consume_rate);
            $energy_diff = -$this->energy;
        }
        // TODO take energy override tech into consideration

        $res_diff = array_map(function($rate) use($hours){
            return $rate * $hours;
        }, $res_prod_rate);
        $res_diff['energy'] = $energy_diff;

        $res_consumed = $this->metal + $this->crystal + $this->gas;
        $new_res_place = $res_diff['metal'] + $res_diff['crystal'] + $res_diff['gas'];
        if ($new_res_place != 0 && $new_res_place + $res_consumed > $res_capacity) {
            // oh no, the production will be paused when capacity is totally occupied
            if ($res_capacity > $res_consumed) {
                $prod_defactor = 1 - ($res_capacity - $res_consumed) / $new_res_place;
            } else {
                $prod_defactor = 0;
            }
            $res_diff['metal'] *= $prod_defactor;
            $res_diff['crystal'] *= $prod_defactor;
            $res_diff['gas'] *= $prod_defactor;
        }

        foreach ($res_diff as $item => $value) {
            $res_diff[$item] = floor($value);
            $res_diff[$item.'_decimal'] = intval(($value - floor($value)) * 100000);
        }

        if (false == $this->modify($res_diff)) {
            throw new ModelError($this);
        }
    }



}
