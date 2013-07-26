<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

    <?php
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.js');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/main.js');
    ?>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

</head>

<body>

<div id="page">

	<header>
		<div>
            <h1 id="logo"><?php
                $img = CHtml::image(Yii::app()->request->baseUrl.'/images/kafhe3.png','kafhe');
                echo CHtml::link($img,array('site/index'));
                ?>
            </h1>
        </div>
        <nav>
            <?php
                //echo Yii::app()->user->name.' ';
                if (!Yii::app()->user->isGuest) echo CHtml::link('Logout',array('site/logout'));
            ?>
        </nav>

	</header><!-- header -->

	<nav id="secondary_nav">
	  <?php

	    //Si soy Admin tendré opciones nuevas en el menú
		$this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				//array('label'=>'Home', 'url'=>array('/site/index')), //, 'active'=>true),
				//array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				//array('label'=>'Contact', 'url'=>array('/site/contact')),
                array('label'=>'Prueba', 'url'=>array('/site/prueba')),
				//array('label'=>'Alistamiento', 'url'=>array('/site/alistamiento'), 'visible'=>Event::model()->exists('group_id=:groupId AND open=1', array(':groupId'=>Yii::app()->user->group_id)) ),
				array('label'=>'Alistamiento', 'url'=>array('/enrollment'), 'visible'=>(Yii::app()->user->checkAccess('Authenticated') && isset(Yii::app()->event->model) && Yii::app()->event->model->status==1 && Yii::app()->event->model->type=='desayuno')),

				array('label'=>'Personaje', 'url'=>array('/character'), 'visible'=>(Yii::app()->user->checkAccess('Authenticated') && !Yii::app()->user->checkAccess('Admin'))),
                array('label'=>'Habilidades', 'url'=>array('/character/skills'), 'visible'=>(Yii::app()->user->checkAccess('Authenticated') && !Yii::app()->user->checkAccess('Admin'))),

				//Admin pages
                array('label'=>'Roles y permisos', 'url'=>array('/rights'), 'visible'=>Yii::app()->user->checkAccess('Admin')),
                array('label'=>'Usuarios', 'url'=>array('/user'), 'visible'=>Yii::app()->user->checkAccess('Admin')),
                array('label'=>'Administration', 'url'=>array('/admin/index'), 'visible'=>Yii::app()->user->checkAccess('Admin')),


				//array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				//array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		));
	  ?>
	</nav><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

    <section id="main">
	    <?php echo $content; ?>
    </section>

	<div class="clear"></div>

	<footer>
		<ul>
            <li><a href="<?php echo Yii::app()->request->baseUrl.'/wiki'?>">kafhe wiki</a></li>
		</ul>
	</footer>

</div><!-- page -->

</body>
</html>
