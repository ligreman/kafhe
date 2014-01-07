<?php
$this->breadcrumbs=array(
	'Gumbudos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List Gumbudo','url'=>array('index')),
	array('label'=>'Create Gumbudo','url'=>array('create')),
	array('label'=>'View Gumbudo','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Gumbudo','url'=>array('admin')),
);
?>

<h1>Update Gumbudo <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>