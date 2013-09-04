<?php
$this->breadcrumbs=array(
	'Cronpiles'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List Cronpile','url'=>array('index')),
	array('label'=>'Create Cronpile','url'=>array('create')),
	array('label'=>'Update Cronpile','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Cronpile','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Cronpile','url'=>array('admin')),
);
?>

<h1>View Cronpile #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'operation',
		'params',
	),
)); ?>
