<div class="paddedContent page">
    <?php
    $this->pageTitle=Yii::app()->name . ' - Bestiario';

    $gumbudos = array(
        array('Gumbudo Asaltante', 'gumbudoAsaltante', 'Es un Gumbudo que realiza '.Yii::app()->config->getParam('gumbudoAsaltanteActions').' ataque/s a un corral enemigo aleatorio, cada 2 horas durante 12 horas.##Al crearse el jugador selecciona un arma (garras, colmillos, púas).##Si el Gumbudo Asaltante se enfrenta a un Gumbudo Guardián y empata o pierde, desistirá en su ataque hasta el siguiente ataque y cambiará de arma por la que le derrotó. Si gana, penetrará en el corral y acabará con un número de Gungubos de ['.Yii::app()->config->getParam('gumbudoAsaltanteMinMuertes').'-'.Yii::app()->config->getParam('gumbudoAsaltanteMaxMuertes').'].##Con una probabilidad de '.Yii::app()->config->getParam('gumbudoAsaltanteProbabilidadSanguinario').'% el Gumbudo Asaltante puede crearse con la característica <em><=Sanguinario=> (2)</em>.'),
        array()
    );

    $caracteristicas = array(
        'Sanguinario' => 'Sanguinario (n): el Gumbudo mata n veces más Gungubos con sus ataques (multiplica por n las muertes que provoca).',
    );
    ?>


    <div>
        <ul>
            <?php
            foreach ($gumbudos as $gumbudo) {
                $nombre = array_shift($gumbudo);
                $imagen = array_shift($gumbudo);
                $descripcion = array_shift($gumbudo);

                echo '<li>';

                echo CHtml::image(Yii::app()->baseUrl."/images/bestiary/".$imagen.".png",$nombre);

                echo '<p class="titulo">'.$nombre.'</p>';
                echo '<p>';
                $texto = str_replace('##', '</p><p>', $descripcion);
                $texto = str_replace('<=', '<span class="tooltip">', $texto);
                $texto = str_replace('=>', '</span>', $texto);
                echo $texto;

                echo '</p></li>';
            }

            ?>
        </ul>
    </div>


</div>