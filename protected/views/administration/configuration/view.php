<?php
$this->breadcrumbs=array(
	'Configurations'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List Configuration','url'=>array('index')),
	array('label'=>'Create Configuration','url'=>array('create')),
	array('label'=>'Update Configuration','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Configuration','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Configuration','url'=>array('admin')),
);
?>

<h1>View Configuration #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'param',
		'value',
		'category',
		'description',
	),
)); ?>
