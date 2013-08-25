<?php
$this->breadcrumbs=array(
	'Enrollments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List Enrollment','url'=>array('index')),
	array('label'=>'Create Enrollment','url'=>array('create')),
	array('label'=>'View Enrollment','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Enrollment','url'=>array('admin')),
);
?>

<h1>Update Enrollment <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>