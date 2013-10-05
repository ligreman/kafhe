<?php
$this->breadcrumbs=array(
	'Cronpiles'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List Cronpile','url'=>array('index')),
	array('label'=>'Manage Cronpile','url'=>array('admin')),
);
?>

<h1>Create Cronpile</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>