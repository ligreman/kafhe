<?php
$this->breadcrumbs=array(
	'Drinks'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List Drink','url'=>array('index')),
	array('label'=>'Create Drink','url'=>array('create')),
	array('label'=>'View Drink','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Drink','url'=>array('admin')),
);
?>

<h1>Update Drink <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>