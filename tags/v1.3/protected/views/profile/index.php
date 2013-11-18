<div id="menuContent" class="paddedContent">
    <?php
    $flashMessages = Yii::app()->user->getFlashes();
    if ($flashMessages) {
        echo '<ul class="flashes">';
        foreach($flashMessages as $key => $message) {
            echo '<li><div class="flash-' . $key . '">' . $message . "</div></li>\n";
        }
        echo '</ul>';
    }
    ?>

    <h1 class="oculto">Mi cuenta</h1>

    <?php
        if($model->hasErrors()){
            echo '<div class="formErrors">';
            echo CHtml::errorSummary($model);
            echo '</div>';
        }
    ?>

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'profile-form',
        'enableClientValidation'=>true,

        'clientOptions'=>array(
            'validateOnSubmit'=>true,

            //'validateOnChange'=>true,

            /*'afterValidate'=>"js:function(form, data, hasError) {
                if (hasError) {
                    alert('There were errors. Please correct them before submitting again.');
                    return false; //<- normally not needed
                }
            }",*/
        ),
    ));

    ?>
        <!--<p class="floatedLink"><a href="#" class="closeSubmenuLink">Cerrar</a></p>-->
      <p>Información de tu cuenta de usuario:</p>
      
      <div class="row">
          <?php echo $form->labelEx($model,'alias'); ?>
          <?php echo $form->textField($model,'alias',array('maxlength'=>10)); ?>
          <?php echo $form->error($model,'alias'); ?>
      </div>
      
      <div class="row">
          <?php echo $form->labelEx($model,'email'); ?>
          <?php echo $form->emailField($model,'email',array('maxlength'=>128)); ?>
          <?php echo $form->error($model,'email'); ?>
      </div>
      
      <div class="row">
          <?php echo $form->labelEx($model,'password'); ?>
          <?php echo $form->passwordField($model,'password',array('maxlength'=>128,'placeholder' => 'Nueva contraseña')); ?>
          <?php echo $form->error($model,'password'); ?>
      </div>

      <div class="row">
          <?php echo $form->labelEx($model,'password_repeat'); ?>
          <?php echo $form->passwordField($model,'password_repeat',array('maxlength'=>128, 'placeholder' => 'Verifica la contraseña')); ?>
          <?php echo $form->error($model,'password_repeat'); ?>
      </div>
      

        <div class="buttons">            
            <?php echo CHtml::submitButton('Guardar cambios', array('class' => 'btn btncommon')); ?>
        </div>

    <?php $this->endWidget(); ?>

</div>


