<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'gungubo-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'event_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'owner_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'attacker_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'side',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'health',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'location',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'trait',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'trait_value',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'condition_status',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'condition_value',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'birthdate',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
