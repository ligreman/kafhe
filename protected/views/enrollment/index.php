<div id="menuContent">

<h1 class="oculto">Alistamiento</h1>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enrollment-form',
	'enableClientValidation'=>true,

	'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'inputContainer'=>'ul',
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
    
    <?php echo $form->errorSummary($model); ?>

	<p>Comienza a formar parte del bando de Kafhe. Alístate diciendo que vas a tomar en el próximo desayuno:</p>

  <div class="itoSelect">
		<?php echo $form->checkBox($model,'ito',array('class' => 'oculto')); ?>
		<?php echo $form->labelEx($model,'ito'); ?>
		<?php echo $form->error($model,'ito'); ?>
	</div>

    <div class="centerContainer">
        <div class="blockCentered">
            <div id="drinks">
                <?php echo $form->label($model,'drink_id',array('class' => 'title')); ?>
                <ul>
                    <?php echo $form->radioButtonList($model,'drink_id', CHtml::listData($drinks, 'id', 'name'), array('template' => '<li class="radio_row">{input}{label}</li>','separator' => '')); ?>
                </ul>
                <?php echo $form->error($model,'drink_id'); ?>
            </div>

            <div id="meals">
                <?php echo $form->label($model,'meal_id',array('class' => 'title')); ?>
                <ul>
                    <?php echo $form->radioButtonList($model,'meal_id', CHtml::listData($meals, 'id', 'name'), array('template' => '<li class="radio_row">{input}{label}</li>','separator' => '')); ?>
                </ul>
                <?php echo $form->error($model,'meal_id'); ?>
            </div>
        </div>
    </div>

	<div class="buttons">
            <?php echo CHtml::submitButton(!$already_enroll ? 'Alistarse' : 'Actualizar pedido', array('name'=>'btn_submit', 'class' => 'btn btn'.Yii::app()->user->side)); ?>
            <?php 
              if ($already_enroll) {
                echo CHtml::submitButton('Darse de baja', array('name'=>'btn_cancel', 'class' => 'btn'));
                }
            ?>
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Modificar'); ?>
	</div>

<?php $this->endWidget(); ?>
</div>


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
