<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'alias'); ?>
		<?php echo $form->textField($model,'alias',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'alias'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'birthdate'); ?>
		<?php echo $form->textField($model,'birthdate'); ?>
		<?php echo $form->error($model,'birthdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'role'); ?>
		<?php echo $form->textField($model,'role',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'role'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'group_id'); ?>
		<?php echo $form->textField($model,'group_id'); ?>
		<?php echo $form->error($model,'group_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'side'); ?>
		<?php echo $form->textField($model,'side',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'side'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rank'); ?>
		<?php echo $form->textField($model,'rank'); ?>
		<?php echo $form->error($model,'rank'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ptos_tueste'); ?>
		<?php echo $form->textField($model,'ptos_tueste'); ?>
		<?php echo $form->error($model,'ptos_tueste'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ptos_retueste'); ?>
		<?php echo $form->textField($model,'ptos_retueste'); ?>
		<?php echo $form->error($model,'ptos_retueste'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ptos_relanzamiento'); ?>
		<?php echo $form->textField($model,'ptos_relanzamiento'); ?>
		<?php echo $form->error($model,'ptos_relanzamiento'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ptos_talentos'); ?>
		<?php echo $form->textField($model,'ptos_talentos'); ?>
		<?php echo $form->error($model,'ptos_talentos'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tostolares'); ?>
		<?php echo $form->textField($model,'tostolares'); ?>
		<?php echo $form->error($model,'tostolares'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'experience'); ?>
		<?php echo $form->textField($model,'experience'); ?>
		<?php echo $form->error($model,'experience'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sugarcubes'); ?>
		<?php echo $form->textField($model,'sugarcubes'); ?>
		<?php echo $form->error($model,'sugarcubes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dominio_tueste'); ?>
		<?php echo $form->textField($model,'dominio_tueste'); ?>
		<?php echo $form->error($model,'dominio_tueste'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dominio_habilidades'); ?>
		<?php echo $form->textField($model,'dominio_habilidades'); ?>
		<?php echo $form->error($model,'dominio_habilidades'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dominio_bandos'); ?>
		<?php echo $form->textField($model,'dominio_bandos'); ?>
		<?php echo $form->error($model,'dominio_bandos'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'times'); ?>
		<?php echo $form->textField($model,'times'); ?>
		<?php echo $form->error($model,'times'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'calls'); ?>
		<?php echo $form->textField($model,'calls'); ?>
		<?php echo $form->error($model,'calls'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_regen_timestamp'); ?>
		<?php echo $form->textField($model,'last_regen_timestamp'); ?>
		<?php echo $form->error($model,'last_regen_timestamp'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_notification_read'); ?>
		<?php echo $form->textField($model,'last_notification_read'); ?>
		<?php echo $form->error($model,'last_notification_read'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->