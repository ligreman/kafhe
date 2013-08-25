<?php
$this->breadcrumbs=array(
	'Modifiers'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List Modifier','url'=>array('index')),
	array('label'=>'Manage Modifier','url'=>array('admin')),
);
?>

<h1>Create Modifier</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>