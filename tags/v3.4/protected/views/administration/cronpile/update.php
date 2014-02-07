<?php
$this->breadcrumbs=array(
	'Cronpiles'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List Cronpile','url'=>array('index')),
	array('label'=>'Create Cronpile','url'=>array('create')),
	array('label'=>'View Cronpile','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Cronpile','url'=>array('admin')),
);
?>

<h1>Update Cronpile <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>