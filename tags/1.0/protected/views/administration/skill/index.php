<?php
$this->breadcrumbs=array(
	'Skills',
);

$this->menu=array(
	array('label'=>'Create Skill','url'=>array('create')),
	array('label'=>'Manage Skill','url'=>array('admin')),
);
?>

<h1>Skills</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
