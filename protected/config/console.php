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
		'gungubos'=>array('class'=>'GungubosSingleton'),
		'tueste'=>array('class'=>'TuesteSingleton'),		
		'config'=>array('class'=>'ConfigurationSingleton'),
	),
	
	'params'=>array(
        'adminEmail'=>'omelettus@gmail.com',		
		
		//Estados de Usuario
		'statusCriador'=>0,
		'statusCazador'=>1,
		'statusAlistado'=>2,
		'statusBaja'=>3,
		'statusDesertor'=>4,
		'statusLibre'=>5,
		
		//Estados de Eventos
		'statusCerrado'=>0,
		'statusIniciado'=>1,
		'statusBatalla'=>2,
		'statusFinalizado'=>3,
		
		//Modificadores. Están aquí y en BBDD
		'modifierHidratado'=>'hidratado',
		'modifierDisimulando'=>'disimulando',
		
		//Habilidades. Están aquí y en BBDD
		'skillHidratar'=>'hidratar',
		'skillDisimular'=>'disimular',
		'skillCazarGungubos'=>'cazarGungubos',
		'skillEscaquearse'=>'escaquearse',
		'skillGungubicidio'=>'gungubicidio',
		'skillVendetta'=>'vendetta',

		//Otros
		'sideNames'=>array('kafhe'=>'Kafhe', 'achikhoria'=>'Achikhoria', 'libre'=>'Renegados'),
	),
);