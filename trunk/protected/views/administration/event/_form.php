<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'event-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'group_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'caller_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'caller_side',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'relauncher_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'gungubos_kafhe',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'gungubos_achikhoria',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'last_gungubos_timestamp',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'type',array('class'=>'span5','maxlength'=>8)); ?>

	<?php echo $form->textFieldRow($model,'date',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
