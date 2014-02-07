<?php
$this->breadcrumbs=array(
	'Drinks'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List Drink','url'=>array('index')),
	array('label'=>'Manage Drink','url'=>array('admin')),
);
?>

<h1>Create Drink</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>