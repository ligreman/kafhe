<?php
$this->breadcrumbs=array(
	'History Skill Executions',
);

$this->menu=array(
	array('label'=>'Create HistorySkillExecution','url'=>array('create')),
	array('label'=>'Manage HistorySkillExecution','url'=>array('admin')),
);
?>

<h1>History Skill Executions</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
