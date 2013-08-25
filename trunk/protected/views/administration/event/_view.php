<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('group_id')); ?>:</b>
	<?php echo CHtml::encode($data->group_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('caller_id')); ?>:</b>
	<?php echo CHtml::encode($data->caller_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('caller_side')); ?>:</b>
	<?php echo CHtml::encode($data->caller_side); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('relauncher_id')); ?>:</b>
	<?php echo CHtml::encode($data->relauncher_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gungubos_kafhe')); ?>:</b>
	<?php echo CHtml::encode($data->gungubos_kafhe); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('gungubos_achikhoria')); ?>:</b>
	<?php echo CHtml::encode($data->gungubos_achikhoria); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_gungubos_timestamp')); ?>:</b>
	<?php echo CHtml::encode($data->last_gungubos_timestamp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	*/ ?>

</div>