<div id="menuContent" class="paddedContent">
    <?php
    $flashMessages = Yii::app()->user->getFlashes();
    if ($flashMessages) {
        echo '<ul class="flashes">';
        foreach($flashMessages as $key => $message) {
            echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
        }
        echo '</ul>';
    }
    ?>

<p>Info info info</p>

    <br class="clear" />
</div>