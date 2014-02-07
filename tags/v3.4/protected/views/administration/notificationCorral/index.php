<?php
$this->breadcrumbs=array(
	'Notification Corrals',
);

$this->menu=array(
	array('label'=>'Create NotificationCorral','url'=>array('create')),
	array('label'=>'Manage NotificationCorral','url'=>array('admin')),
);
?>

<h1>Notification Corrals</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
