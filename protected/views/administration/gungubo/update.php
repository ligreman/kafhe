<?php
$this->breadcrumbs=array(
	'Gungubos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List Gungubo','url'=>array('index')),
	array('label'=>'Create Gungubo','url'=>array('create')),
	array('label'=>'View Gungubo','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Gungubo','url'=>array('admin')),
);
?>

<h1>Update Gungubo <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>