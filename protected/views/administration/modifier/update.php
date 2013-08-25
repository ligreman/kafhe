<?php
$this->breadcrumbs=array(
	'Modifiers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List Modifier','url'=>array('index')),
	array('label'=>'Create Modifier','url'=>array('create')),
	array('label'=>'View Modifier','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Modifier','url'=>array('admin')),
);
?>

<h1>Update Modifier <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>