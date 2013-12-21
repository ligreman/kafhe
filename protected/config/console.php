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
                    //'logPath'=>'logs',
                    'logFile'=>date('Y-m-d').'-console-error.log',
                    'levels'=>'error, warning',
                ),
                /*array(
                    'class'=>'CFileLogRoute',
                    //'logPath'=>'logs',
                    'logFile'=>date('Y-m-d').'-console-trace.log',
                    'levels'=>'profile, trace',
                ),*/
                array(
                    'class'=>'CFileLogRoute',
                    //'logPath'=>'logs',
                    'logFile'=>date('Y-m-d').'-console-info.log',
                    'levels'=>'info',
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
        'gumbudos'=>array('class'=>'GumbudosSingleton'),
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
        'statusInactivo'=>0,
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
        'statusPreparativos'=>5,

        //Modificadores. Están aquí y en BBDD
        'modifierHidratado'=>'hidratado',
        'modifierDisimulando'=>'disimulando',
        'modifierImpersonando'=>'impersonando',
        'modifierDesecado'=>'desecado',
        'modifierTrampaPifia'=>'trampaPifia',
        'modifierTrampaTueste'=>'trampaTueste',
        'modifierProtegiendo'=>'protegiendo',
        'modifierOteando'=>'oteando',
        'modifierSenuelo'=>'senuelo',

        //Habilidades. Están aquí y en BBDD
        'skillHidratar'=>'hidratar',
        'skillDesecar'=>'desecar',
        'skillDisimular'=>'disimular',
        'skillImpersonar'=>'impersonar',
        'skillCazarGungubos'=>'cazarGungubos',
        'skillEscaquearse'=>'escaquearse',
        'skillRescatarGungubos'=>'rescatarGungubos',
        'skillVendetta'=>'vendetta',
        'skillTrampaTueste'=>'trampaTueste',
        'skillTrampaPifia'=>'trampaPifia',
        'skillLiberarGungubos'=>'liberarGungubos',
        'skillAtraerGungubos'=>'atraerGungubos',
        'skillProtegerGungubos'=>'protegerGungubos',
        'skillOtear'=>'otear',
        'skillSenuelo'=>'senuelo',

        'skillOtearKafhe'=>'otearKafhe',
        'skillOtearAchikhoria'=>'otearAchikhoria',

        'skillGumbudoAsaltante'=>'gumbudoAsaltante',
        'skillGumbudoGuardian'=>'gumbudoGuardian',
        'skillGumbudoCriador'=>'gumbudoCriador',
        'skillGumbudoNigromante'=>'gumbudoNigromante',
        'skillGumbudoArtificiero'=>'gumbudoArtificiero',

        //Gumbudos
        'gumbudoClassAsaltante'=>'asaltante',
        'gumbudoClassGuardian'=>'guardian',
        'gumbudoClassCriador'=>'criador',
        'gumbudoClassNigromante'=>'nigromante',
        'gumbudoClassArtificiero'=>'artificiero',

        //Gungubo
        'gunguboClassZombie'=>'zombie',
        'gunguboClassBomba'=>'bomba',

        //Armas
        'gumbudoWeapon1'=>'garras',
        'gumbudoWeapon2'=>'colmillos',
        'gumbudoWeapon3'=>'puas',

        'traitAcorazado'=>'acorazado',
        'traitSanguinario'=>'sanguinario',
        'traitConsumeCadaveres'=>'consumeCadaveres',
        'traitCanibal'=>'canibal',
        'traitIncendiar'=>'incendiar',
        'traitColera'=>'colera',
        'traitZombificar'=>'zombificar',

        'conditionQuemadura'=>'quemadura',


        //Otros
        'sideNames'=>array('kafhe'=>'Kafhe', 'achikhoria'=>'Achikhoria', 'libre'=>'Iluminado'),
        'userStatusNames'=>array(0=>'Inactivo', 1=>'Alborotador', 2=>'Combatiente', 3=>'Baja', 4=>'Espectador', 5=>'Libertador'),
        'eventStatusNames'=>array(0=>'Cerrada', 1=>'Gungubos', 2=>'Batalla', 3=>'Finalizada', 4=>'En calma', 5=>'Preparativos'),

        'gumbudoClassNames'=>array('asaltante'=>'Asaltante', 'guardian'=>'Guardián', 'criador'=>'Criador'),
        'gumbudoClassNamesPlural'=>array('asaltante'=>'Asaltantes', 'guardian'=>'Guardianes', 'criador'=>'Criadores'),
        'gumbudoWeaponNames'=>array('garras'=>'Garras', 'colmillos'=>'Colmillos', 'puas'=>'Púas'),

        'traitNames'=>array('acorazado'=>'Acorazado', 'sanguinario'=>'Sanguinario', 'consumeCadaveres'=>'Consume cadáveres', 'canibal'=>'Caníbal', 'incendiar'=>'Incendiar', 'colera'=>'Cólera', 'zombificar'=>'Zombificar'),
        'conditionNames'=>array('quemadura'=>'Quemadura'),
	),
);