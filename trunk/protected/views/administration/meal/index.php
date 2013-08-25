<?php
$this->breadcrumbs=array(
	'Meals',
);

$this->menu=array(
	array('label'=>'Create Meal','url'=>array('create')),
	array('label'=>'Manage Meal','url'=>array('admin')),
);
?>

<h1>Meals</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
