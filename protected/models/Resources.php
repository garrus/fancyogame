<?php
class Resources extends CFormModel implements JsonSerializable {
    
    public $metal;
    public $crystal;
    public $gas;
    public $energy;
    
    /**
     * 
     * @return array
     */
    public function rules(){
        return array(
            array('metal,crystal,gas,energy', 'numerical', 'allowEmpty' => false, 'integerOnly' => true, 'min' => 0),
        );
    }

    /**
     * Construct from a json string
     * 
     * @param string $json
     * @return Resources
     */
    public static function fromJson($json){
        
        $model = new self();
        foreach (json_decode($json, true) as $key => $value) {
            $model->$key = $value;
        }
        return $model;
    }
    
    /**
     * Constructor
     * 
     * @param numeric $metal
     * @param numeric $crystal
     * @param numeric $gas
     * @param numeric $energy
     * @throws InvalidArgumentException
     */
    public function __construct($metal=0, $crystal=0, $gas=0, $energy=0){
        
        $this->metal = $metal;
        $this->crystal = $crystal;
        $this->gas = $gas;
        $this->energy = $energy;
        
        if (!$this->validate()) {
            throw new ModelError($this);
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize(){
        
        return $this->getAttributes();
    }
    
    
    public function add(Resources $diff){

        $this->metal += $diff->metal;
        $this->crystal += $diff->crystal;
        $this->gas += $diff->gas;
        $this->energy += $diff->energy;
        
        if (!$this->validate()) {
            return false;
        }
        
        $this->onChange();
        return true;
    }
    
    public function sub(Resources $diff){
        
        $this->metal -= $diff->metal;
        $this->crystal -= $diff->crystal;
        $this->gas -= $diff->gas;
        $this->energy -= $diff->energy;
        
        if (!$this->validate()) {
            return false;
        }
        
        $this->onChange();
        return true;
    }
    
    /**
     * Event onChange
     */
    public function onChange(){
        $event = new CEvent($this);
        $this->raiseEvent('onChange', $event);
    }
}
