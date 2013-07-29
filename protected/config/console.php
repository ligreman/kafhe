<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Kafhe Console Application',

	'import'=>array(
		'application.models.*',	
	    'application.components.*',
        'application.components.TXDB.*',
	),

	// preloading 'log' component
	'preload'=>array('log'),
	
	// application components
	'components'=>array(		
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=kafhe_refactor',
			'emulatePrepare' => true,
			'username' => 'kafhe',
			'password' => '',
			'charset' => 'utf8',
		),
		
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
		
				
		//Librerías/Componentes
		//'SkillValidator'=>array('class'=>'SkillValidator'),
		
		//Singletons
		//'event'=>array('class'=>'EventSingleton'),
		'usertools'=>array('class'=>'UserToolsSingleton'),
		'tueste'=>array('class'=>'TuesteSingleton'),		
		'config'=>array('class'=>'ConfigurationSingleton'),
	),
);