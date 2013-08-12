<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alias'); ?>
		<?php echo $form->textField($model,'alias',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'birthdate'); ?>
		<?php echo $form->textField($model,'birthdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'role'); ?>
		<?php echo $form->textField($model,'role',array('size'=>5,'maxlength'=>5)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'group_id'); ?>
		<?php echo $form->textField($model,'group_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'side'); ?>
		<?php echo $form->textField($model,'side',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rank'); ?>
		<?php echo $form->textField($model,'rank'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ptos_tueste'); ?>
		<?php echo $form->textField($model,'ptos_tueste'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ptos_retueste'); ?>
		<?php echo $form->textField($model,'ptos_retueste'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ptos_relanzamiento'); ?>
		<?php echo $form->textField($model,'ptos_relanzamiento'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ptos_talentos'); ?>
		<?php echo $form->textField($model,'ptos_talentos'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tostolares'); ?>
		<?php echo $form->textField($model,'tostolares'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'experience'); ?>
		<?php echo $form->textField($model,'experience'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sugarcubes'); ?>
		<?php echo $form->textField($model,'sugarcubes'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dominio_tueste'); ?>
		<?php echo $form->textField($model,'dominio_tueste'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dominio_habilidades'); ?>
		<?php echo $form->textField($model,'dominio_habilidades'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dominio_bandos'); ?>
		<?php echo $form->textField($model,'dominio_bandos'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'times'); ?>
		<?php echo $form->textField($model,'times'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'calls'); ?>
		<?php echo $form->textField($model,'calls'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'last_regen_timestamp'); ?>
		<?php echo $form->textField($model,'last_regen_timestamp'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'last_notification_read'); ?>
		<?php echo $form->textField($model,'last_notification_read'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->