<?php
$this->breadcrumbs=array(
	'Rankings'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List Ranking','url'=>array('index')),
	array('label'=>'Create Ranking','url'=>array('create')),
	array('label'=>'View Ranking','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Ranking','url'=>array('admin')),
);
?>

<h1>Update Ranking <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>