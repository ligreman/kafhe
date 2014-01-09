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

<?php
if ($gungubos!==null) {
    $normales = $quemados = $enfermos = $cementerio = array();
    foreach($gungubos as $gungubo) {
        if ($gungubo->condition_status==Yii::app()->params->conditionNormal && $gungubo->location=='corral') {
            $normales[] = $gungubo;
        } elseif ($gungubo->condition_status==Yii::app()->params->conditionQuemadura && $gungubo->location=='corral') {
            $quemados[] = $gungubo;
        } elseif ($gungubo->condition_status==Yii::app()->params->conditionEnfermedad && $gungubo->location=='corral') {
            $enfermos[] = $gungubo;
        } elseif ($gungubo->location=='cementerio') {
            $cementerio[] = $gungubo;
        }
    }
}
?>


    <p>En el mundo hay <?php echo count($gungubos);?> Gungubos:</p>
    <p><ul>
        <?php
            echo '<li>'.count($normales).' Gungubos en estado normal.</li>';
            echo '<li>'.count($quemados).' Gungubos con quemadura.</li>';
            echo '<li>'.count($enfermos).' Gungubos enfermos.</li>';
            echo '<li>'.count($cementerio).' Gungubos en el cementerio.</li>';
        ?>
    </ul></p>
    <p>Lista de Gumbudos:</p>

    <p><ul>
    <?php
    foreach ($gumbudos as $gumbudo) {
        echo '<li>'.Yii::app()->params->gumbudoClassNames[$gumbudo->class].' ('.Yii::app()->params->sideNames[$gumbudo->side].')</li>';
    }

    ?>
    </ul></p>

    <br class="clear" />
</div>