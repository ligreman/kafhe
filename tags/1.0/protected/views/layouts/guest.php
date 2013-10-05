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
                    <?php
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

    <section id="guest">
        <div id="vResponsiveContent">
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
        </div>
    </section>

	<div class="clear"></div>

	<footer>
		<ul>
            <li><a target="_blank" href="http://wiki.kafhe.com">Kafhe wiki</a></li>
            <li><a target="_blank" href="http://kafhe.hol.es/wiki/index.php/Normas_de_comportamiento">Normas de comportamiento</a></li>
            <li><a href="<?php echo Yii::app()->request->baseUrl.'/site/page?view=about';?>">Acerca de</a></li>
		</ul>
	</footer>

</div><!-- page -->

</body>
</html>
