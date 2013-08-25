<?php
$this->breadcrumbs=array(
	'Drinks'=>array('index'),
	$model->name,
);

$this->menu=array(
	//array('label'=>'List Drink','url'=>array('index')),
	array('label'=>'Create Drink','url'=>array('create')),
	array('label'=>'Update Drink','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Drink','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Drink','url'=>array('admin')),
);
?>

<h1>View Drink #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'type',
		'ito',
	),
)); ?>
