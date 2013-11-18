<?php
$this->breadcrumbs=array(
	'Meals'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List Meal','url'=>array('index')),
	array('label'=>'Create Meal','url'=>array('create')),
	array('label'=>'View Meal','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Meal','url'=>array('admin')),
);
?>

<h1>Update Meal <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>