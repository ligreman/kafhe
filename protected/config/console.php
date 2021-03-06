<?php

date_default_timezone_set('Europe/Madrid');

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Kafhe Console Application',

	'import'=>array(
		'application.models.*',	
	    'application.components.*',
        'application.components.TXDB.*',
        'ext.YiiMailer.YiiMailer',
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
            //#iniLog
            'enableParamLogging'=>true, //debug
            //#finLog
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
                //#iniLog
                array(
                    'class'=>'CFileLogRoute',
                    //'logPath'=>'logs',
                    'logFile'=>date('Y-m-d').'-console-info.log',
                    'levels'=>'info',
                ),

                array( //debug
                    'class'=>'CFileLogRoute',
                    'levels'=>'trace,log',
                    'categories' => 'system.db.CDbCommand',
                    'logFile' => 'db.log',
                ),
                //#finLog
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
        'reward'=>array('class'=>'RewardSingleton'),
        'mail'=>array('class'=>'MailSingleton'),
        'utils'=>array('class'=>'UtilsSingleton'),
	),
	
	'params'=>array(
        'adminEmail'=>'omelettus@gmail.com',//#mailEmail
        'mailServerUsername'=>'',//#mailUsername
        'mailServerPassword'=>'',//#mailPassword

        //Estados de Usuario
        //'statusInactivo'=>0,
        'statusCazador'=>1,
        'statusAlistado'=>2,
        //'statusBaja'=>3,
        'statusIluminado'=>4,
        'statusLibertador'=>5,

        //Estados de Eventos
        'statusCerrado'=>0,
        'statusIniciado'=>1,
        'statusBatalla'=>2,
        'statusFinalizado'=>3,
        'statusCalma'=>4,
        'statusPreparativos'=>5,

        //Recompensas
        'rwMoreCritic' => 'rwMoreCritic',
        'rwLessFail' => 'rwLessFail',
        'rwMinTueste' => 'rwMinTueste',
        'rwMoreRegen' => 'rwMoreRegen',

        //Modificadores. Están aquí y en BBDD
        'modifierHidratado'=>'hidratado',
        'modifierDisimulando'=>'disimulando',
        'modifierImpersonando'=>'impersonando',
        'modifierDesecado'=>'desecado',
        'modifierTrampaPifia'=>'trampaPifia',
        'modifierTrampaTueste'=>'trampaTueste',
        'modifierTrampaConfusion'=>'trampaConfusion',
        //'modifierProtegiendo'=>'protegiendo',
        //'modifierOteando'=>'oteando',
        'modifierSenuelo'=>'senuelo',

        //Habilidades. Están aquí y en BBDD
        'skillHidratar'=>'hidratar',
        //'skillDesecar'=>'desecar',
        //'skillDisimular'=>'disimular',
        //'skillImpersonar'=>'impersonar',
        //'skillCazarGungubos'=>'cazarGungubos',
        'skillEscaquearse'=>'escaquearse',
        //'skillRescatarGungubos'=>'rescatarGungubos',
        //'skillVendetta'=>'vendetta',
        'skillTrampaTueste'=>'trampaTueste',
        'skillTrampaPifia'=>'trampaPifia',
        'skillTrampaConfusion'=>'trampaConfusion',
        //'skillLiberarGungubos'=>'liberarGungubos',
        //'skillAtraerGungubos'=>'atraerGungubos',
        //'skillProtegerGungubos'=>'protegerGungubos',
        //'skillOtear'=>'otear',
        'skillSenuelo'=>'senuelo',
        'skillSacrificar'=>'sacrificar',
        'skillVampirismo'=>'vampirismo',
        'skillOtearKafhe'=>'otearKafhe',
        'skillOtearAchikhoria'=>'otearAchikhoria',
        'skillDifamar'=>'difamar',
        'skillPoderPrimigenio'=>'poderPrimigenio',
        'skillConversionDivina'=>'conversionDivina',
        'skillApocalipsisZombie'=>'apocalipsisZombie',

        'skillGumbudoAsaltante'=>'gumbudoAsaltante',
        'skillGumbudoGuardian'=>'gumbudoGuardian',
        'skillGumbudoCriador'=>'gumbudoCriador',
        'skillGumbudoNigromante'=>'gumbudoNigromante',
        'skillGumbudoArtificiero'=>'gumbudoArtificiero',
        'skillGumbudoHippie' => 'gumbudoHippie',
        'skillGumbudoPestilente' => 'gumbudoPestilente',
        'skillGumbudoAsedio' => 'gumbudoAsedio',


        //Gumbudos
        'gumbudoClassDefault'=>'gumbudo',
        'gumbudoClassAsaltante'=>'asaltante',
        'gumbudoClassGuardian'=>'guardian',
        'gumbudoClassCriador'=>'criador',
        'gumbudoClassNigromante'=>'nigromante',
        'gumbudoClassArtificiero'=>'artificiero',
        'gumbudoClassHippie'=>'hippie',
        'gumbudoClassPestilente'=>'pestilente',
        'gumbudoClassAsedio'=>'asedio',

        //Gungubo
        'gunguboClassDefault'=>'gungubo',
        'gunguboClassZombie'=>'zombie',
        'gunguboClassBomba'=>'bomba',
        'gunguboClassMolotov'=>'molotov',

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
        'traitHiperactivo'=>'hiperactivo',
        'traitFetido'=>'fetido',

        'conditionNormal'=>'normal',
        'conditionQuemadura'=>'quemadura',
        'conditionEnfermedad'=>'enfermedad',


        //Otros
        'sideNames'=>array('kafhe'=>'Kafhe', 'achikhoria'=>'Achikhoria', 'libre'=>'Têh'),
        'userStatusNames'=>array(0=>'Inactivo', 1=>'Alborotador', 2=>'Combatiente', 3=>'Baja', 4=>'Espectador', 5=>'Libertador'),
        'eventStatusNames'=>array(0=>'Cerrada', 1=>'Fama', 2=>'Batalla', 3=>'Finalizada', 4=>'En calma', 5=>'Preparativos'),

        'gumbudoClassNames'=>array('asaltante'=>'Asaltante', 'guardian'=>'Guardián', 'criador'=>'Criador', 'nigromante'=>'Nigromante', 'artificiero'=>'Artificiero', 'hippie'=>'Hippie', 'pestilente'=>'Pestilente', 'asedio'=>'de Asedio'),
        'gumbudoClassNamesPlural'=>array('asaltante'=>'Asaltantes', 'guardian'=>'Guardianes', 'criador'=>'Criadores', 'nigromante'=>'Nigromantes', 'artificiero'=>'Artificieros', 'hippie'=>'Hippies', 'pestilente'=>'Pestilentes', 'asedio'=>'de Asedio'),
        'gumbudoWeaponNames'=>array('garras'=>'Garras', 'colmillos'=>'Colmillos', 'puas'=>'Púas'),

        'traitNames'=>array('acorazado'=>'Acorazado', 'sanguinario'=>'Sanguinario', 'consumeCadaveres'=>'Consume cadáveres', 'canibal'=>'Caníbal', 'incendiar'=>'Incendiar', 'colera'=>'Cólera', 'zombificar'=>'Zombificar', 'hiperactivo'=>'Hiperactivo'),
        //'conditionNames'=>array('quemadura'=>'Quemadura'),
        'trampaNames'=>array('trampaTueste'=>'Trampa de Tueste', 'trampaConfusion'=>'Trampa de Confusión', 'trampaPifia'=>'Trampa de Pifia'),

        'modifierNames'=>array('hidratado'=>'Hidratado', 'disimulando'=>'Disimulando', 'impersonando'=>'Impersonando', 'desecado'=>'Desecado', 'trampaPifia'=>'Trampa de pifia', 'trampaTueste'=>'Trampa de tueste', 'trampaConfusion'=>'Trampa de confusión', 'senuelo'=>'Marcado con señuelo', 'rwMoreCritic'=>'Recompensa de más crítico', 'rwLessFail'=>'Recompensa de menos pifia', 'rwMinTueste'=>'Recompensa de mínimo de tueste', 'rwMoreRegen'=>'Recompensa de mayor regeneración de tueste'),
	),
);