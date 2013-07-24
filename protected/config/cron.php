<?php
// ¿Needed?

return array(
    // This path may be different. You can probably get it from `config/main.php`.
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Cron Kafhe',
 
    'preload'=>array('log'),
 
    'import'=>array(
        'application.models.*',
		'application.components.*',
        'application.components.TXDB.*',
    ),
    // We'll log cron messages to the separate files
    'components'=>array(
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'cron.log',
                    'levels'=>'error, warning',
                ),
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'cron_trace.log',
                    'levels'=>'trace',
                ),
            ),
        ),
		
		//Librerías/Componentes
		//'SkillValidator'=>array('class'=>'SkillValidator'),
		
		//Singletons
		//'event'=>array('class'=>'EventSingleton'),
		//'usertools'=>array('class'=>'UserToolsSingleton'),
		'tueste'=>array('class'=>'TuesteSingleton'),		
		'config'=>array('class'=>'ConfigurationSingleton'),
 
        // Your DB connection
        'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=kafhe_refactor',
			'emulatePrepare' => true,
			'username' => 'kafhe',
			'password' => '',
			'charset' => 'utf8',
		),
    ),
	
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(		
		//'tiempoRegeneracionTueste'=>600, //Segundos
		//'tuesteRegeneradoHora'=>100, //Puntos a la hora
	),
);