<?php
/**
 * 
 * @author user
 *
 */
class PlanetHelper {

    
    /**
     * Create mother planet for given player
     * 
     * @param Player $player
     * @throws CException if this player already owns planet
     * @return Planet
     */
    public static function createMotherPlanet(Player $player){
        
        if (Planet::model()->exists('owner_id=:owner_id', array('owner_id' => $player->id))) {
            throw new CException('This player already owns planet.');
        }
        
        $hex = md5($player->id. $player->name. $player->account_id);
        
        Gen_Location:
        $i = 0;
        do {
            $location = new Location(
                hexdec($hex[$i++]) + 1,
                hexdec($hex[$i++]. $hex[$i++]) + 1,
                mt_rand(4, 13)
                );
                
            $planet = $location->findPlanet();
            if (!$planet || self::isPlanetTouched($planet)) {
                break;
            } else {
                $location = null;
            }
        } while ($i < 30);
        
        if (!$location) {
            $hex = md5(microtime());
            goto Gen_Location;
        }
        
        
        $trans = Yii::app()->db->beginTransaction();
        
        try {
            if (!$planet) {
                $planet = self::createPlanetAtLocation($location);
            }
            self::setupMotherPlanet($player, $planet);
            $trans->commit();
        } catch (Exception $e) {
            $trans->rollback();
            throw $e;
        }
        return $planet;
    }
    
    /**
     * Setup mother planet
     * 
     * @param Player $player
     * @param Planet $planet
     */
    private static function setupMotherPlanet(Player $player, Planet $planet) {
        
        $planet->is_colonized = true;
        $planet->owner_id = $player->id;
        if (!$planet->save()) {
            throw new ModelError($planet);
        }
        $planet->owner = $player;
        
        $data = new PlanetData();
        $data->setResources(new Resources(2000, 1000, 500));
        $data->planet_id = $planet->id;
        $data->last_update_time = new CDbExpression('CURRENT_TIMESTAMP');
        if (!$data->save()) {
            throw new ModelError($data);
        }
        $data->planet = $planet;
        $planet->planetData = $data;
    }
    
    public static function setPlanetOwner(Planet $planet, Player $player) {
    
        $planet->is_colonized = true;
        $planet->owner_id = $player->id;
        if (!$planet->save()) {
            throw new ModelError($planet);
        }
        $planet->owner = $player;
    
        $data = new PlanetData();
        $data->setResources(new Resources);
        $data->planet_id = $planet->id;
        $data->last_update_time = new CDbExpression('CURRENT_TIMESTAMP');
        if (!$data->save()) {
            throw new ModelError($data);
        }
        $data->planet = $planet;
        $planet->planetData = $data;
    }
    
    /**
     * Return if the planet is touched
     * 
     * @param Planet $planet
     */
    public static function isPlanetTouched(Planet $planet, $ignoreFleet=false) {
        
        if ($planet->is_colonized) {
            return true;
        }

        if ($planet->has_active_mine) {
            return true;
        }
        
        if ($planet->has_moon) {
            return true;
        }
        
        if (!$ignoreFleet && Fleet::model()->exists('dest_planet_id=:planet_id', array('planet_id' => $planet->id))) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Create a planet at given location
     * 
     * @param Location $loc
     */
    public static function createPlanetAtLocation($loc){

        $planet = new Planet;
        
        $planet->name = $loc->getDescription();
        
        $planet->galaxy = $loc->gal;
        $planet->system = $loc->sys;
        $planet->position = $loc->pos;

        $planet->mine_limit = intval(150 * $loc->generateMiningFactor());
        $planet->temperature = $loc->generateTemperature();
        $planet->gas_production_rate = intval(50 * 273 / $planet->temperature);
        
        if (!$planet->save()) {
            throw new ModelError($planet);
        }
        
        return $planet;
    }
    
    
}
