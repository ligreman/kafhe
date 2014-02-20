<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'notification-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'event_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'sender',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'recipient_original',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'recipient_final',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textAreaRow($model,'message',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'timestamp',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'type',array('class'=>'span5','maxlength'=>10)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
