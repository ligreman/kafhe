<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('event_id')); ?>:</b>
	<?php echo CHtml::encode($data->event_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('owner_id')); ?>:</b>
	<?php echo CHtml::encode($data->owner_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('side')); ?>:</b>
	<?php echo CHtml::encode($data->side); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('class')); ?>:</b>
	<?php echo CHtml::encode($data->class); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('actions')); ?>:</b>
	<?php echo CHtml::encode($data->actions); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('trait')); ?>:</b>
	<?php echo CHtml::encode($data->trait); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('trait_value')); ?>:</b>
	<?php echo CHtml::encode($data->trait_value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('weapon')); ?>:</b>
	<?php echo CHtml::encode($data->weapon); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ripdate')); ?>:</b>
	<?php echo CHtml::encode($data->ripdate); ?>
	<br />

	*/ ?>

</div>