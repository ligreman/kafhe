<?php
$this->breadcrumbs=array(
	'Modifiers',
);

$this->menu=array(
	array('label'=>'Create Modifier','url'=>array('create')),
	array('label'=>'Manage Modifier','url'=>array('admin')),
);
?>

<h1>Modifiers</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
