<?php foreach ($notifications as $key => $notification): ?>
    <article data-rel="<?php echo $notification->timestamp; ?>"><?php print_r($notification->message);?></article>
<?php endforeach;?>

<?php if($hay_mas): ?>
    <p id="moreCorralNotifications"><a href="#" class="btn btn<?php echo YIi::app()->currentUser->side?>">Ver más notificaciones</a></p>
<?php else: ?>
    <p class="corralNotif"><span>No hay más notificaciones</span></p>
<?php endif; ?>