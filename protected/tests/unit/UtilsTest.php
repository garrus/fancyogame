<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 13-6-12
 * Time: 下午11:39
 * To change this template use File | Settings | File Templates.
 */

class UtilsTest extends \CTestCase {

    public function testTimelinePercentage(){

        $this->assertEquals(0, Utils::timelinePercentage(0, 100, -5));
        $this->assertEquals(0, Utils::timelinePercentage(0, 100, 0));
        $this->assertEquals(5, Utils::timelinePercentage(0, 100, 5));
        $this->assertEquals(100, Utils::timelinePercentage(0, 100, 100));
        $this->assertEquals(100, Utils::timelinePercentage(0, 100, 105));
    }
}