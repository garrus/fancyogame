<?php

class CollectionTest extends \CTestCase {

    public function testInit(){
        // don't know why Yii will warn cannot include JsonSerializable
        class_exists('Collection');
    }

    public function setUp(){
        if (version_compare('5.4', PHP_VERSION, '>=')) {
            $this->markTestSkipped('PHP version is too low. 5.4 required, '. PHP_VERSION. ' having.');
        }
    }

    public function testToJson(){

        $res = new Resources(1,2,3,4);
        $this->assertEquals(1, $res->metal);
        $this->assertEquals(2, $res->crystal);
        $this->assertEquals(3, $res->gas);
        $this->assertEquals(4, $res->energy);

        $json = json_encode($res);
        $_json = json_encode($res->attributes);

        $this->assertEquals($_json, $json);
        $_res = Resources::fromJson($json);

        foreach ($res as $key => $val) {
            $this->assertEquals($val, $_res->$key);
        }
    }

    public function testAdd(){

        $res = new Resources();
        $this->assertEquals(0, $res->metal);
        $this->assertEquals(0, $res->crystal);
        $this->assertEquals(0, $res->gas);
        $this->assertEquals(0, $res->energy);

        $this->assertTrue($res->add(new Resources(1,2,3,4)));
        $this->assertEquals(1, $res->metal);
        $this->assertEquals(2, $res->crystal);
        $this->assertEquals(3, $res->gas);
        $this->assertEquals(4, $res->energy);
    }

    public function testSub(){

        $res = new Resources(5,5,5,5);
        $this->assertEquals(5, $res->metal);
        $this->assertEquals(5, $res->crystal);
        $this->assertEquals(5, $res->gas);
        $this->assertEquals(5, $res->energy);

        $this->assertTrue($res->sub(new Resources(4,3,2,1)));
        $this->assertEquals(1, $res->metal);
        $this->assertEquals(2, $res->crystal);
        $this->assertEquals(3, $res->gas);
        $this->assertEquals(4, $res->energy);

        $this->assertFalse($res->sub(new Resources(100))); // metal would be negative
        $this->assertEquals(1, $res->metal);
        $this->assertEquals(2, $res->crystal);
        $this->assertEquals(3, $res->gas);
        $this->assertEquals(4, $res->energy);
    }


    public function testTriggerOnChangeEvent(){

        $counter = 0;
        $testcase = $this;

        $res = new Resources(5,5,5,5);
        $res->onChange = function($event) use($testcase, $res, & $counter){
            $testcase->assertEquals($res, $event->sender);
            ++$counter;
        };

        $this->assertTrue($res->sub(new Resources(4,3,2,1)));
        $this->assertEquals(1, $counter);

        $this->assertFalse($res->sub(new Resources(100)));
        $this->assertEquals(1, $counter, 'Unsuccessful operation won\'t trigger onChange event.');
    }


}
