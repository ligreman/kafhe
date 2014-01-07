<?php
$this->breadcrumbs=array(
	'Notification Corrals'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List NotificationCorral','url'=>array('index')),
	array('label'=>'Manage NotificationCorral','url'=>array('admin')),
);
?>

<h1>Create NotificationCorral</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>