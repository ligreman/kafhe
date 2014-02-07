<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'user-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'username',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->passwordFieldRow($model,'password',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'alias',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'email',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'birthdate',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'role',array('class'=>'span5','maxlength'=>5)); ?>

	<?php echo $form->textFieldRow($model,'group_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'side',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'rank',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'ptos_tueste',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'ptos_retueste',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'ptos_relanzamiento',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'ptos_talentos',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'tostolares',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'experience',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'fame',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'sugarcubes',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'dominio_tueste',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'dominio_habilidades',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'dominio_bandos',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'times',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'calls',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'last_regen_timestamp',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'last_notification_read',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'last_activity',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'active',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
