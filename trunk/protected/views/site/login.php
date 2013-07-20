<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
/*$this->breadcrumbs=array(
	'Login',
);*/

?>

<h1>Acceso</h1>


<p class="note">Fields with <span class="required">*</span> are required.</p>
<p class="hint">
    Hint: You may login with <kbd>demo</kbd>/<kbd>demo</kbd> or <kbd>admin</kbd>/<kbd>admin</kbd>.
</p>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),'htmlOptions' => array('class' => 'form-horizontal') )); ?>

	<div class="control-group">
		<?php echo $form->labelEx($model,'username', array('class' => 'control-label')); ?>
		<?php echo $form->textField($model,'username', array('class' => 'controls')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'password', array('class' => 'control-label')); ?>
		<?php echo $form->passwordField($model,'password', array('class' => 'controls')); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="control-group rememberMe">
        <?php echo $form->label($model,'rememberMe', array('class' => 'checkbox')); ?>
        <?php echo $form->checkBox($model,'rememberMe', array('class' => 'controls')); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

    <div class="control-group">
        <?php echo CHtml::submitButton('Login', array('class' => 'btn controls')); ?>
    </div>


<?php $this->endWidget(); ?>

<?php //$this->widget('application.modules.hybridauth.widgets.renderProviders'); ?>

