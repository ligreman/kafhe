<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'enrollment-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'event_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'meal_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'drink_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'ito',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'timestamp',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
