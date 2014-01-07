<?php
$this->breadcrumbs=array(
	'Talents'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List Talent','url'=>array('index')),
	array('label'=>'Manage Talent','url'=>array('admin')),
);
?>

<h1>Create Talent</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>