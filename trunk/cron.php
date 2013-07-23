<?php
/*
	In your crontab, execute yiic dentro de protected: yiic <command-name> <action-name> --option1=value1 --option2=value2 ...
	
	Ejemplos:
	yiic test index --param1=News --param2=5 --param3=valorArray1 --param3=valorArray2 --param3=valorArray3

	// $param2 takes default value
	yiic test index --param1=News --param3=valorArray1
	
	//parametros globales
	tiic test index --global_param=8

*/

defined('YII_DEBUG') or define('YII_DEBUG',true);

// change the following paths if necessary.
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/cron.php';
 
// including Yii
require_once($yii);
 
// creating and running console application
Yii::createConsoleApplication($configFile)->run();
