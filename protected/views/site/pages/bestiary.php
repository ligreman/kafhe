<div class="paddedContent page">
    <?php
    $this->pageTitle=Yii::app()->name . ' - Bestiario';

    $gunbudos = array(
        array('Gunbudo Asaltante', 'gunbudoAsaltante', 'Es un Gunbudo que realiza '.Yii::app()->config->getParam('gunbudoAsaltanteActions').' ataque/s a un corral enemigo aleatorio, cada 2 horas durante 12 horas.##Al crearse el jugador selecciona un arma (garras, colmillos, púas).##Si el Gunbudo Asaltante se enfrenta a un Gunbudo Guardián y empata o pierde, desistirá en su ataque hasta el siguiente ataque y cambiará de arma por la que le derrotó. Si gana, penetrará en el corral y acabará con un número de Gungubos de ['.Yii::app()->config->getParam('gunbudoAsaltanteMinMuertes').'-'.Yii::app()->config->getParam('gunbudoAsaltanteMaxMuertes').'].##Con una probabilidad de '.Yii::app()->config->getParam('gunbudoAsaltanteProbabilidadSanguinario').'% el Gunbudo Asaltante puede crearse con la característica <em><=Sanguinario=> (2)</em>.'),
        array()
    );

    $caracteristicas = array(
        'Sanguinario' => 'Sanguinario (n): el Gunbudo mata n veces más Gungubos con sus ataques (multiplica por n las muertes que provoca).',
    );
    ?>


    <div>
        <ul>
            <?php
            foreach ($gunbudos as $gunbudo) {
                $nombre = array_shift($gunbudo);
                $imagen = array_shift($gunbudo);
                $descripcion = array_shift($gunbudo);

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