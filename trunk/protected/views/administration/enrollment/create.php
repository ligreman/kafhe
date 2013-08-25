<?php
$this->breadcrumbs=array(
	'Enrollments'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List Enrollment','url'=>array('index')),
	array('label'=>'Manage Enrollment','url'=>array('admin')),
);
?>

<h1>Create Enrollment</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>