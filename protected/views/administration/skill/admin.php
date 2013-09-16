<?php
$this->breadcrumbs=array(
	'Skills'=>array('index'),
	'Manage',
);

$this->menu=array(
	//array('label'=>'List Skill','url'=>array('index')),
	array('label'=>'Create Skill','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('skill-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Skills</h1>

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
	'id'=>'skill-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'description',
		'category',
		'type',
		'keyword',
		'modifier_keyword',
		'modifier_hidden',
		'duration',
		'duration_type',
		'critic',
		'fail',
		'extra_param',
		'cost_tueste',
		'cost_retueste',
		'cost_relanzamiento',
		'cost_tostolares',
		'is_cooperative',
		'cost_tueste_cooperate',
		'cost_tostolares_cooperate',
		'cooperate_benefit',
		'require_target_user',
		'require_target_side',
		'require_caller',
		'require_user_side',
		'require_user_min_rank',
		'require_user_max_rank',
		'require_user_status',
		'require_event_status',
		'require_talent_id',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
