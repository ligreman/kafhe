<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'description',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'category',array('class'=>'span5','maxlength'=>13)); ?>

	<?php echo $form->textFieldRow($model,'type',array('class'=>'span5','maxlength'=>8)); ?>

	<?php echo $form->textFieldRow($model,'keyword',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'modifier_keyword',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'modifier_hidden',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'duration',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'duration_type',array('class'=>'span5','maxlength'=>6)); ?>

	<?php echo $form->textFieldRow($model,'critic',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'fail',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'cost_tueste',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'cost_retueste',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'cost_relanzamiento',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'cost_tostolares',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'is_cooperative',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'cost_tueste_cooperate',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'cost_tostolares_cooperate',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'cooperate_benefit',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'require_target_user',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'require_target_side',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'require_caller',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'require_user_side',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'require_user_min_rank',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'require_user_status',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'require_event_status',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'require_talent_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
