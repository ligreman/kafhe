<?php
$this->breadcrumbs=array(
	'Notification Corrals'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List NotificationCorral','url'=>array('index')),
	array('label'=>'Create NotificationCorral','url'=>array('create')),
	array('label'=>'View NotificationCorral','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage NotificationCorral','url'=>array('admin')),
);
?>

<h1>Update NotificationCorral <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>