<?php
$this->breadcrumbs=array(
	'History Skill Executions'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List HistorySkillExecution','url'=>array('index')),
	array('label'=>'Manage HistorySkillExecution','url'=>array('admin')),
);
?>

<h1>Create HistorySkillExecution</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>