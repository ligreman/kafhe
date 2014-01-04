<?php
/* @var $this SiteController */

define('KAFHE','kafhe');
define('ACHIKHORIA','achikhoria');
define('OMELETTUS','omelettus');
define('SYSTEM','system');

$this->pageTitle=Yii::app()->name;
$nombres_tiempo=array('día','hora','minuto','segundo');
$aliases = Yii::app()->usertools->getAlias();
?>
<div id="muro">
<h1 class="oculto">Notificaciones</h1>
<span id="baseUrl" class="oculto"><?php echo Yii::app()->getBaseUrl(true);?></span>

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
    <?php $last_type = ""; ?>
    <?php
		if ($notifications!==null && !empty($notifications)):
		
			$nuevas = $notifications['new'];
			$viejas = $notifications['old'];
            $hay_mas = $notifications['hay_mas'];
            $pattern = '/:+([a-z]+):+/i';

			//echo "<br>Nuevas: ".count($nuevas);
			//echo "<br>Viejas: ".count($viejas);
			
			
			if (count($nuevas)>0): ?>
				<p class="categoriaNotif"><span>Notificaciones sin leer</span></p>
				
				<?php foreach($nuevas as $notification):?>
					<article data-rel="<?php echo $notification->timestamp; ?>" class="unread notification <?php echo $notification->type;?> <?php
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
                        <p class="notification_message"><?php
                            if(preg_match($pattern,$notification->message)){
                                echo preg_replace($pattern, '<span class="image">'.CHtml::image(Yii::app()->baseUrl."/images/skills/$1.png",'$1',array('class' => 'icon')).'</span><span>', $notification->message).'</span>';
                            }else{
                                echo '<span>'.$notification->message.'</span>';
                            }
                            ?></p>
					</article>

				<?php 
				endforeach;
			endif; //nuevas

            $last_type = "";
			if (count($viejas)>0): ?>
				<p class="categoriaNotif"><span>Notificaciones leídas</span></p>
				<?php foreach($viejas as $notification): ?>
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
						<p class="notification_message"><?php
                            if(preg_match($pattern,$notification->message)){
                                echo preg_replace($pattern, '<span class="image">'.CHtml::image(Yii::app()->baseUrl."/images/skills/$1.png",'$1',array('class' => 'icon')).'</span><span>', $notification->message).'</span>';
                            }else{
                                echo '<span>'.$notification->message.'</span>';
                            }
                            ?></p>
					</article>


				<?php 
				endforeach;			
			endif; //viejas
            if (count($viejas)<=0):?>
                <p class="categoriaNotif hidden"><span>Notificaciones leídas</span></p>
            <?php endif;
		endif;?>

    <?php if($hay_mas): ?>
        <p id="moreNotifications"><a href="#" class="btn btn<?php echo YIi::app()->currentUser->side?>">Ver más notificaciones</a></p>
    <?php else: ?>
        <p class="categoriaNotif"><span>No hay más notificaciones</span></p>
    <?php endif; ?>
		
    <div class="clear"></div>
</div>
<div id="corral_notifications">
    <?php
    if ($notifications_corral!==null && !empty($notifications_corral)):
        $nuevas = $notifications_corral['new'];
        $viejas = $notifications_corral['old'];
        $hay_mas = $notifications_corral['hay_mas'];
        if (count($nuevas)>0): ?>
            <p class="corralNotif"><span>Notificaciones no leídas</span></p>
        <?php endif;?>
        <?php foreach ($nuevas as $key => $notif): ?>
            <article data-rel="<?php echo $notif->timestamp; ?>"><?php print_r($notif->message);?></article>
        <?php endforeach;
        if (count($viejas)>0): ?>
        <p class="corralNotif"><span>Notificaciones leídas</span></p>
        <?php endif;?>
        <?php foreach ($viejas as $key => $notif): ?>
            <article data-rel="<?php echo $notif->timestamp; ?>"><?php print_r($notif->message);?></article>
        <?php endforeach;?>
        <?php if($hay_mas): ?>
            <p id="moreCorralNotifications"><a href="#" class="btn btn<?php echo YIi::app()->currentUser->side?>">Ver más notificaciones</a></p>
        <?php else: ?>
            <p class="corralNotif"><span>No hay más notificaciones</span></p>
        <?php endif; ?>
    <?php endif; ?>
</div>
<div class="clear"></div>
