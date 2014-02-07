<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List User','url'=>array('index')),
	array('label'=>'Create User','url'=>array('create')),
	array('label'=>'Update User','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete User','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User','url'=>array('admin')),
);
?>

<h1>View User #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'password',
		'alias',
		'email',
		'birthdate',
		'role',
		'group_id',
		'side',
		'status',
		'rank',
		'ptos_tueste',
		'ptos_retueste',
		'ptos_relanzamiento',
		'ptos_talentos',
		'tostolares',
		'experience',
		'fame',
		'sugarcubes',
		'dominio_tueste',
		'dominio_habilidades',
		'dominio_bandos',
		'times',
		'calls',
		'last_regen_timestamp',
		'last_notification_read',
		'last_activity',
		'active',
	),
)); ?>
