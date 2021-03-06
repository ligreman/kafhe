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

    <h1 class="oculto">Alistamiento</h1>

    <?php
        if($model->hasErrors()){
            echo '<div class="formErrors">';
            echo CHtml::errorSummary($model);
            echo '</div>';
        }
    ?>

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'enrollment-form',
        'enableClientValidation'=>true,

        'clientOptions'=>array(
            'inputContainer'=>'ul',
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
      <p>Alístate eligiendo tu próximo desayuno:</p>

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
            <?php echo CHtml::submitButton(!$already_enroll ? 'Alistarse' : 'Actualizar pedido', array('name'=>'btn_submit', 'class' => 'btn btn'.Yii::app()->currentUser->side)); ?>
            <?php
              if ($already_enroll) {
                echo CHtml::submitButton('Darse de baja', array('name'=>'btn_cancel', 'class' => 'btn'));
                }
            ?>
            <?php //echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Modificar'); ?>
        </div>

    <?php $this->endWidget(); ?>

</div>


<script type="text/javascript">
    <!--
    //Pongo los itos para filtrarlos
    <?php foreach($drinks as $drink) {
        if (!$drink->ito): ?>
    $('#drinks input[value='+<?php echo intval($drink->id); ?>+']').attr('rel-ito', 'no');
    <?php endif;
}?>

    <?php foreach($meals as $meal) {
        if (!$meal->ito): ?>
    $('#meals input[value='+<?php echo intval($meal->id); ?>+']').attr('rel-ito', 'no');
    <?php endif;
}?>

    prepareEnrollmentForm();
    //-->
</script>


<?php



	/*foreach ($meals as $meal) {
        $this->widget('zii.widgets.CDetailView', array(
            'data'=>$meal,
            'attributes'=>array(
                'id',             // title attribute (in plain text)
                'name',        // an attribute of the related object "owner"
                'type',
				'ito'
            ),
        ));

        echo "<br>";
    }

	foreach ($drinks as $meal) {
        $this->widget('zii.widgets.CDetailView', array(
            'data'=>$meal,
            'attributes'=>array(
                'id',             // title attribute (in plain text)
                'name',        // an attribute of the related object "owner"
                'type',
				'ito'
            ),
        ));

        echo "<br>";
    }*/
    
/*
 * In addition to setting both enableAjaxValidation and validateOnSubmit to true, you can use CActiveForm's afterValidate property if you really want to do something in JavaScript (e.g. alert). All is described in CActiveForm doc.

Example:
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'some-form',
    'enableClientValidation'=>false,
    'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>false,
        'afterValidate'=>"js:function(form, data, hasError) {
            if (hasError) {
                alert('There were errors. Please correct them before submitting again.');
                return false; //<- normally not needed
            }
        }",
    )
)); ?>


And I guess you can use an ajaxSubmitButton in order to submit the form via Ajax. Instead of a Yii flash message, it's easier (better?) just to echo your flash-success div from your controller and display it in the Ajax success scenario.
<?php echo CHtml::ajaxSubmitButton('Submit',
                                      $this->createUrl(…),
                                      array('success' => 'js: function(result) {
                                            if(result != "") {
                                                if (result.indexOf("{") != 0) {
                                                // means the servers-side validation did not return any errors
                                                // useful for some validators where Ajax validation does not cover everything
                                                    $("#someTargetDiv").html(result); // or use .replaceWith()
                                                }
                                            }
                                        }'),
                                    ); ?>

And in your controller, you would do:
if ($model->save) { // or your own test
    echo '<div class="flash-success">Congratulations!</div>';
}

 */    
?>
