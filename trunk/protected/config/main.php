<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Kafhe',
    'language'=>'es',

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

        //#iniGii
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'kafhe',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			'generatorPaths' => array('bootstrap.gii'),
		),
		//#finGii
		
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
			'appLayout'=>'webroot.themes.bootstrap.views.layouts.main', // Application layout.
			//'cssFile'=>'', // Style sheet file to use for Rights.
			'install'=>false, // Whether to enable installer. 
			'debug'=>false,
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
		'modifier'=>array('class'=>'ModifierSingleton'),
        'currentUser'=>array('class'=>'UserSingleton'),
		'gungubos'=>array('class'=>'GungubosSingleton'),
		'tueste'=>array('class'=>'TuesteSingleton'),
		'skill'=>array('class'=>'SkillSingleton'),
		'config'=>array('class'=>'ConfigurationSingleton'),
        'mail'=>array('class'=>'MailSingleton'),


        'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),

		/*'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database

		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=kafhe_refactor',
			'emulatePrepare' => true,
			'username' => 'kafhe',//#mysqlUsername
			'password' => '',//#mysqlPassword
			'charset' => 'utf8',
		),
		
		'request'=>array(
            'enableCsrfValidation'=>true,
        ),
		
		'authManager'=>array(
            //'class'=>'CDbAuthManager',
            //'connectionID'=>'db',
			'class'=>'RDbAuthManager', //rights
			'assignmentTable'=>'authassignment',
            'itemTable'=>'authitem',
            'rightsTable'=>'rights',
            'itemChildTable'=>'authitemchild',
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
				//#iniLog
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
		        //#finLog
				
			),
		),
	),
    
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName'] ó Yii::app()->params->paramName
	'params'=>array(
	    'appVersion'=>'v1.1',

	    //Servidor de correo
		'adminEmail'=>'omelettus@gmail.com',//#mailEmail
		'mailServerUsername'=>'',//#mailUsername
		'mailServerPassword'=>'',//#mailPassword
		
		//Estados de Usuario
		'statusCriador'=>0,
		'statusCazador'=>1,
		'statusAlistado'=>2,
		'statusBaja'=>3,
		'statusIluminado'=>4,
		'statusLibertador'=>5,
		
		//Estados de Eventos
		'statusCerrado'=>0,
		'statusIniciado'=>1,
		'statusBatalla'=>2,
		'statusFinalizado'=>3,
		'statusCalma'=>4,
		
		//Modificadores. Están aquí y en BBDD
		'modifierHidratado'=>'hidratado',
		'modifierDisimulando'=>'disimulando',
        'modifierImpersonando'=>'impersonando',
		'modifierDesecado'=>'desecado',
        'modifierTrampa'=>'trampa',
        'modifierProtegiendo'=>'protegiendo',
        'modifierOteando'=>'oteando',
		
		//Habilidades. Están aquí y en BBDD
		'skillHidratar'=>'hidratar',
        'skillDesecar'=>'desecar',
        'skillDisimular'=>'disimular',
        'skillImpersonar'=>'impersonar',
		'skillCazarGungubos'=>'cazarGungubos',
		'skillEscaquearse'=>'escaquearse',
		'skillRescatarGungubos'=>'rescatarGungubos',
		'skillVendetta'=>'vendetta',
		'skillTrampa'=>'trampa',
        'skillLiberarGungubos'=>'liberarGungubos',
        'skillAtraerGungubos'=>'atraerGungubos',
        'skillProtegerGungubos'=>'protegerGungubos',
        'skillOtear'=>'otear',

		//Otros
		'sideNames'=>array('kafhe'=>'Kafhe', 'achikhoria'=>'Achikhoria', 'libre'=>'Iluminado'),
        'userStatusNames'=>array(0=>'Criador', 1=>'Cazador', 2=>'Alistado', 3=>'Baja', 4=>'Iluminado', 5=>'Libertador'),
        'eventStatusNames'=>array(0=>'Cerrada', 1=>'Gungubos', 2=>'Batalla', 3=>'Finalizada'),
	),
);