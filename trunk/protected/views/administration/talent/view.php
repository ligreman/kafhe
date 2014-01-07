<?php
$this->breadcrumbs=array(
	'Talents'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List Talent','url'=>array('index')),
	array('label'=>'Create Talent','url'=>array('create')),
	array('label'=>'Update Talent','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Talent','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Talent','url'=>array('admin')),
);
?>

<h1>View Talent #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'keyword',
		'required_id',
	),
)); ?>
