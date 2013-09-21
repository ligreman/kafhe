<?php
$this->breadcrumbs=array(
	'History Skill Executions'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List HistorySkillExecution','url'=>array('index')),
	array('label'=>'Create HistorySkillExecution','url'=>array('create')),
	array('label'=>'Update HistorySkillExecution','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete HistorySkillExecution','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage HistorySkillExecution','url'=>array('admin')),
);
?>

<h1>View HistorySkillExecution #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'event_id',
		'skill_id',
		'caster_id',
		'target_final',
		'result',
		'timestamp',
	),
)); ?>
