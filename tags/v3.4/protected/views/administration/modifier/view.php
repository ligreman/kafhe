<?php
$this->breadcrumbs=array(
	'Modifiers'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List Modifier','url'=>array('index')),
	array('label'=>'Create Modifier','url'=>array('create')),
	array('label'=>'Update Modifier','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Modifier','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Modifier','url'=>array('admin')),
);
?>

<h1>View Modifier #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'event_id',
		'caster_id',
		'target_final',
		'skill_id',
		'item_id',
		'keyword',
		'value',
		'duration',
		'duration_type',
		'hidden',
		'timestamp',
	),
)); ?>
