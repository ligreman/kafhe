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
			'username' => 'kafhe',//#mysqlUsername
			'password' => '',//#mysqlPassword
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
		'event'=>array('class'=>'EventSingleton'),
		'usertools'=>array('class'=>'UserToolsSingleton'),
		'modifier'=>array('class'=>'ModifierSingleton'),
		'gungubos'=>array('class'=>'GungubosSingleton'),
		'tueste'=>array('class'=>'TuesteSingleton'),		
		'config'=>array('class'=>'ConfigurationSingleton'),
        'skill'=>array('class'=>'SkillSingleton'),
        //'mail'=>array('class'=>'MailSingleton'),
	),
	
	'params'=>array(
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
		
		//Modificadores. Están aquí y en BBDD
		'modifierHidratado'=>'hidratado',
		'modifierDisimulando'=>'disimulando',
        'modifierImpersonando'=>'impersonando',
		'modifierDesecado'=>'desecado',
        'modifierTrampa'=>'trampa',
        'modifierProtegiendo'=>'protegiendo',
		
		//Habilidades. Están aquí y en BBDD
		'skillHidratar'=>'hidratar', //Asocia el parámetro skillHidratar con el keyword de la habilidad
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

		//Otros
		'sideNames'=>array('kafhe'=>'Kafhe', 'achikhoria'=>'Achikhoria', 'libre'=>'Iluminado'),
        'userStatusNames'=>array(0=>'Criador', 1=>'Cazador', 2=>'Alistado', 3=>'Baja', 4=>'Iluminado', 5=>'Libertador'),
        'eventStatusNames'=>array(0=>'Cerrado', 1=>'Iniciado', 2=>'Batalla', 3=>'Finalizado'),
	),
);