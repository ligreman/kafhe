<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);

$this->menu=array(
	//array('label'=>'List User','url'=>array('index')),
	array('label'=>'Create User','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Users</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
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
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
