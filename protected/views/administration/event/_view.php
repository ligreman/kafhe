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

	<b><?php echo CHtml::encode($data->getAttributeLabel('gungubos_population')); ?>:</b>
	<?php echo CHtml::encode($data->gungubos_population); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('gungubos_kafhe')); ?>:</b>
	<?php echo CHtml::encode($data->gungubos_kafhe); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fame_kafhe')); ?>:</b>
	<?php echo CHtml::encode($data->fame_kafhe); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gungubos_achikhoria')); ?>:</b>
	<?php echo CHtml::encode($data->gungubos_achikhoria); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fame_achikhoria')); ?>:</b>
	<?php echo CHtml::encode($data->fame_achikhoria); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_gungubos_criadores')); ?>:</b>
	<?php echo CHtml::encode($data->last_gungubos_criadores); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stored_tueste_kafhe')); ?>:</b>
	<?php echo CHtml::encode($data->stored_tueste_kafhe); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stored_tueste_achikhoria')); ?>:</b>
	<?php echo CHtml::encode($data->stored_tueste_achikhoria); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	*/ ?>

</div>