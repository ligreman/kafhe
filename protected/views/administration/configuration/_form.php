<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'configuration-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'param',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'value',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldRow($model,'category',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textAreaRow($model,'description',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
