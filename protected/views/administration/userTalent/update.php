<?php
$this->breadcrumbs=array(
	'User Talents'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List UserTalent','url'=>array('index')),
	array('label'=>'Create UserTalent','url'=>array('create')),
	array('label'=>'View UserTalent','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage UserTalent','url'=>array('admin')),
);
?>

<h1>Update UserTalent <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>