<?php
$this->breadcrumbs=array(
	'Configurations',
);

$this->menu=array(
	array('label'=>'Create Configuration','url'=>array('create')),
	array('label'=>'Manage Configuration','url'=>array('admin')),
);
?>

<h1>Configurations</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
