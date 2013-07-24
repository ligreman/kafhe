<?php
// ¿Needed?

defined('YII_DEBUG') or define('YII_DEBUG',true);

// change the following paths if necessary.
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/cron.php';
 
// including Yii
require_once($yii);
 
// creating and running console application
Yii::createConsoleApplication($configFile)->run();
