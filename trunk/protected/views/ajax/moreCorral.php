<?php
$nombres_tiempo=array('día','hora','minuto','segundo');
$pattern = '/:+([a-zA-Z]+):+/i';
$last_read = Yii::app()->currentUser->lastNotificationRead;
$habiaNoLeidas = null;

    foreach ($notifications as $key => $notif):
        //Miro a ver si la primera notificación es no leída
        if ($habiaNoLeidas==null) {
            if (strtotime($notif->timestamp) > strtotime($last_read))
                $habiaNoLeidas = true;
            else $habiaNoLeidas = false;
        }

        //Si había no leídas y esta notificación está leída, he de meter el separador de a partir de aquí leídas
        if ($habiaNoLeidas && (strtotime($notif->timestamp) <= strtotime($last_read))) {
            ?> <p class="corralNotif"><span>Notificaciones leídas</span></p> <?php
            $habiaNoLeidas = 'none'; //para que no haga nada más, pongo el separador una sola vez
        }

    ?>
    <article data-rel="<?php echo $notif->timestamp; ?>">
            <?php
                    //Calculamos el tiempo que hace
                    $fecha_noti = date_create($notif->timestamp);
                    //$fecha_noti = $notif->timestamp;
                    $intervalo = date_diff(Yii::app()->event->getCurrentDateTime(), $fecha_noti);
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

                <?php if(preg_match($pattern,$notif->message)):?>
                    <p class="corral_message image_message">
                    <?php echo preg_replace($pattern, '<span class="image">'.CHtml::image(Yii::app()->baseUrl."/images/bestiary/minis/$1.png",'$1',array('class' => 'icon')).'</span><span>', $notif->message).'</span>';?>
                    </p>
                <?php else:?>
                    <p class="corral_message">
                    <?php echo '<span>'.$notif->message.'</span>';?>
                    </p>
                <?php endif;?>
        </article>
<?php endforeach;?>

<?php if($hay_mas): ?>
    <p id="moreCorralNotifications"><a href="#" class="btn btn<?php echo YIi::app()->currentUser->side?>">Ver más notificaciones</a></p>
<?php else: ?>
    <p class="corralNotif"><span>No hay más notificaciones</span></p>
<?php endif; ?>