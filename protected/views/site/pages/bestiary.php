<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.carouFredSel-6.2.0-packed.js"></script>

<div class="paddedContent page">
    <?php
    $this->pageTitle=Yii::app()->name . ' - Bestiario';

    $gumbudos = array(
        array('Gumbudo Criador', 'criador', 'comun', 'Es un Gumbudo que se dedica a cuidar del corral criando a los Gungubos, durante 24 horas.##Duplica el tiempo que tardan en perder vida los Gungubos (de media hora a 1 hora).'),
        array('Gumbudo Asaltante', 'asaltante', 'comun', 'Es un Gumbudo que realiza '.Yii::app()->config->getParam('gumbudoAsaltanteActions').' ataque/s a un corral enemigo aleatorio, cada 2 horas durante 12 horas.##Al crearse el jugador selecciona un arma (garras, colmillos, púas).##Si el Gumbudo Asaltante se enfrenta a un Gumbudo Guardián y empata o pierde, desistirá en su ataque hasta el siguiente ataque y cambiará de arma por la que le derrotó. Si gana, penetrará en el corral y acabará con un número de Gungubos de ['.Yii::app()->config->getParam('gumbudoAsaltanteMinMuertes').'-'.Yii::app()->config->getParam('gumbudoAsaltanteMaxMuertes').'].##Con una probabilidad del '.Yii::app()->config->getParam('gumbudoAsaltanteProbabilidadSanguinario').'% el Gumbudo Asaltante puede crearse con la característica <span class="tooltip">Sanguinario (2)::sanguinario::</span>.'),
        array('Gumbudo Guardián', 'guardian', 'comun', 'Es un Gumbudo que permanece en el corral 12 horas para defenderlo de ataques de otros Gungubos o Gumbudos. Puede detener '.Yii::app()->config->getParam('gumbudoGuardianActions').' ataque/s por hora.##Al crearse el jugador selecciona un arma (garras, colmillos, púas).##Si pierde un combate cambia de arma por la que le derrotó.##Con una probabilidad del '.Yii::app()->config->getParam('gumbudoGuardianProbabilidadAcorazado').'% el Gumbudo Guardián puede crearse con la característica <span class="tooltip">Acorazado (1)::acorazado::</span>.'),
        array('Gumbudo Nigromante', 'nigromante', 'achikhoria', 'Es un Gumbudo que durante 12 horas, cada 2 horas (6 ataques), intenta generar con un porcentaje de probabilidad del '.Yii::app()->config->getParam('gumbudoNigromanteProbabilidadZombie').'% un Gungubo Zombie por cada cadáver de tu cementerio. Hay un límite de '.Yii::app()->config->getParam('gumbudoNigromanteMaxZombies').' zombies creados por ataque.##Acto seguido tras levantar a los zombies los envía a atacar a un corral enemigo aleatorio.##Con una probabilidad del '.Yii::app()->config->getParam('gumbudoNigromanteProbabilidadColera').'% el Gungubo Zombie creado tendrá la característica <span class="tooltip">Cólera::colera::</span>.##<span class="tooltip">Consume cadáveres::consumeCadaveres::</span>.'),
        array('Gumbudo Pestilente', 'pestilente', 'achikhoria', 'Es un Gumbudo que durante 8 horas, cada hora (8 ataques), intenta colarse en un corral enemigo y propagar una enfermedad en el mismo.##Va desarmado, y si consigue penetrar en el corral superando a las defensas, tiene una probabilidad del '.Yii::app()->config->getParam('gumbudoPestilenteProbabilidadInfectar').'%  de infectarlo con <span class="tooltip">Enfermedad ('.Yii::app()->config->getParam('gumbudoPestilenteIntensidadEnfermedad').')::enfermedad::</span>.##Con una probabilidad del '.Yii::app()->config->getParam('gumbudoPestilenteProbabilidadFetido').'%  el Gumbudo Pestilente puede crearse con la característica <span class="tooltip">Fétido::fetido::</span>.'),
        array('Gumbudo Artificiero', 'artificiero', 'kafhe', 'Es un Gumbudo que durante 12 horas, cada 2 horas (6 ataques) intenta generar con una probabilidad del '.Yii::app()->config->getParam('gumbudoArtificieroProbabilidadBomba').'% un Gungubo Bomba por cada Gungubos asesinado de tu cementerio.##Hay un límite de '.Yii::app()->config->getParam('gumbudoArtificieroMaxBombas').' bombas que puede crear por ataque.##<span class="tooltip">Consume cadáveres::consumeCadaveres::</span>.'),
        array('Gumbudo de Asedio', 'asedio', 'kafhe', 'Es un Gumbudo que durante 8 horas, cada 2 horas (4 ataques) rellena a dos Gungubos de gasolina convirtiéndolos en Gungubos Molotov, y los catapulta a un corral enemigo aleatorio, provocando incendios.##Estos ataques no pueden detenerse con Gumbudos Guardianes. Remueve del juego 2 Gungubos de tu corral por cada ataque.##<span class="tooltip">Consume Gungubos::consumeGungubos::</span>.'),
        array('Gumbudo Hippie', 'hippie', 'libre', 'Es un Gumbudo que durante 12 horas, cada hora (12 intentos) intenta convencer, con un porcentaje del '.Yii::app()->config->getParam('gumbudoHippieProbabilidadActuar').'%, a los Gungubos hostiles de que cesen su actividad durante en ese ataque.##Impide un máximo de '.Yii::app()->config->getParam('gumbudoHippieActions').' ataque/s por hora.##Con una probabilidad del '.Yii::app()->config->getParam('gumbudoHippieProbabilidadHiperactivo').'% el Gumbudo Hippie puede crearse con la característica <span class="tooltip">Hiperactivo (1)::hiperactivo::</span>.'),

        array('Gungubo Zombie', 'zombie', 'gung', 'Es un Gungubo devuelto a la vida a partir de un cadáver del cementerio.##Los Gungubos Zombies atacan en grupo a un corral y son fácilmente eliminados por un Gumbudo Guardián ya que van desarmados, a menos que tengan la característica <span class="tooltip">Cólera::colera::</span>.##Al Gungubo que ataca le devora el cerebro y le convierte en zombie, por lo que no va al cementerio. Si falla el ataque, el Gungubo Zombie muere..##<span class="tooltip">Zombificar ('.Yii::app()->config->getParam('gunguboZombieProbabilidadZombificar').')::zombificar::</span>.'),
        array('Gungubo Bomba', 'bomba', 'gung', 'Es un Gungubo fabricado a partir de un cadáver del cementerio. Es la carcasa de un cadáver rellena de pólvora.##Los Gungubos Bomba estallan con una probabilidad del '.Yii::app()->config->getParam('gunguboBombaProbabilidadEstallar').'% en el corral enemigo matando a un número de gungubos ['.Yii::app()->config->getParam('gunguboBombaMinMuertes').'-'.Yii::app()->config->getParam('gunguboBombaMaxMuertes').']. Además provocan incendios.##<span class="tooltip">Incendiar ('.Yii::app()->config->getParam('gunguboBombaProbabilidadIncendiar').')::incendiar::</span>.'),
        array('Gungubo Molotov', 'molotov', 'gung', 'Es un Gungubo del corral relleno de gasolina que al estallar contra un corral puede provocar incendios.##<span class="tooltip">Incendiar ('.Yii::app()->config->getParam('gunguboMolotovProbabilidadIncendiar').')::incendiar::</span>.'),
    );

    //Este array está en el Bestiario y en la página de Corral. Actualizar sincronizadamente
    $caracteristicas = array(
        'sanguinario' => 'Sanguinario (n): el Gumbudo mata n veces más Gungubos con sus ataques (multiplica por n las muertes que provoca).',
        'acorazado' => 'Acorazado (n): el Gumbudo puede defender n ataques extra, además de los que defiende de base.',
        'colera' => 'Cólera: un Gungubo colérico no podrá ser bloqueado por un Gumbudo Guardián y penetrará en el corral.',
        'fetido' => 'Fétido: un Gungubo fétido infecta el corral atacado con un 100% de posibilidades.',
        'hiperactivo' => 'Hiperactivo (n): el Gumbudo puede actuar n veces extra.',
        'canibal' => 'Caníbal: el Gungubo caníbal se come a ['.Yii::app()->config->getParam('canibalMinComidos').'-'.Yii::app()->config->getParam('canibalMaxComidos').'] Gungubos de su propio corral para aumentar en el mismo número sus contadores de vida. Acto seguido pierde la característica Caníbal.',
        'incendiar' => 'Incendiar (p): el Gungubo tiene una probabilidad del p% de provocar quemadura a ['.Yii::app()->config->getParam('incendiarMinQuemados').'-'.Yii::app()->config->getParam('incendiarMaxQuemados').'] Gungubos del corral atacado.',
        'zombificar' => 'Zombificar (p): el Gungubo puede moder con una probabilidad ‘p’ a otro Gungubo y convertirlo en Zombie.',
        'sanador' => 'Sanador (p): el Gungubo añade n contadores de vida a todos los gungubos del corral. Acto seguido pierde la característica Sanador.',
        'enfermedad' => 'Enfermedad (n): la enfermedad afecta normalmente a todo el corral de Gungubos y hace que pierdan n contadores adicionales la siguiente vez que pierdan contadores de forma natural, tras lo cuál la enfermedad se cura. Si un Gungubo muere al perder un contador por enfermedad va al cementerio.',
        'quemadura' => 'Quemadura: un Gungubo con quemadura pierde un contador de vida cada 15 minutos hasta morir y puede propagar su quemadura a los gungubos cercanos. Cada vez que muere un Gungubo por quemadura, tiene un '.Yii::app()->config->getParam('quemaduraProbabilidadPropagacion').'% de propagar la quemadura a ['.Yii::app()->config->getParam('quemaduraMinQuemados').'-'.Yii::app()->config->getParam('quemaduraMaxQuemados').'] Gungubos cercanos. Los gungubos que mueren por perder un contador por quemadura van al cementerio.',
        'consumeCadaveres' => 'Consume cadáveres: el Gumbudo usa cadáveres del cementerio para realizar su misión. Si tiene éxito remueve el cadáver del cementerio, en caso de fracasar el cadáver permanece en el cementerio para un uso posterior.',
        'consumeGungubos' => 'Consume Gungubos: el Gumbudo usa Gungubos del corral para sus fines.',
    );
    ?>



    <div id="bestiary">
        <?php
        $index = 0;
        foreach ($gumbudos as $gumbudo) {
            $nombre = array_shift($gumbudo);
            $imagen = array_shift($gumbudo);
            $bando = array_shift($gumbudo);
            $descripcion = array_shift($gumbudo);
        ?>
        <article rel="<?php echo $nombre;?>">
            <header>
                <h2><?php echo $nombre; ?></h2>
            </header>
            <div class="image">
                <?php echo CHtml::image(Yii::app()->baseUrl."/images/bestiary/".$imagen.".png",$nombre); ?>
            </div>
            <p><?php
                $texto = str_replace('##', '<br /><br />', $descripcion);

                foreach($caracteristicas as $caracteristica=>$descripcion) {
                    $texto = str_replace('::'.$caracteristica.'::', '<span class="text">'.$descripcion.'</span>', $texto);
                }

                echo $texto;
            ?></p>
            <?php
            $bandos = "";
            if(strcmp($bando, "kafhe") == 0 || strcmp($bando, "comun") == 0) $bandos .= '<span class="bando" title="'.Yii::app()->params->sideNames['kafhe'].'">'.CHtml::image(Yii::app()->baseUrl."/images/modifiers/kafhe.png").'</span>';
            if(strcmp($bando, "achikhoria") == 0 || strcmp($bando, "comun") == 0) $bandos .= '<span class="bando" title="'.Yii::app()->params->sideNames['achikhoria'].'">'.CHtml::image(Yii::app()->baseUrl."/images/modifiers/achikhoria.png").'</span>';
            if(strcmp($bando, "libre") == 0 || strcmp($bando, "comun") == 0) $bandos .= '<span class="bando" title="'.Yii::app()->params->sideNames['libre'].'">'.CHtml::image(Yii::app()->baseUrl."/images/modifiers/libre.png").'</span>';
            echo '<p class="bandos">'.$bandos.'</p>';
            ?>
        </article>
        <?php
        $index++;
        if($index % 2 == 0 && $index > 0):?>
        <div class="clear"></div>
        <?php endif;
        } ?>
        <div class="clear"></div>
    </div>
</div>
