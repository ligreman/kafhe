<?php
$this->breadcrumbs=array(
	'Gumbudos'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List Gumbudo','url'=>array('index')),
	array('label'=>'Create Gumbudo','url'=>array('create')),
	array('label'=>'Update Gumbudo','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Gumbudo','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Gumbudo','url'=>array('admin')),
);
?>

<h1>View Gumbudo #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'event_id',
		'owner_id',
		'side',
		'class',
		'actions',
		'trait',
		'trait_value',
		'weapon',
		'ripdate',
	),
)); ?>
