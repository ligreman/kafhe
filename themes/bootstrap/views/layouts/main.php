<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico">

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />

	<title>El rincón de Omelettus</title>

	<?php Yii::app()->bootstrap->register(); ?>
</head>

<body>

<?php $this->widget('bootstrap.widgets.TbNavbar', array(
    'type'=>'inverse', // null or 'inverse'
    'brand'=>'Omelettus',
    'collapse'=>true, // requires bootstrap-responsive.css
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'Notificaciones', 'url'=>array('/administration/notification'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/notification'?true:false),
                array('label'=>'Usuarios', 'url'=>'#', 'items'=>array(
                    array('label'=>'CUENTAS'),
                    array('label'=>'Grupos', 'url'=>array('/administration/group'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/group'?true:false),
                    array('label'=>'Usuarios', 'url'=>array('/administration/user'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/user'?true:false),
                    //rank
                    '---',
                    array('label'=>'AUTORIZACIÓN'),
                    array('label'=>'Roles y permisos', 'url'=>array('/rights'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='rights'?true:false),
                )),
                array('label'=>'Eventos', 'url'=>'#', 'items'=>array(
                    array('label'=>'BATALLAS'),
                    array('label'=>'Eventos', 'url'=>array('/administration/event'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/event'?true:false),
                    array('label'=>'Alistamiento', 'url'=>array('/administration/enrollment'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/enrollment'?true:false),
                    array('label'=>'Ranking', 'url'=>array('/administration/ranking'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/ranking'?true:false),
                    '---',
                    array('label'=>'PEDIDOS'),
                    array('label'=>'Comidas', 'url'=>array('/administration/meal'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/meal'?true:false),
                    array('label'=>'Bebidas', 'url'=>array('/administration/drink'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/drink'?true:false),
                    //'---',
                    //array('label'=>'NAV HEADER'),
                )),
                array('label'=>'Habilidades', 'url'=>'#', 'items'=>array(
                    array('label'=>'DE USUARIO'),
                    array('label'=>'Habilidades', 'url'=>array('/administration/skill'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/skill'?true:false),
                    array('label'=>'Modificadores', 'url'=>array('/administration/modifier'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/modifier'?true:false),
                )),
                array('label'=>'Servidor', 'url'=>'#', 'items'=>array(
                    array('label'=>'SERVIDOR'),
                    array('label'=>'Configuración', 'url'=>array('/administration/configuration'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/configuration'?true:false),
                    array('label'=>'Pila cron', 'url'=>array('/administration/cronpile'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/cronpile'?true:false),
                    array('label'=>'Logs', 'url'=>array('/administration/logs'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/logs'?true:false),
                    '---',
                    array('label'=>'HISTÓRICOS'),
                    array('label'=>'Ejecución de habilidades', 'url'=>array('/administration/historySkillExecution'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='administration/historySkillExecution'?true:false),
                )),

            ),
        ),
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right'),
            'items'=>array(
                array('label'=>'Salir', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
            ),
        ),
    ),
)); ?>


<div class="container" id="page">

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>


</div><!-- page -->

<footer>
    <p>Versión <?php echo Yii::app()->params->appVersion; ?></p>
</footer>

</body>
</html>
