<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.carouFredSel-6.2.0-packed.js"></script>

<div class="paddedContent page">
    <?php
    $this->pageTitle=Yii::app()->name . ' - Bestiario';

    $gumbudos = array(
        array('Gumbudo Criador', 'gumbudoCriador', 'comun', 'Es un Gumbudo que se dedica a cuidar del corral criando a los Gungubos, durante 24 horas.##Duplica el tiempo que tardan en perder vida los Gungubos (de media hora a 1 hora).'),
        array('Gumbudo Asaltante', 'gumbudoAsaltante', 'comun', 'Es un Gumbudo que realiza '.Yii::app()->config->getParam('gumbudoAsaltanteActions').' ataque/s a un corral enemigo aleatorio, cada 2 horas durante 12 horas.##Al crearse el jugador selecciona un arma (garras, colmillos, púas).##Si el Gumbudo Asaltante se enfrenta a un Gumbudo Guardián y empata o pierde, desistirá en su ataque hasta el siguiente ataque y cambiará de arma por la que le derrotó. Si gana, penetrará en el corral y acabará con un número de Gungubos de ['.Yii::app()->config->getParam('gumbudoAsaltanteMinMuertes').'-'.Yii::app()->config->getParam('gumbudoAsaltanteMaxMuertes').'].##Con una probabilidad del '.Yii::app()->config->getParam('gumbudoAsaltanteProbabilidadSanguinario').'% el Gumbudo Asaltante puede crearse con la característica <span class="tooltip">Sanguinario (2)::sanguinario::</span>.'),
        array('Gumbudo Guardián', 'gumbudoGuardian', 'comun', 'Es un Gumbudo que permanece en el corral 12 horas para defenderlo de ataques de otros Gungubos o Gumbudos. Puede detener '.Yii::app()->config->getParam('gumbudoGuardianActions').' ataque/s por hora.##Al crearse el jugador selecciona un arma (garras, colmillos, púas).##Si pierde un combate cambia de arma por la que le derrotó.##Con una probabilidad del '.Yii::app()->config->getParam('gumbudoGuardianProbabilidadAcorazado').'% el Gumbudo Guardián puede crearse con la característica <span class="tooltip">Acorazado (1)::acorazado::</span>.'),
        array('Gumbudo Nigromante', 'gumbudoNigromante', 'achikhoria', 'Es un Gumbudo que durante 12 horas, cada 2 horas (6 ataques), intenta generar con un porcentaje de probabilidad del '.Yii::app()->config->getParam('gumbudoNigromanteProbabilidadZombie').'% un Gungubo Zombie por cada cadáver de tu cementerio. Hay un límite de '.Yii::app()->config->getParam('gumbudoNigromanteMaxZombies').' zombies creados por ataque.##Acto seguido tras levantar a los zombies los envía a atacar a un corral enemigo aleatorio.##Con una probabilidad del '.Yii::app()->config->getParam('gumbudoNigromanteProbabilidadColera').'% el Gungubo Zombie creado tendrá la característica <span class="tooltip">Cólera::colera::</span>.##<span class="tooltip">Consume cadáveres::consumeCadaveres::</span>'),
        array('Gumbudo Artificiero', 'gumbudoArtificiero', 'kafhe', 'Es un Gumbudo que durante 12 horas, cada 2 horas (6 ataques) intenta generar con una probabilidad del '.Yii::app()->config->getParam('gumbudoArtificieroProbabilidadBomba').'% un Gungubo Bomba por cada Gungubos asesinado de tu cementerio.##Hay un límite de '.Yii::app()->config->getParam('gumbudoArtificieroMaxBombas').' bombas que puede crear por ataque.##<span class="tooltip">Consume cadáveres::consumeCadaveres::</span>'),
    );

    $caracteristicas = array(
        'sanguinario' => 'Sanguinario (n): el Gumbudo mata n veces más Gungubos con sus ataques (multiplica por n las muertes que provoca).',
        'acorazado' => 'Acorazado (n): el Gumbudo puede defender n ataques extra, además de los que defiende de base.',
        'colera' => 'Cólera: Un Gungubo colérico no podrá ser bloqueado por un Gumbudo Guardián y penetrará en el corral.',
        'consumeCadaveres' => 'Consume cadáveres: el Gumbudo usa cadáveres del cementerio para realizar su misión. Si tiene éxito remueve el cadáver del cementerio, en caso de fracasar el cadáver permanece en el cementerio para un uso posterior.',
        'consumeGungubos' => 'Consume Gungubos: el Gumbudo usa Gungubos del corral para sus fines.',
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

