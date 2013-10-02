<div id="menuContent">
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

      <div class="itoSelect">
            <?php echo $form->checkBox($model,'ito',array('class' => 'oculto')); ?>
            <?php echo $form->labelEx($model,'ito'); ?>
            <?php //echo $form->error($model,'ito'); ?>
        </div>


        <div class="centerContainer">
            <div class="blockCentered">
                <div id="drinks">
                    <?php echo $form->label($model,'drink_id',array('class' => 'title')); ?>
                    <?php echo $form->radioButtonList($model,'drink_id', CHtml::listData($drinks, 'id', 'name'), array('container'=>'ul', 'template' => '<li class="radio_row">{input}{label}</li>','separator' => '')); ?>
                    <?php //echo $form->error($model,'drink_id'); ?>
                </div>

                <div id="meals">
                    <?php echo $form->label($model,'meal_id',array('class' => 'title')); ?>
                    <?php echo $form->radioButtonList($model,'meal_id', CHtml::listData($meals, 'id', 'name'), array('container'=>'ul', 'template' => '<li class="radio_row">{input}{label}</li>','separator' => '')); ?>
                    <?php //echo $form->error($model,'meal_id'); ?>
                </div>
            </div>
        </div>

        <div class="otherDay">
            <?php echo CHtml::linkButton('Lo del otro día', array('name'=>'btn_otroDia', 'class'=>'btn btncommon', 'rel-meal'=>$prev_meal, 'rel-drink'=>$prev_drink, 'rel-ito'=>$prev_ito, 'onclick'=>'return false;')); ?>
        </div>

        <div class="buttons">            
            <?php echo CHtml::submitButton('Guardar cambios'); ?>
        </div>

    <?php $this->endWidget(); ?>

</div>


