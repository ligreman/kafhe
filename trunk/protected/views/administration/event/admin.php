<?php
$this->breadcrumbs=array(
	'Events'=>array('index'),
	'Manage',
);

$this->menu=array(
	//array('label'=>'List Event','url'=>array('index')),
	array('label'=>'Create Event','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('event-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Events</h1>

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
	'id'=>'event-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'group_id',
		'caller_id',
		'caller_side',
		'relauncher_id',
		'status',
		'gungubos_population',
		'gungubos_kafhe',
		'fame_kafhe',
		'gungubos_achikhoria',
		'fame_achikhoria',
		'last_gungubos_criadores',
		'stored_tueste_kafhe',
		'stored_tueste_achikhoria',
		'type',
		'date',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
