<?php
$this->breadcrumbs=array(
	'Notification Corrals'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List NotificationCorral','url'=>array('index')),
	array('label'=>'Create NotificationCorral','url'=>array('create')),
	array('label'=>'Update NotificationCorral','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete NotificationCorral','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage NotificationCorral','url'=>array('admin')),
);
?>

<h1>View NotificationCorral #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'event_id',
		'user_id',
		'message',
		'timestamp',
	),
)); ?>
