<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'gumbudo-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'event_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'owner_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'side',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'class',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'actions',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'trait',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'trait_value',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'weapon',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'ripdate',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
