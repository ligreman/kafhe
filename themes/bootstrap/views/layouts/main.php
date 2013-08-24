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
                array('label'=>'Notificaciones', 'url'=>array('/administration/user'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='user'?true:false),
                array('label'=>'Usuarios', 'url'=>'#', 'items'=>array(
                    array('label'=>'CUENTAS'),
                    array('label'=>'Grupos', 'url'=>array('/administration/user'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='user'?true:false),
                    array('label'=>'Usuarios', 'url'=>array('/administration/user'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='user'?true:false),
                    //rank
                    '---',
                    array('label'=>'AUTORIZACIÓN'),
                    array('label'=>'Roles y permisos', 'url'=>array('/rights'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='rights'?true:false),
                )),
                array('label'=>'Eventos', 'url'=>'#', 'items'=>array(
                    array('label'=>'BATALLAS'),
                    array('label'=>'Eventos', 'url'=>array('/administration/user'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='user'?true:false),
                    array('label'=>'Alistamiento', 'url'=>array('/administration/user'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='user'?true:false),
                    array('label'=>'Ranking', 'url'=>array('/administration/user'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='user'?true:false),
                    '---',
                    array('label'=>'PEDIDOS'),
                    array('label'=>'Comidas', 'url'=>array('/administration/user'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='user'?true:false),
                    array('label'=>'Bebidas', 'url'=>array('/rights'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='rights'?true:false),
                    //'---',
                    //array('label'=>'NAV HEADER'),
                )),
                array('label'=>'Habilidades', 'url'=>'#', 'items'=>array(
                    array('label'=>'DE USUARIO'),
                    array('label'=>'Habilidades', 'url'=>array('/administration/user'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='user'?true:false),
                    array('label'=>'Modificadores', 'url'=>array('/administration/user'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='user'?true:false),
                )),
                array('label'=>'Configuración', 'url'=>array('/administration/user'), 'visible'=>Yii::app()->user->checkAccess('Administrador'), 'active'=>$this->id=='user'?true:false),
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

</body>
</html>
