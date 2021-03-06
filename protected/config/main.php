<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$config = array(
	'basePath'=>dirname(__DIR__),
	'name'=>'Fancy Ogame',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
	    'application.models.*',
		'application.models.ar.*',
	    'application.models.forms.*',
		'application.components.*',
		'application.exceptions.*',
		'application.utils.*',
	),

    'aliases' => array(
        'bootstrap' => 'ext.bootstrap',
    ),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>false,
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		    'generatorPaths' => array(
		        'bootstrap.gii',
		    ),
		),

	    'game',
	),

	// application components
	'components'=>array(
		'user'=>array(
			'class' => 'application.appcomponents.WebUser',
			'loginUrl' => array('site/index'),
			// enable cookie-based authentication
			//'allowAutoLogin'=>true,
		),
		'actx' => array(
		    'class' => 'application.appcomponents.AppContext',
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
		),
		'bootstrap'=>array(
		    'class'=>'bootstrap.components.Bootstrap',
		),

		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__DIR__).'/data/testdrive.db',
		),
		'cache'=>array(
		    'class' => 'CFileCache',
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
			//'discardOutput' => false,
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'info,error, warning',
				),
				// uncomment the following to show log messages on web pages
				array(
					'class'=>'CWebLogRoute',
					'levels' => 'info',
				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);

// check if there is local config override file
if (file_exists(__DIR__. DIRECTORY_SEPARATOR. 'local.php')) {
    return array_replace_recursive($config, require __DIR__. DIRECTORY_SEPARATOR. 'local.php');
} else {
    return $config;
}
