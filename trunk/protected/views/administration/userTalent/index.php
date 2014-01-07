<?php
$this->breadcrumbs=array(
	'User Talents',
);

$this->menu=array(
	array('label'=>'Create UserTalent','url'=>array('create')),
	array('label'=>'Manage UserTalent','url'=>array('admin')),
);
?>

<h1>User Talents</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
