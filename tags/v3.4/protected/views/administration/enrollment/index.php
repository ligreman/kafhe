<?php
$this->breadcrumbs=array(
	'Enrollments',
);

$this->menu=array(
	array('label'=>'Create Enrollment','url'=>array('create')),
	array('label'=>'Manage Enrollment','url'=>array('admin')),
);
?>

<h1>Enrollments</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
