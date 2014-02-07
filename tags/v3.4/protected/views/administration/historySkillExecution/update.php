<?php
$this->breadcrumbs=array(
	'History Skill Executions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List HistorySkillExecution','url'=>array('index')),
	array('label'=>'Create HistorySkillExecution','url'=>array('create')),
	array('label'=>'View HistorySkillExecution','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage HistorySkillExecution','url'=>array('admin')),
);
?>

<h1>Update HistorySkillExecution <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>