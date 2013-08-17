<?php
    $userPanelHidden = isset(Yii::app()->request->cookies['userPanelHidden']) ? Yii::app()->request->cookies['userPanelHidden']->value : '1';
?>
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
		Yii::app()->clientScript->registerCoreScript('jquery'); //JQuery viene con Yii, simplemente lo cojo del Core para no duplicarlo
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.cookie.js');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/main.js');
    ?>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

</head>

<body>

<div id="page">

	<header>
		<div>
            <h1 id="logo"><?php
                $img = CHtml::image(Yii::app()->request->baseUrl.'/images/kafhe3.png','kafhe');
                echo CHtml::link($img,Yii::app()->homeUrl);
                ?>
            </h1>
        </div>
        <nav>
            <?php if (!Yii::app()->user->isGuest): ?>
                <ul id="mainActionsList">
                    <li>
                    <?php
                        $img = CHtml::image(Yii::app()->request->baseUrl.'/images/notificationsIcon.png','Notificaciones');
                        echo CHtml::link($img,array('#'),array('title' => 'Enséñame las notificaciones, Oh! Gran Omelettus!','id' => 'notificationsMainLink'));
                    ?>
                    </li>
                    <li>
                    <?php
                        if($userPanelHidden=="1"){
                            $img = CHtml::image(Yii::app()->request->baseUrl.'/images/showUserBlock.png','Quiero ver el panel de usuario');
                        }else{
                            $img = CHtml::image(Yii::app()->request->baseUrl.'/images/hideUserBlock.png','No quiero ver el panel de usuario');
                        }

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

            <?php
            if(Yii::app()->user->checkAccess('Usuario')&&!Yii::app()->user->checkAccess('Administrador')){
                $battle = Yii::app()->event->model;
                $totalGungubos = $battle->gungubos_kafhe + $battle->gungubos_achikhoria;
                if ($totalGungubos == 0) $pkafhe = 50;
                else
                    $pkafhe = floor(($battle->gungubos_kafhe/$totalGungubos)*100);

                if($pkafhe > 0) $side = 'Kafhe';
                else $side = 'Achikhoria';

            ?>
        </nav>
        <div id="battleStatus">
                <span id="batteStatus<?php echo $side; ?>" class="w<?php echo $pkafhe;?>">
                    <span class="<?php echo Yii::app()->currentUser->side;?>pin">
                            <span class="title battleTitle"><?php
                                echo CHtml::image(Yii::app()->baseUrl."/images/modifiers/kafhe.png",'Kafhe',array('height' => 16, 'class' => 'scoreSide'));
                                ?><span class="score"><?php
                                    echo $battle->gungubos_kafhe;
                                    echo ' - ';
                                    echo $battle->gungubos_achikhoria;
                                ?></span><?php
                                echo CHtml::image(Yii::app()->baseUrl."/images/modifiers/achikhoria.png",'Achikhoria',array('height' => 16, 'class' => 'scoreSide'));
                                ?>
                                <span class="flecha-down"></span>
                            </span>

                    </span>
                </span>
        </div>
        <?php
            }
        ?>
	</header><!-- header -->
    <section id="userPanel" <?php if($userPanelHidden=="1") echo 'style="display:none;"'; ?>>
        <?php if (!Yii::app()->user->isGuest && !Yii::app()->user->checkAccess('Administrador')){
            $this->widget('application.components.UserPanel');
        }?>
    </section>

	<nav id="secondary_nav">
	  <?php

	    //Si soy Admin tendré opciones nuevas en el menú
		$this->widget('zii.widgets.CMenu',array(
			'items'=>array(
			    //Menú principal
				array('label'=>'Notificaciones', 'url'=>array('/site/index'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && !Yii::app()->user->checkAccess('Administrador')), 'active'=>$this->id=='site'?true:false),
				array('label'=>'Alistamiento', 'url'=>array('/enrollment'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && isset(Yii::app()->event->model) && Yii::app()->event->type=='desayuno' && (Yii::app()->event->status==Yii::app()->params->statusIniciado || Yii::app()->event->status==Yii::app()->params->statusBatalla)), 'active'=>$this->id=='enrollment'?true:false),
                array('label'=>'Batalla', 'url'=>array('/event'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && isset(Yii::app()->event->model) && Yii::app()->event->status!=Yii::app()->params->statusCerrado), 'active'=>($this->id=='event' && $this->action->id=='index')?true:false),
                array('label'=>'Histórico de batallas', 'url'=>array('/history'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && !Yii::app()->user->checkAccess('Administrador')), 'active'=>$this->id=='history'?true:false),

				//Enlaces de batalla
				array('label'=>'Iniciar batalla', 'url'=>array('/event/start'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && Yii::app()->user->checkAccess('lanzar_evento') && !Yii::app()->user->checkAccess('Administrador') && isset(Yii::app()->event->model) && Yii::app()->event->status==Yii::app()->params->statusIniciado)),
				array('label'=>'Aceptar derrota', 'url'=>array('/event/finish'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && !Yii::app()->user->checkAccess('Administrador') && isset(Yii::app()->event->model) && Yii::app()->event->status==Yii::app()->params->statusBatalla && isset(Yii::app()->event->callerId) && Yii::app()->event->callerId==Yii::app()->user->id)),
                array('label'=>'Pedido', 'url'=>array('/event/finish'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && !Yii::app()->user->checkAccess('Administrador') && isset(Yii::app()->event->model) && Yii::app()->event->status==Yii::app()->params->statusFinalizado && isset(Yii::app()->event->callerId) && Yii::app()->event->callerId==Yii::app()->user->id), 'active'=>($this->id=='event' && $this->action->id=='finish')?true:false),
                array('label'=>'Ya he llamado', 'url'=>array('/event/close'), 'visible'=>(Yii::app()->user->checkAccess('Usuario') && !Yii::app()->user->checkAccess('Administrador') && isset(Yii::app()->event->model) && Yii::app()->event->status==Yii::app()->params->statusFinalizado && isset(Yii::app()->event->callerId) && Yii::app()->event->callerId==Yii::app()->user->id)),

				//Admin pages
                array('label'=>'Administración', 'url'=>array('/admin/index'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>($this->id=='admin' && $this->action->id=='index')?true:false),
                array('label'=>'Roles y permisos', 'url'=>array('/rights'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='rights'?true:false),
                array('label'=>'Usuarios', 'url'=>array('/user'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='user'?true:false),

			),
		));

        /*if(Yii::app()->user->checkAccess('Usuario') && isset(Yii::app()->event->model) && (Yii::app()->event->status==Yii::app()->params->statusIniciado || Yii::app()->event->status==Yii::app()->params->statusBatalla) && Yii::app()->event->type=='desayuno'){
            echo CHtml::ajaxLink('Alistamiento (ajax)', CController::createUrl('enrollment/index'), array('update'=>'#submenuBlock'));
        }*/

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
        <?php
        $flashMessages = Yii::app()->user->getFlashes();
        if ($flashMessages) {
            echo '<ul class="flashes">';
            foreach($flashMessages as $key => $message) {
                echo '<li><div class="flash-' . $key . '">' . $message . "</div></li>\n";
            }
            echo '</ul>';
        }
        ?>
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
