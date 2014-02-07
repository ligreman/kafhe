<?php
$this->breadcrumbs=array(
	'User Talents'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List UserTalent','url'=>array('index')),
	array('label'=>'Create UserTalent','url'=>array('create')),
	array('label'=>'Update UserTalent','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete UserTalent','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserTalent','url'=>array('admin')),
);
?>

<h1>View UserTalent #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'talent_id',
	),
)); ?>
