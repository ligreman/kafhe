<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'group_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'caller_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'caller_side',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'relauncher_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'gungubos_population',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'gungubos_kafhe',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'gungubos_achikhoria',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'last_gungubos_criadores',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'stored_tueste_kafhe',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'stored_tueste_achikhoria',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'type',array('class'=>'span5','maxlength'=>8)); ?>

	<?php echo $form->textFieldRow($model,'date',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
