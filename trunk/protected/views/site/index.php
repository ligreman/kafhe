<?php
/* @var $this SiteController */

define('KAFHE','kafhe');
define('ACHIKHORIA','achikhoria');

$this->pageTitle=Yii::app()->name;
$nombres_tiempo=array('día','hora','minuto','segundo');
?>
<div id="muro">
<h1 class="oculto">Notificaciones</h1>
    <?php $last_type = ""; ?>
    <?php foreach($notifications as $notification):?>
        <article class="notification <?php echo $notification->type;?> <?php
            if(strcmp($notification->type,$last_type)!=0 && (strcmp($last_type, KAFHE)==0 || strcmp($last_type,ACHIKHORIA)==0 || strcmp($last_type,"")==0)){
                echo 'first';
                $last_type = $notification->type;
            }
            ?>">
            <h1><?php echo Yii::app()->usertools->getAlias($notification->recipient_final); ?></h1>
            <?php
                //Calculamos el tiempo que hace
                $fecha_noti = date_create($notification->timestamp);
                $intervalo = date_diff(date_create(), $fecha_noti);
                $tiempo = $intervalo->format("%d,%h,%i,%s");
                $t = explode(',',$tiempo);
                $i=0;

                while($i<(count($t)-1) && !$t[$i]){
                    $i++;
                }
                $plural = '';
                if($t[$i]>1){
                    $plural = 's';
                }

            ?>
            <p class="timestamp">Hace <?php echo $t[$i].' '.$nombres_tiempo[$i].$plural;?></p>
            <p class="notification_message"><?php echo $notification->message; ?></p>
        </article>

        <?php //echo $notification->read."<br>";?>

    <?php endforeach;?>
    <div class="clear"></div>
</div>