<?php
/**
 * 
 * 
 * @author user
 *
 */
class Location {

    public $gal=0;
    public $sys=0;
    public $pos=0;
    
    public static function isLocValid($gal, $sys, $pos){
        
        return is_int($gal) && is_int($sys) & is_int($pos)
            && $gal > 0 && $gal <= 16
            && $sys > 0 && $sys <= 256
            && $pos > 0 && $pos <= 16;
    }
    
    public function isValid(){
        
        return self::isLocValid($this->gal, $this->sys, $this->pos);
    }
    
    public function __construct($gal, $sys, $pos){

        $this->gal = $gal;
        $this->sys = $sys;
        $this->pos = $pos;
    }
    
    public function findPlanet(){
        
        return ZPlanet::model()->findByLocation($this);
    }

    /**
     * @param Location $loc
     * @return int
     */
    public function distance(Location $loc){
        
        return 1;
    }
    
    public function getDescription(){
        
        return sprintf('%s-%02s%s',
            StringHelper::getWord($this->gal),
            dechex($this->sys),
            StringHelper::getRoman($this->pos));
    }
    
    /**
     * 
     * @return number
     */
    public function generateTemperature(){

        if ($this->pos < 5) {
            return 330 + mt_rand((5 - $this->pos) * 10, (7 - $this->pos) * 20);
        }
        if ($this->pos > 12) {
            return 220 - mt_rand(($this->pos - 12) * 10, ($this->pos - 10) * 20);
        }
        return 350 - mt_rand(0, $this->pos * 15);
    }
    
    
    public function generateMiningFactor(){
        
        if ($this->pos < 7) {
            return mt_rand(5, 20) / 50 + 0.12 * ($this->pos + 1); // (0.1~0.4 + 0.24~0.84)
        }
        if ($this->pos > 10) {
            return mt_rand(5, 20) / 50 + 0.12 * (18 - $this->pos); // (0.1~0.4 + 0.24~0.84)
        }
        return 0.86 + mt_rand(5, 40) / 50; // 0.86 + 0.1~0.8
    }

}
