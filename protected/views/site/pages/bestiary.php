<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.carouFredSel-6.2.0-packed.js"></script>

<div class="paddedContent page">
    <?php
    $this->pageTitle=Yii::app()->name . ' - Bestiario';

    $gumbudos = array(
        array('Gumbudo Asaltante', 'gumbudoAsaltante', 'comun', 'Es un Gumbudo que realiza '.Yii::app()->config->getParam('gumbudoAsaltanteActions').' ataque/s a un corral enemigo aleatorio, cada 2 horas durante 12 horas.##Al crearse el jugador selecciona un arma (garras, colmillos, púas).##Si el Gumbudo Asaltante se enfrenta a un Gumbudo Guardián y empata o pierde, desistirá en su ataque hasta el siguiente ataque y cambiará de arma por la que le derrotó. Si gana, penetrará en el corral y acabará con un número de Gungubos de ['.Yii::app()->config->getParam('gumbudoAsaltanteMinMuertes').'-'.Yii::app()->config->getParam('gumbudoAsaltanteMaxMuertes').'].##Con una probabilidad de '.Yii::app()->config->getParam('gumbudoAsaltanteProbabilidadSanguinario').'% el Gumbudo Asaltante puede crearse con la característica <span class="tooltip">Sanguinario (2)::Sanguinario::</span>.'),
        array()
    );

    $caracteristicas = array(
        'Sanguinario' => 'Sanguinario (n): el Gumbudo mata n veces más Gungubos con sus ataques (multiplica por n las muertes que provoca).',
    );
    ?>



    <div id="bestiary">
        <div id="carousel">
            <?php
            foreach ($gumbudos as $gumbudo) {
                $nombre = array_shift($gumbudo);
                $imagen = array_shift($gumbudo);
                $bando = array_shift($gumbudo);
                $descripcion = array_shift($gumbudo);
            ?>
            <div class="<?php echo $bando;?>" rel="<?php echo $nombre;?>">
                <p><?php
                    $texto = str_replace('##', '<br /><br />', $descripcion);

                    foreach($caracteristicas as $caracteristica=>$descripcion) {
                        $texto = str_replace('::'.$caracteristica.'::', '<span class="text">'.$descripcion.'</span>', $texto);
                    }

                    echo $texto;
                ?></p>

                <?php echo CHtml::image(Yii::app()->baseUrl."/images/bestiary/".$imagen.".png",$nombre); ?>
            </div>
            <?php
            } ?>
        </div>
        <div id="pager"></div>
    </div>

</div>


<script type="text/javascript">
    $(function() {
        $('#carousel').carouFredSel({
            direction: 'up',
            circular: false,
            infinite: false,
            auto: false,
            scroll: {
                duration: 1000,
                easing: 'cubic'
            },
            pagination: {
                container: '#pager',
                anchorBuilder: function() {
                    return '<a href="#">'+ $(this).attr('rel') +'</a>';
                }
            }
        });
    });
</script>

