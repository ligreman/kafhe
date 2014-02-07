<?php
$this->breadcrumbs=array(
	'Gungubos',
);

$this->menu=array(
	array('label'=>'Create Gungubo','url'=>array('create')),
	array('label'=>'Manage Gungubo','url'=>array('admin')),
);
?>

<h1>Gungubos</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
