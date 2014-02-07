<?php
$this->breadcrumbs=array(
	'Talents'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List Talent','url'=>array('index')),
	array('label'=>'Create Talent','url'=>array('create')),
	array('label'=>'View Talent','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Talent','url'=>array('admin')),
);
?>

<h1>Update Talent <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>