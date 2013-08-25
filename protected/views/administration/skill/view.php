<?php
$this->breadcrumbs=array(
	'Skills'=>array('index'),
	$model->name,
);

$this->menu=array(
	//array('label'=>'List Skill','url'=>array('index')),
	array('label'=>'Create Skill','url'=>array('create')),
	array('label'=>'Update Skill','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Skill','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Skill','url'=>array('admin')),
);
?>

<h1>View Skill #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'category',
		'type',
		'keyword',
		'modifier_keyword',
		'modifier_hidden',
		'duration',
		'duration_type',
		'critic',
		'fail',
		'cost_tueste',
		'cost_retueste',
		'cost_relanzamiento',
		'cost_tostolares',
		'is_cooperative',
		'cost_tueste_cooperate',
		'cost_tostolares_cooperate',
		'cooperate_benefit',
		'require_target_user',
		'require_target_side',
		'require_caller',
		'require_user_side',
		'require_user_min_rank',
		'require_user_status',
		'require_event_status',
		'require_talent_id',
	),
)); ?>
