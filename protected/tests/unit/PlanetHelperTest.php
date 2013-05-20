<?php

class PlanetHelperTest extends \CTestCase {

    
    public function testCreatePlanetAtLocation(){
        
        $location = new Location(1, 23, 12);
        
        $planet = PlanetHelper::createPlanetAtLocation($location);
        $this->assertInstanceOf('Planet', $planet);
        
        $planet = Planet::model()->findByPk($planet->id);
        $this->assertInstanceOf('Planet', $planet);
        $this->assertEquals('[1, 23, 12]', $planet->formatLocation());
    }
    
}
