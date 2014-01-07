<?php
$this->breadcrumbs=array(
	'User Talents'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List UserTalent','url'=>array('index')),
	array('label'=>'Manage UserTalent','url'=>array('admin')),
);
?>

<h1>Create UserTalent</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>