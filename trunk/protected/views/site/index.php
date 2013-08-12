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
    <?php $last_type = ""; ?>
    <?php
		if ($notifications!==null):
		
			$nuevas = $notifications['new'];
			$viejas = $notifications['old'];
			
			//echo "<br>Nuevas: ".count($nuevas);
			//echo "<br>Viejas: ".count($viejas);
			
			
			if (count($nuevas)>0): ?>
				<p class="categoriaNotif"><span>notificaciones sin leer</span></p>
				
				<?php foreach($nuevas as $notification):?>
					<article data-rel="<?php echo $notification->timestamp; ?>" class="notification <?php echo $notification->type;?> <?php
						if(strcmp($notification->type,$last_type)!=0 && (strcmp($last_type, KAFHE)==0 || strcmp($last_type,ACHIKHORIA)==0 || strcmp($last_type,"")==0)){
							echo 'first';
							$last_type = $notification->type;
						}
						?>">
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
                            $pattern = '/:+([a-z]+):+/i';
                            echo preg_replace('/:+([a-z]+):+/i', '<span class="image">'.CHtml::image(Yii::app()->baseUrl."/images/skills/$1.png",'$1',array('width' => '48')).'</span><span>', $notification->message);
                            //echo $notification->message;
                            ?></p>
					</article>

				<?php //echo $notification->read."<br>";?>

				<?php 
				endforeach;
			endif; //nuevas
			
			
			if (count($viejas)>0): ?>
				<p class="categoriaNotif"><span>notificaciones leídas</span></p>
				<?php foreach($viejas as $notification): ?>
					<article data-rel="<?php echo $notification->timestamp; ?>" class="notification <?php echo $notification->type;?> <?php
						if(strcmp($notification->type,$last_type)!=0 && (strcmp($last_type, KAFHE)==0 || strcmp($last_type,ACHIKHORIA)==0 || strcmp($last_type,"")==0)){
							echo 'first';
							$last_type = $notification->type;
						}
						?>">
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
                            $pattern = '/:+([a-z]+):+/i';
                            print_r(preg_replace('/:+([a-z]+):+/i', CHtml::image(Yii::app()->baseUrl."/images/skills/$1.png"), $notification->message));
                            //echo $notification->message;
                            ?></p>
					</article>

				<?php //echo $notification->read."<br>";?>

				<?php 
				endforeach;			
			endif; //viejas
			
		endif;?>
		
		<p>Botón de cargar más</p>
		
    <div class="clear"></div>
</div>
