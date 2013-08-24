<?php
$this->breadcrumbs=array(
	'Configurations'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Configuration','url'=>array('index')),
	array('label'=>'Create Configuration','url'=>array('create')),
	array('label'=>'View Configuration','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Configuration','url'=>array('admin')),
);
?>

<h1>Update Configuration <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>