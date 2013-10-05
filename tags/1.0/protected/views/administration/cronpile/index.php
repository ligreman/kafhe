<?php
$this->breadcrumbs=array(
	'Cronpiles',
);

$this->menu=array(
	array('label'=>'Create Cronpile','url'=>array('create')),
	array('label'=>'Manage Cronpile','url'=>array('admin')),
);
?>

<h1>Cronpiles</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
