<?php
$this->breadcrumbs=array(
	'Gumbudos'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List Gumbudo','url'=>array('index')),
	array('label'=>'Manage Gumbudo','url'=>array('admin')),
);
?>

<h1>Create Gumbudo</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>