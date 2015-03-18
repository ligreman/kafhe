<?php

  //Prueba de edición

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/test.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

//Requires
require_once($yii);
Yii::createWebApplication($config)->run();
