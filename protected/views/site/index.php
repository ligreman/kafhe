<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
$nombres_tiempo=array('día','hora','minuto','segundo');
?>

<p>Notificaciones</p>
    <?php foreach($notifications as $notification):?>
        <article class="notification <?php echo $notification->type;?>"><?php //TODO: Obtener el bando para incluir la clase del article?>
            <h1><?php echo Yii::app()->usernames->getAlias($notification->recipient_final); ?></h1>
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