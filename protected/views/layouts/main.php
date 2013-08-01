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
        //Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.js'); //<- En teoría ya se está cargando jquery que viene integrado en Yii
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
            <?php if (!Yii::app()->user->isGuest): ?>
                <ul id="mainActionsList">
                    <li>
                    <?php //echo Yii::app()->user->name.' ';
                        $img = CHtml::image(Yii::app()->request->baseUrl.'/images/notificationsIcon.png','Notificaciones');
                        echo CHtml::link($img,array('#'),array('title' => 'Enséñame las notificaciones, Oh! Gran Omelettus!','id' => 'notificationsMainLink'));
                    ?>
                    </li>
                    <li>
                    <?php
                        $img = CHtml::image(Yii::app()->request->baseUrl.'/images/hideUserBlock.png','Abrir panel de usuario');
                        echo CHtml::link($img,array('#'), array('title' => 'No quiero ver el panel de usuario','id' => 'userpanelMainLink'));
                    ?>
                    </li>
                    <li>
                    <?php
                        $img = CHtml::image(Yii::app()->request->baseUrl.'/images/logoutIcon.png','Salir');
                        echo CHtml::link($img,array('/site/logout'), array('title' => 'Me voy pitando', 'id'=>'logoutMainLink'));
                    ?>
                    </li>
                </ul>
			<?php endif;?>
        </nav>

	</header><!-- header -->
    <section id="userPanel">
        <?php if (!Yii::app()->user->isGuest){
            $this->widget('application.components.UserPanel');
        }?>
    </section>

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
				array('label'=>'Alistamiento', 'url'=>array('/enrollment'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && isset(Yii::app()->event->model) && Yii::app()->event->type=='desayuno' && (Yii::app()->event->status==Yii::app()->params->statusPreparativos || Yii::app()->event->status==Yii::app()->params->statusBatalla))),
                array('label'=>'Batalla', 'url'=>array('/event'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && isset(Yii::app()->event->model) && Yii::app()->event->status!=Yii::app()->params->statusCerrado)),

				array('label'=>'Personaje', 'url'=>array('/character'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && !Yii::app()->user->checkAccess('Administrador'))),
                array('label'=>'Habilidades', 'url'=>array('/character/skills'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && !Yii::app()->user->checkAccess('Administrador'))),
				
				//Moderator
				array('label'=>'Iniciar batalla', 'url'=>array('/event/start'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && Yii::app()->user->checkAccess('lanzar_evento') && !Yii::app()->user->checkAccess('Administrador') && isset(Yii::app()->event->model) && Yii::app()->event->status==Yii::app()->params->statusPreparativos)),
				array('label'=>'Aceptar derrota', 'url'=>array('/event/finish'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && !Yii::app()->user->checkAccess('Administrador') && isset(Yii::app()->event->model) && Yii::app()->event->status==Yii::app()->params->statusBatalla && isset(Yii::app()->event->caller) && Yii::app()->event->caller==Yii::app()->user->id)),

				//Admin pages
                array('label'=>'Roles y permisos', 'url'=>array('/rights'), 'visible'=>Yii::app()->user->checkAccess('Administrador')),
                array('label'=>'Usuarios', 'url'=>array('/user'), 'visible'=>Yii::app()->user->checkAccess('Administrador')),
                array('label'=>'Administration', 'url'=>array('/admin/index'), 'visible'=>Yii::app()->user->checkAccess('Administrador')),


				//array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				//array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		));

        if(Yii::app()->user->checkAccess('Usuario') && isset(Yii::app()->event->model) && (Yii::app()->event->status==Yii::app()->params->statusPreparativos || Yii::app()->event->status==Yii::app()->params->statusBatalla) && Yii::app()->event->type=='desayuno'){
            echo CHtml::ajaxLink('Alistamiento (ajax)', CController::createUrl('enrollment/index'), array('update'=>'#submenuBlock'));
        }

	  ?>
	</nav><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

    <section id="submenuBlock">
    </section>
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
