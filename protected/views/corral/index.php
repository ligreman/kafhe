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

/******************************************************************/
// Preparo los gungubos
if ($gungubos!==null):
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




?>
<h1 class="corral">Tus Gungubos</h1>
    <div id="">
        <div id="corralNumeric"></div>
    </div>

    <script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart);
        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Estado', 'Cantidad', {role:'style'}, {role:'annotation'}],
                <?php
                    //echo '[0,0,0,0,0,0],';
                    echo'["Normal", '.count($normales).', "#00924a", "'.count($normales).'"],';
                    echo'["Quemadura", '.count($quemados).', "#bf3950", "'.count($quemados).'"],';
                    echo'["Enfermo", '.count($enfermos).', "#8f6255", "'.count($enfermos).'"],';
                    echo'["Cementerio", '.count($cementerio).', "#363636", "'.count($cementerio).'"]';
                ?>

            ]);

            var options = {
                title: 'Tu corral',
                titleTextStyle: { fontName: 'Lato', fontSize: 20 },
                height: 400,
                hAxis: { textStyle: {bold: true} },
                vAxis: { title:'Gungubos', maxValue:50, gridlines: { count: 11 } },
                bar: {groupWidth: "80%"},
                legend: {position:'none'}
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('corralNumeric'));
            chart.draw(data, options);
        }
    </script>



    <div id="">
        <div id="corralScatter"></div>
    </div>

    <script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart);
        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['ID', 'Normal', 'Quemados', 'Enfermos', 'Cementerio', 'Dummy'],
                <?php
                    echo '[0,0,0,0,0,0],';

                    $counter = 1;
                    foreach ($normales as $gungubo) {
                        echo '['.$counter.','.$gungubo->health.', null, null, null, null],';
                        $counter++;
                    }

                    foreach ($quemados as $gungubo) {
                        echo '['.$counter.', null, '.$gungubo->health.', null, null, null],';
                        $counter++;
                    }

                    foreach ($enfermos as $gungubo) {
                        echo '['.$counter.', null, null, '.$gungubo->health.', null, null],';
                        $counter++;
                    }

                    foreach ($cementerio as $gungubo) {
                        echo '['.$counter.', null, null, null, 0, null],';
                        $counter++;
                    }
                ?>

            ]);

            var options = {
                title: 'Distribución de tus Gungubos',
                titleTextStyle: { fontName: 'Lato', fontSize: 20 },
                height: 400,
                hAxis: { minValue: 0, maxValue: 50, gridlines: { count: 11 }},
                vAxis: {title: 'Vida del Gungubo', minValue: 0, maxValue: 10, ticks: [0,1,2,3,4,5,6,7,8,9,10] },
                tooltip: {trigger:'none'},
                series: [
                    {color: '#00924a'}, //normal
                    {color: '#bf3950'}, //quemadura
                    {color: '#8f6255'}, //enfermo
                    {color: '#363636'}, //cementerio
                    {color: '#FFFFFF', visibleInLegend: false} //valor dummy
                ]
            };

            var chart = new google.visualization.ScatterChart(document.getElementById('corralScatter'));
            chart.draw(data, options);
        }
    </script>

<?php
endif;
?>


<div id="misGumbudos">
<h1 class="corral">Tus Gumbudos</h1>
<table>
    <thead><tr>
        <th>Gumbudo</th>
        <th>Vivo hasta</th>
        <th>Características</th>
        <th>Otros</th>
    </tr></thead>
<?php
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

    foreach($gumbudos as $gumbudo) {
        echo '<tr>';

        //Icono
        echo '<td class="icon" title="Gumbudo '.Yii::app()->params->gumbudoClassNames[$gumbudo->class].'">'.CHtml::image(Yii::app()->baseUrl."/images/bestiary/".$gumbudo->class.".png",$gumbudo->class).'</td>';

        $trait = '';
        if ($gumbudo->trait !== null) {
            $trait = '<span class="tooltip">'.Yii::app()->params->traitNames[$gumbudo->trait];

            if ($gumbudo->trait_value !== null)
                $trait .= ' ('.$gumbudo->trait_value.')';

            $trait .= '<span class="text">'.$caracteristicas[$gumbudo->trait].'</span></span>';
        }

        $otro = '';
        if ($gumbudo->weapon !== null){
            $otro .= '<p>Arma: '.Yii::app()->params->gumbudoWeaponNames[$gumbudo->weapon].'</p>';
        }

        if ($gumbudo->class==Yii::app()->params->gumbudoClassGuardian) {
            $otro .= '<p><abbr title="Veces que puede defender esta hora">Acciones</abbr>: '.$gumbudo->actions.'</p>';
        }


        //Datos
        $rip = $gumbudo->ripdate;
        echo '<td>'.date('H:i \d\e\l d/m', strtotime($rip)).'</td>';
        echo '<td>'.$trait.'</td>';
        echo '<td class="otros">'.$otro.'</td>';

        echo '</tr>';
    }

?>
</table>
</div>


<?php
/* @var $this CorralController */
/*
$users = User::model()->findAll();
foreach ($users as $user) {
    $ggc = Gungubo::model()->count(array('condition'=>'event_id='.Yii::app()->event->id.' AND owner_id='.$user->id.' AND location="corral"'));
    $ggm = Gungubo::model()->count(array('condition'=>'event_id='.Yii::app()->event->id.' AND owner_id='.$user->id.' AND location="cementerio"'));
    $gb = Gumbudo::model()->count(array('condition'=>'event_id='.Yii::app()->event->id.' AND owner_id='.$user->id));

    echo "<br>".$user->username;
    echo "<br>&nbsp;&nbsp;Gungubos: ".$ggc.' en corral; '.$ggm.' en cementerio';
    echo "<br>&nbsp;&nbsp;Gumbudos: ".$gb;
    echo "<br>";
}*/
?>

    <br class="clear" />
</div>