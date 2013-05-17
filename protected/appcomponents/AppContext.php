<?php
/**
 * 
 * @author user
 *
 */
class AppContext extends \CApplicationComponent {

    /**
     * 
     * @var Player
     */
    private $_player;
    
    /**
     * 
     * @var Planet
     */
    private $_planet;

    /**
     * 
     * @return CHttpSession
     */
    protected function getSession(){
        
        return Yii::app()->session;
    }
    
    /**
     * 
     * @return WebUser
     */
    protected function getWebUser(){
    
        return Yii::app()->user;
    }
    
    /**
     * Get current player
     * 
     * @return Player return null on not found
     */
    public function getPlayer(){
        
        if (!$this->_player) {
            $player_id = $this->getSession()->get('current_player', null);
            if ($player_id) {
                $player = Player::model()->findByPk($player_id);
                if ($player) {
                    return $this->_player = $player;
                } else {
                    $this->getSession()->remove('current_player');
                }
            }
        }
        
        return $this->_player;
    }
    
    /**
     * Get current planet
     * 
     * @return Planet return null on not found
     */
    public function getPlanet(){
        
        if (!$this->_planet) {
            $planet_id = $this->getSession()->get('current_planet', null);
            if ($planet_id) {
                $planet = Planet::model()->findByPk($planet_id);
                if ($planet) {
                    return $this->_planet = $planet;
                } else {
                    $this->getSession()->remove('current_planet');
                }
            }
        }
        
        return $this->_planet;
    }
    
    /**
     * Switch current planet
     * 
     * @param Planet $planet
     * @throws CException
     */
    public function switchPlanet(Planet $planet){
        
        if ($this->getSession()->get('current_player') == $planet->owner_id) {
            $this->getSession()->add('current_planet', $planet->id);
            
            $this->_planet = $planet;
        } else {
            throw new CException('Invalid operation. Planet '. $planet->name. ' is not of current player.');
        }
    }
    
    /**
     * Switch current player
     * 
     * @param Player $player
     * @throws CException
     */
    public function switchPlayer(Player $player){
        
        if ($this->getWebUser()->getId() == $player->account_id) {
            $this->getSession()->add('current_player', $player->id);
            
            $this->_player = $player;
        } else {
            throw new CException('Invalid operation. Player '. $player->name. ' is not of current logged in user.');
        }
    }
    
}
