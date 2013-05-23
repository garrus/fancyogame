<?php

abstract class Collection extends CFormModel implements JsonSerializable {


    private $_amounts = array();

    /**
     * (non-PHPdoc)
     * @see CComponent::__get()
     */
    public function __get($name){
        if (isset($this->_amounts[$name])) {
            return $this->_amounts[$name];
        } else {
            return parent::__get($name);
        }
    }

    /**
     * (non-PHPdoc)
     * @see CComponent::__set()
     */
    public function __set($name, $value){
        if (isset($this->_amounts[$name])) {
            return $this->setAttribute($name, $value);
        } else {
            return parent::__get($name);
        }
    }

    /**
     * (non-PHPdoc)
     * @see CComponent::__isset()
     */
    public function __isset($name){
        if (isset($this->_amounts[$name])) {
            return true;
        } else {
            return parent::__get($name);
        }
    }

    /**
     *
     * @param string $scenario
     */
    final public function __construct($scenario=''){
        foreach ($this->attributeNames() as $attr) {
            $this->_amounts[$attr] = 0;
        }
        return parent::__construct($scenario);
    }

    /**
     * (non-PHPdoc)
     * @see CFormModel::attributeNames()
     */
    public function attributeNames(){

        return array();
    }

    /**
     * (non-PHPdoc)
     * @see CModel::rules()
     */
    public function rules(){

        return array(
            array($this->attributeNames(), 'numerical', 'integerOnly' => true, 'allowEmpty' => false, 'min' => 0),
        );
    }

    /**
     * Construct from a json string
     *
     * @param string $json
     * @return Resources
     */
    public static function fromJson($json){

        $amounts = json_decode($json, true);
        if (is_array($amounts)) {
            return self::c($amounts);
        } else {
            throw new InvalidArgumentException('Parameter is not valid.');
        }
    }


    /**
     * Construct from an array specifying the amounts of each element
     *
     * @param array $amounts
     * @return Collection
     */
    public static function c($amounts){

        $model = new Static;
        foreach ($amounts as $name => $value){
            $model->$name = $value;
        }
        return $model;
    }

    /**
     * (non-PHPdoc)
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize(){

        return $this->getAttributes();
    }

    /**
     *
     * @param Collection $diff
     * @return boolean
     */
    public function add(Collection $diff){

        $stored_attrs = $this->_amounts;

        foreach ($this->attributeNames() as $attr) {
            $this->$attr += $diff->$attr;
        }

        if ($this->_amounts == $stored_attrs) {
            return true;
        }

        if (!$this->validate()) {
            $this->_amounts = $stored_attrs;
            return false;
        }
        $this->onChange();
        return true;
    }

    /**
     *
     * @param Collection $diff
     * @return boolean
     */
    public function sub(Collection $diff){

        $stored_attrs = $this->_amounts;

        foreach ($this->attributeNames() as $attr) {
            $this->$attr -= $diff->$attr;
        }

        if ($this->_amounts == $stored_attrs) {
            return true;
        }

        if (!$this->validate()) {
            $this->_amounts = $stored_attrs;
            return false;
        }
        $this->onChange();
        return true;
    }


    /**
     *
     * @param string $name
     * @param int $value must be a non-negative number
     * @throws CException
     */
    private function setAttribute($name, $value){
        if (is_numeric($value)) {
            $this->_amounts[$name] = intval($value, 10);
        } else {
            throw new CException('Invalid value for property '. get_class($this). '.'. $name);
        }
    }

    /**
     * Modify the value
     *
     * @param mixed $name can be the name or an associate array, key as the name, value as diff.
     * @param int $diff can be a negative or positive integer
     * @throws InvalidArgumentException
     * @return boolean
     */
    public function modify($name, $diff=null){

        $stored_attrs = $this->_amounts;

        if (is_string($name)) {
            $this->$name += $diff;
        } elseif (is_array($name)) {
            foreach ($name as $n => $diff) {
                $this->$n += $diff;
            }
        } else {
            throw new InvalidArgumentException('Expecting parameter 1 to be a string or array, '. gettype($name). ' given.');
        }

        //CVarDumper::dump($stored_attrs, 10, true);
       // CVarDumper::dump($this->_amounts, 10, true);

        if ($this->_amounts == $stored_attrs) {
            return true;
        }

        if (!$this->validate()) {
            $this->_amounts = $stored_attrs;
            return false;
        }

        $this->onChange();
        return true;
    }

    /**
     * Separate part of this collection as a new Collection
     *
     * @param array $amounts an associate array, key as name, value as amount
     * @return Collection false on failure
     */
    public function separate($amounts){

        if (array_sum($amounts)) return new Static;

        $stored_attrs = $this->_amounts;
        foreach ($amounts as $name => $value) {
            $this->$name -= $value;
        }

        if (!$this->validate()) {
            $this->_amounts = $stored_attrs;
            return false;
        }

        $model = Static::c($amounts);
        $this->onChange();

        return $model;
    }


    /**
     * Event onChange
     */
    public function onChange(){
        $event = new CEvent($this);
        $this->raiseEvent('onchange', $event);
    }


    /**
     *
     * @return number
     */
    public function getTotalAmount(){

        return array_sum($this->_amounts);
    }


    /**
     *
     * @return array
     */
    public function toArray(){

        return $this->getAttributes();
    }
}
