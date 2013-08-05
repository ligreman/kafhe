<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
/*$this->breadcrumbs=array(
	'Login',
);*/

?>
<section id="loginSection">
    <h1 class="oculto">Acceso</h1>

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),'htmlOptions' => array('class' => 'form-horizontal') )); ?>

        <div class="control-group">
            <?php echo $form->labelEx($model,'username', array('class' => 'control-label','label' => 'Nombre de usuario')); ?>
            <?php echo $form->textField($model,'username', array('class' => 'controls', 'placeholder' => 'Aquí tu nombre de usuario')); ?>
            <?php echo $form->error($model,'username'); ?>
        </div>

        <div class="control-group">
            <?php echo $form->labelEx($model,'password', array('class' => 'control-label','label' => 'Contraseña')); ?>
            <?php echo $form->passwordField($model,'password', array('class' => 'controls','placeholder' => 'Aquí tu contraseña')); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>

        <div class="control-group rememberMe">
            <?php echo $form->checkBox($model,'rememberMe', array('class' => 'controls')); ?>
            <?php echo $form->label($model,'rememberMe', array('class' => 'checkbox','label' => 'Acuerdate de mi guapetón')); ?>
            <?php echo $form->error($model,'rememberMe'); ?>
        </div>

        <div class="control-group submit-group">
            <?php echo CHtml::submitButton('Login', array('class' => 'btn controls btncommon')); ?>
        </div>


    <?php $this->endWidget(); ?>

    <?php //$this->widget('application.modules.hybridauth.widgets.renderProviders'); ?>

</section>