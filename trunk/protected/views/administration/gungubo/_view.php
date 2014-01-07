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

	<b><?php echo CHtml::encode($data->getAttributeLabel('attacker_id')); ?>:</b>
	<?php echo CHtml::encode($data->attacker_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('side')); ?>:</b>
	<?php echo CHtml::encode($data->side); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('health')); ?>:</b>
	<?php echo CHtml::encode($data->health); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('location')); ?>:</b>
	<?php echo CHtml::encode($data->location); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('trait')); ?>:</b>
	<?php echo CHtml::encode($data->trait); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('trait_value')); ?>:</b>
	<?php echo CHtml::encode($data->trait_value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('condition_status')); ?>:</b>
	<?php echo CHtml::encode($data->condition_status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('condition_value')); ?>:</b>
	<?php echo CHtml::encode($data->condition_value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('birthdate')); ?>:</b>
	<?php echo CHtml::encode($data->birthdate); ?>
	<br />

	*/ ?>

</div>