<?php
$this->breadcrumbs=array(
	'Meals'=>array('index'),
	$model->name,
);

$this->menu=array(
	//array('label'=>'List Meal','url'=>array('index')),
	array('label'=>'Create Meal','url'=>array('create')),
	array('label'=>'Update Meal','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Meal','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Meal','url'=>array('admin')),
);
?>

<h1>View Meal #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'type',
		'ito',
	),
)); ?>
