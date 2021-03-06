<?php
/* @var $this SiteController */

define('KAFHE','kafhe');
define('ACHIKHORIA','achikhoria');
define('OMELETTUS','omelettus');
define('SYSTEM','system');

$nombres_tiempo=array('día','hora','minuto','segundo');
$aliases = Yii::app()->usertools->getAlias();
$last_read = Yii::app()->currentUser->lastNotificationRead;
$habiaNoLeidas = null;

if($notifications != null):
?>
<?php $last_type = trim($type);?>
    <?php foreach($notifications as $notification):
        //Miro a ver si la primera notificación es no leída
        if ($habiaNoLeidas==null) {
            if (strtotime($notification->timestamp) > strtotime($last_read))
                $habiaNoLeidas = true;
            else $habiaNoLeidas = false;
        }

        //Si había no leídas y esta notificación está leída, he de meter el separador de a partir de aquí leídas
        if ($habiaNoLeidas && (strtotime($notification->timestamp) <= strtotime($last_read))) {
            ?> <p class="categoriaNotif"><span>Notificaciones leídas</span></p> <?php
            $habiaNoLeidas = 'none'; //para que no haga nada más, pongo el separador una sola vez
        }
    ?>
        <article data-rel="<?php echo $notification->timestamp; ?>" class="notification <?php echo $notification->type;?> <?php
            if(strcmp($notification->type,$last_type)!=0 && (strcmp($last_type, KAFHE)==0 || strcmp($last_type,ACHIKHORIA)==0 || strcmp($last_type,"")==0)){
                echo 'first';
                $last_type = $notification->type;
            }
        ?>" target-rel="<?php if($notification->recipient_original == Yii::app()->user->id || $notification->recipient_final == Yii::app()->user->id) echo "self";
                else echo "other";?>" sender-rel="<?php if($notification->sender == Yii::app()->user->id ) echo "self";
                else echo "other";?>">
            <?php //Calculo el nombre a mostrar
                if($notification->type == OMELETTUS) $nombre = 'Omelettus';
                elseif($notification->type == SYSTEM) $nombre = 'System';
                else $nombre = $aliases[$notification->sender];
            ?>
            <h1><?php echo $nombre; //Yii::app()->usertools->getAlias($notification->recipient_final); ?></h1>
            <?php
                //Calculamos el tiempo que hace
                $fecha_noti = date_create($notification->timestamp);
                //$intervalo = date_diff(Yii::app()->utils->getCurrentDateTime(), $fecha_noti);
                $intervalo = Yii::app()->utils->getDateTimeDiff($fecha_noti, Yii::app()->utils->getCurrentDateTime());
                //$tiempo = $intervalo->format("%d,%h,%i,%s");
                $t = array($intervalo['days'], $intervalo['hours'], $intervalo['minutes'], $intervalo['seconds']);
                //$t = explode(',',$tiempo);
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
            <?php
                $pattern = '/:+([a-z]+):+/i';

                if(preg_match($pattern, $notification->message)){
                    echo '<p class="notification_message image_message">';
                    echo preg_replace($pattern, '<span class="image">'.CHtml::image(Yii::app()->baseUrl."/images/skills/$1.png",'$1',array('class' => 'icon')).'</span><span>', $notification->message).'</span></p>';
                }else{
                    echo '<p class="notification_message">';
                    echo '<span>'.$notification->message.'</span></p>';
                }
                ?>
        </article>

    <?php
        endforeach;

    ?>



    <?php if($hay_mas): ?>
        <p id="moreNotifications"><a href="#" class="btn btn<?php echo YIi::app()->currentUser->side?>">Ver más notificaciones</a></p>
    <?php else: ?>
        <p class="categoriaNotif"><span>No hay más notificaciones</span></p>
    <?php endif; ?>


<?php endif; ?>