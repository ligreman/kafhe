<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('keyword')); ?>:</b>
	<?php echo CHtml::encode($data->keyword); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('required_id')); ?>:</b>
	<?php echo CHtml::encode($data->required_id); ?>
	<br />


</div>