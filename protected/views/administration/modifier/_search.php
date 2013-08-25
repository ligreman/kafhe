<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'event_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'caster_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'target_final',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'skill_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'item_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'keyword',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'value',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'duration',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'duration_type',array('class'=>'span5','maxlength'=>6)); ?>

	<?php echo $form->textFieldRow($model,'hidden',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'timestamp',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
