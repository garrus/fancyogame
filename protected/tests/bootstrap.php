<?php

// change the following paths if necessary
$yiit='C:\local\yii\framework\yiit.php';
$config=dirname(__FILE__).'/../config/test.php';
date_default_timezone_set('Asia/Shanghai');
require_once($yiit);
//require_once(dirname(__FILE__).'/WebTestCase.php');

Yii::createWebApplication($config);
