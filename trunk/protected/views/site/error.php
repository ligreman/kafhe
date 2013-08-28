<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
?>
<div id="menuContent" class="paddedContent">
    <h2 class="error<?php echo $code; ?>">Error <?php echo $code; ?></h2>

    <?php if($code == "404"): ?>
    <div class="error404">
        <p>El Gran Omelettus aún no ha permitido a los humanos venir aquí.</p>
        <?php echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/404.png','');?>
        <?php //echo CHtml::encode($message); ?>
    </div>
    <?php endif;?>
</div>