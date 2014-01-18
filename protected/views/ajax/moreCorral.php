<?php
    $nombres_tiempo=array('día','hora','minuto','segundo');
    $pattern = '/:+([a-zA-Z]+):+/i';
    foreach ($notifications as $key => $notif): ?>
    <article data-rel="<?php echo Yii::app()->event->getCurrentDate($notif->timestamp); ?>">
            <?php
                    //Calculamos el tiempo que hace
                    //$fecha_noti = date_create($notif->timestamp);
                    $fecha_noti = Yii::app()->event->getCurrentDateTime($notif->timestamp);
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
                    <?php echo preg_replace($pattern, '<span class="image">'.CHtml::image(Yii::app()->baseUrl."/images/skills/$1.png",'$1',array('class' => 'icon')).'</span><span>', $notif->message).'</span>';?>
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