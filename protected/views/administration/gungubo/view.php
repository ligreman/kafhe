<?php
$this->breadcrumbs=array(
	'Gungubos'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List Gungubo','url'=>array('index')),
	array('label'=>'Create Gungubo','url'=>array('create')),
	array('label'=>'Update Gungubo','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Gungubo','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Gungubo','url'=>array('admin')),
);
?>

<h1>View Gungubo #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'event_id',
		'owner_id',
		'attacker_id',
		'side',
		'health',
		'location',
		'trait',
		'trait_value',
		'condition_status',
		'condition_value',
		'birthdate',
	),
)); ?>
