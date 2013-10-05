<?php
$this->breadcrumbs=array(
	'Skills'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List Skill','url'=>array('index')),
	array('label'=>'Manage Skill','url'=>array('admin')),
);
?>

<h1>Create Skill</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>