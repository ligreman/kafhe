<?php
$this->breadcrumbs=array(
	'Enrollments'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List Enrollment','url'=>array('index')),
	array('label'=>'Create Enrollment','url'=>array('create')),
	array('label'=>'Update Enrollment','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Enrollment','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Enrollment','url'=>array('admin')),
);
?>

<h1>View Enrollment #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'event_id',
		'meal_id',
		'drink_id',
		'ito',
		'timestamp',
	),
)); ?>
