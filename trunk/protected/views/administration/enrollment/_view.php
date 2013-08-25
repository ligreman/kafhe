<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('event_id')); ?>:</b>
	<?php echo CHtml::encode($data->event_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('meal_id')); ?>:</b>
	<?php echo CHtml::encode($data->meal_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('drink_id')); ?>:</b>
	<?php echo CHtml::encode($data->drink_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ito')); ?>:</b>
	<?php echo CHtml::encode($data->ito); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('timestamp')); ?>:</b>
	<?php echo CHtml::encode($data->timestamp); ?>
	<br />


</div>