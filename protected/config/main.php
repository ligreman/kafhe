<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Kafhe',

	// preloading 'log' component
	'preload'=>array(
		'log'
	),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'application.components.TXDB.*',
		'application.extensions.yiidebugtb.*', //Yii debug
		'application.modules.rights.*', 'application.modules.rights.components.*', //rights
		'ext.YiiMailer.YiiMailer',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'kafhe',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			//'generatorPaths' => array('bootstrap.gii'),
		),
		
		//Rights
		'rights'=>array( 
			'superuserName'=>'Administrador', // Name of the role with super user privileges. 
			'authenticatedName'=>'Usuario', // Name of the authenticated user role. 
			'userIdColumn'=>'id', // Name of the user id column in the database. 
			'userNameColumn'=>'username', // Name of the user name column in the database. 
			'enableBizRule'=>true, // Whether to enable authorization item business rules. 
			'enableBizRuleData'=>false, // Whether to enable data for business rules. 
			'displayDescription'=>true, // Whether to use item description instead of name. 
			'flashSuccessKey'=>'RightsSuccess', // Key to use for setting success flash messages. 
			'flashErrorKey'=>'RightsError', // Key to use for setting error flash messages.		
			'baseUrl'=>'/rights', // Base URL for Rights. Change if module is nested. 
			'layout'=>'rights.views.layouts.main', // Layout to use for displaying Rights. 
			'appLayout'=>'application.views.layouts.main', // Application layout. 
			//'cssFile'=>'', // Style sheet file to use for Rights.
			'install'=>false, // Whether to enable installer. 
			'debug'=>false,
		),
		
		//Hybrid Auth
		'hybridauth' => array(
            'baseUrl' => 'http://localhost/kafhe_3.0/trunk/index.php/hybridauth', 
            'withYiiUser' => false, // Set to true if using yii-user
            "providers" => array ( 
                "openid" => array (
                    "enabled" => true
                ),
 
                "yahoo" => array ( 
                    "enabled" => true 
                ),
 
                "google" => array ( 
                    "enabled" => true,
                    "keys"    => array ( "id" => "", "secret" => "" ),
                    "scope"   => ""
                ),
 
                "facebook" => array ( 
                    "enabled" => true,
                    "keys"    => array ( "id" => "", "secret" => "" ),
                    "scope"   => "email,publish_stream", 
                    "display" => "" 
                ),
 
                "twitter" => array ( 
                    "enabled" => true,
                    "keys"    => array ( "key" => "", "secret" => "" ) 
                )
            )
        ),
		
	),

	// application components
	'components'=>array(
		'Randomness'=>array('class'=>'Randomness'),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl'=>array('site/index'),
			'class'=>'RWebUser', //rights
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
	
		//Librerías/Componentes
		'SkillValidator'=>array('class'=>'SkillValidator'),
		
		//Singletons
		'event'=>array('class'=>'EventSingleton'),
		'usertools'=>array('class'=>'UserToolsSingleton'),
		'gungubos'=>array('class'=>'GungubosSingleton'),
		'tueste'=>array('class'=>'TuesteSingleton'),
		'skill'=>array('class'=>'SkillSingleton'),
		'config'=>array('class'=>'ConfigurationSingleton'),
        'mail'=>array('class'=>'MailSingleton'),

		
		/*'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=kafhe_refactor',
			'emulatePrepare' => true,
			'username' => 'kafhe',
			'password' => '',
			'charset' => 'utf8',
		),
		
		'request'=>array(
            'enableCsrfValidation'=>true,
        ),
		
		'authManager'=>array(
            //'class'=>'CDbAuthManager',
            //'connectionID'=>'db',
			'class'=>'RDbAuthManager', //rights
        ),
		
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'logPath'=>'logs',
					'logFile'=>date('Y-m-d').'-error.log',
					'levels'=>'error, warning',
				),
				array(
					'class'=>'CFileLogRoute',
					'logPath'=>'logs',
					'logFile'=>date('Y-m-d').'-trace.log',
					'levels'=>'profile, trace',
				),
				array(
					'class'=>'CFileLogRoute',
					'logPath'=>'logs',
					'logFile'=>date('Y-m-d').'-info.log',
					'levels'=>'info',
				),
				// uncomment the following to show log messages on web pages
				
				array(
					'class'=>'CWebLogRoute',
					'levels' =>'error, warning, profile, info' //trace
				),
				
				array( // configuration for the toolbar
		          'class'=>'XWebDebugRouter',
		          'config'=>'alignLeft, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
		          'levels'=>'error, warning, profile, info, trace', //trace
		          'allowedIPs'=>array('127.0.0.1','::1','192.168.1.54','192\.168\.1[0-5]\.[0-9]{3}'),
		        ),
				
			),
		),
	),
    
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName'] ó Yii::app()->params->paramName
	'params'=>array(		
		'adminEmail'=>'omelettus@gmail.com',
		'mailServerUsername'=>'omelettus@gmail.com',
		'mailServerPassword'=>'',
		'statusCriador'=>0,
		'statusCazador'=>1,
		'statusAlistado'=>2,
		'statusBaja'=>3,
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
	),
);