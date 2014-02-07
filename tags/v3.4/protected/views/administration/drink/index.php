<?php
$this->breadcrumbs=array(
	'Drinks',
);

$this->menu=array(
	array('label'=>'Create Drink','url'=>array('create')),
	array('label'=>'Manage Drink','url'=>array('admin')),
);
?>

<h1>Drinks</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
