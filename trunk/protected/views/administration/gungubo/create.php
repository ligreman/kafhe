<?php
$this->breadcrumbs=array(
	'Gungubos'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List Gungubo','url'=>array('index')),
	array('label'=>'Manage Gungubo','url'=>array('admin')),
);
?>

<h1>Create Gungubo</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>