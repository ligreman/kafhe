<?php
/* @var $this SiteController */
?>

<p>Hola <?php echo CHtml::ajaxLink('Pincha', CController::createUrl('site/PruebaAjax'), array('update'=>'#div')); ?></p>

<?php
    // the data received could look like: {"id":3, "msg":"No error found"}
    //array('success' => 'js:function(data) { $("#newid").val(data.id); $("#message").val(data.msg); }')
?>

<p>Salida: <span id="div">nada</span></p>
