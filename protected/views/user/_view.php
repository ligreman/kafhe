<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('birthdate')); ?>:</b>
	<?php echo CHtml::encode($data->birthdate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('role')); ?>:</b>
	<?php echo CHtml::encode($data->role); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('group_id')); ?>:</b>
	<?php echo CHtml::encode($data->group_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('side')); ?>:</b>
	<?php echo CHtml::encode($data->side); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rank')); ?>:</b>
	<?php echo CHtml::encode($data->rank); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ptos_tueste')); ?>:</b>
	<?php echo CHtml::encode($data->ptos_tueste); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ptos_retueste')); ?>:</b>
	<?php echo CHtml::encode($data->ptos_retueste); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ptos_relanzamiento')); ?>:</b>
	<?php echo CHtml::encode($data->ptos_relanzamiento); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ptos_talentos')); ?>:</b>
	<?php echo CHtml::encode($data->ptos_talentos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tostolares')); ?>:</b>
	<?php echo CHtml::encode($data->tostolares); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('azucarillos')); ?>:</b>
	<?php echo CHtml::encode($data->azucarillos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dominio_tueste')); ?>:</b>
	<?php echo CHtml::encode($data->dominio_tueste); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dominio_habilidades')); ?>:</b>
	<?php echo CHtml::encode($data->dominio_habilidades); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dominio_bandos')); ?>:</b>
	<?php echo CHtml::encode($data->dominio_bandos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('times')); ?>:</b>
	<?php echo CHtml::encode($data->times); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('calls')); ?>:</b>
	<?php echo CHtml::encode($data->calls); ?>
	<br />

	*/ ?>

</div>