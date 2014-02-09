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

	<b><?php echo CHtml::encode($data->getAttributeLabel('fame_kafhe')); ?>:</b>
	<?php echo CHtml::encode($data->fame_kafhe); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('fame_achikhoria')); ?>:</b>
	<?php echo CHtml::encode($data->fame_achikhoria); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	*/ ?>

</div>