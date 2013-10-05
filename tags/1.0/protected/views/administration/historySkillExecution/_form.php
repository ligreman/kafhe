<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'history-skill-execution-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'event_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'skill_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'caster_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'target_final',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'result',array('class'=>'span5','maxlength'=>6)); ?>

	<?php echo $form->textFieldRow($model,'timestamp',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
