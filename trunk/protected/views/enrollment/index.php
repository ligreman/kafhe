<?php
/* @var $this EnrollmentController */

$this->breadcrumbs=array(
	'Enrollment',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>


<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enrollment-form',
	'enableClientValidation'=>true,
        //'enableAjaxValidation'=>true,
	'clientOptions'=>array(
              'validateOnSubmit'=>true,
              'validateOnChange'=>false,
            /*'afterValidate'=>"js:function(form, data, hasError) {
                if (hasError) {
                    alert('There were errors. Please correct them before submitting again.');
                    return false; //<- normally not needed
                }
            }",*/
	),
)); ?>
    
    <?php echo $form->errorSummary($model); ?>

	<p class="note">Los campos marcados con <span class="required">*</span> son obligatorios.</p>

  <div class="row rememberMe">
		<?php echo $form->checkBox($model,'ito'); ?>
		<?php echo $form->labelEx($model,'ito'); ?>
		<?php echo $form->error($model,'ito'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mealId'); ?>
		<?php echo $form->radioButtonList($model,'mealId', CHtml::listData($meals, 'id', 'name')); ?>
		<?php echo $form->error($model,'mealId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'drinkId'); ?>
		<?php echo $form->radioButtonList($model,'drinkId', CHtml::listData($drinks, 'id', 'name')); ?>
		<?php echo $form->error($model,'drinkId'); ?>		
	</div>

	<div class="row buttons">
            <?php echo CHtml::submitButton(!$already_enroll ? 'Alistarse' : 'Actualizar pedido', array('name'=>'btn_submit')); ?>
            <?php 
              if ($already_enroll) {
                echo CHtml::submitButton('Darse de baja', array('name'=>'btn_cancel'));
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
