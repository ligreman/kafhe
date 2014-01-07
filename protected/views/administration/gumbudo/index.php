<?php
$this->breadcrumbs=array(
	'Gumbudos',
);

$this->menu=array(
	array('label'=>'Create Gumbudo','url'=>array('create')),
	array('label'=>'Manage Gumbudo','url'=>array('admin')),
);
?>

<h1>Gumbudos</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
