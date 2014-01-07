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
/* @var $this CorralController */

    $users = User::model()->findAll();
    foreach ($users as $user) {
        $ggc = Gungubo::model()->count(array('condition'=>'event_id='.Yii::app()->event->id.' AND owner_id='.$user->id.' AND location="corral"'));
        $ggm = Gungubo::model()->count(array('condition'=>'event_id='.Yii::app()->event->id.' AND owner_id='.$user->id.' AND location="cementerio"'));
        $gb = Gumbudo::model()->count(array('condition'=>'event_id='.Yii::app()->event->id.' AND owner_id='.$user->id));

        echo "<br>".$user->username;
        echo "<br>&nbsp;&nbsp;Gungubos: ".$ggc.' en corral; '.$ggm.' en cementerio';
        echo "<br>&nbsp;&nbsp;Gumbudos: ".$gb;
        echo "<br>";
    }


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
                    {color: '#00C24a'}, //normal
                    {color: '#FF2300'}, //quemadura
                    {color: '#8F6032'}, //enfermo
                    {color: '#000000'}, //cementerio
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




    <br class="clear" />
</div>