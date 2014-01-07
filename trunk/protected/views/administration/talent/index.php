<?php
$this->breadcrumbs=array(
	'Talents',
);

$this->menu=array(
	array('label'=>'Create Talent','url'=>array('create')),
	array('label'=>'Manage Talent','url'=>array('admin')),
);
?>

<h1>Talents</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
