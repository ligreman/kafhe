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
    <div id="corralNumeric"></div>
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
                title: 'El mundo',
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

    <p>Lista de Gumbudos:</p>
    <div id="corralGumbudos"></div>
    <?php
    $index = 0;
    if (count($gumbudos) > 0) {
        $current_class = $gumbudos[$index]->class;
    }?>
    <script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart);
        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Estado', 'Cantidad'],
                
                <?php
                    while(count($gumbudos) > $index){
                        $count = 0;
                        while(count($gumbudos) > $index && strcmp($gumbudos[$index]->class,$current_class) == 0){
                            $count++;
                            $index++;
                        }
                        echo'["Gumbudo '.$current_class.'", '.$count.']';
                        if (count($gumbudos) > $index){
                            $current_class = $gumbudos[$index]->class;
                            echo ',';
                        }
                    }
                ?>

            ]);

            var options = {
                title: 'El mundo',
                titleTextStyle: { fontName: 'Lato', fontSize: 20 },
                height: 400,
                colors:['#00924a'],
                hAxis: { textStyle: {bold: true} },
                vAxis: { title:'Gumbudos'},
                legend: {position:'none'}
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('corralGumbudos'));
            chart.draw(data, options);
        }
    </script>
    <div class="clear"></div>
</div>