<?php
/* @var $this HomeController */

$this->breadcrumbs=array(
	'Home',
);
?>

<?php
$flashMessages = Yii::app()->user->getFlashes();
if ($flashMessages) {
    echo '<div class="flashes">';
    foreach($flashMessages as $key => $message) {
        echo '<p><div class="flash-' . $key . '">' . $message . "</div></p>\n";
    }
    echo '</div>';
}
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<h2>Estadísticas de fama por Gumbudos</h2>

<div class="row">
    <div class="span2">
        <?php
        if (isset($files)) {
            $this->widget('bootstrap.widgets.TbMenu', array(
                'type'=>'list',
                'items'=>$files,
            ));
        }
        ?>
    </div>

    <div class="span6">
        <?php
            if(!isset($file)) {
                ?>
                <p>Selecciona un fichero de estadísticas.</p>
                <?php
            } else {
            ?>
                <div id="generalProbs">
                  <div id="avgFame"></div>
                </div>

                <script type="text/javascript">
                    google.load("visualization", "1", {packages:["corechart"]});
                    google.setOnLoadCallback(drawChart3);
                    function drawChart3() {
                        var data = google.visualization.arrayToDataTable([
                            ['Gumbudo', 'Fama media'],
                        <?php
                            foreach ($avgFame as $gumbudo=>$fame) {
                                echo '["'.$gumbudo.'",'.$fame.'],';
                            }

                        ?>
                        ]);

                        var options = {
                            title: 'Fama media por Gumbudo',
                            titleTextStyle: { fontName: 'Lato', fontSize: 20 },
                            chartArea:{width: 600,height: 400},
                            colors:['#bf3950','#ff8139','#f0cc33','#60b97f','#4f77c1','#673c7d','#ff2c61','#8f6255','#e2a30a','#00924a','#50cae6','#2a0e3d'],
                            fontName: 'Lato',
                            pieSliceText: 'percentage',
                            height: 500
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('avgFame'));
                        chart.draw(data, options);
                    }
                </script>
        <?php
            }

        ?>
    </div>

    <div class="span4">
        <?php if(isset($file)) { ?>
        <div id='table_summary'></div>

        <script type='text/javascript'>
            google.load('visualization', '1', {packages:['table']});
            google.setOnLoadCallback(drawTable);
            function drawTable() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Gumbudo (acciones)');
                data.addColumn('number', 'Fama Media');
                data.addColumn('number', 'Min Fama');
                data.addColumn('number', 'Máx Fama');
                data.addColumn('number', 'Suma Fama');
                data.addColumn('number', 'Fama Relativa');
                data.addRows([
                    ['Asaltante (<?php echo $totalGumbudos['Asaltante']; ?>)', <?php echo $avgFame['Asaltante']; ?>, <?php echo $minFame['Asaltante']; ?>, <?php echo $maxFame['Asaltante']; ?>, <?php echo $sumFame['Asaltante']; ?>, <?php echo relativeFame('Asaltante', $avgFame, $dataGumbudo); ?>],
                    ['Guardián (<?php echo $totalGumbudos['Guardián']; ?>)', <?php echo $avgFame['Guardián']; ?>, <?php echo $minFame['Guardián']; ?>, <?php echo $maxFame['Guardián']; ?>, <?php echo $sumFame['Guardián']; ?>, <?php echo relativeFame('Guardián', $avgFame, $dataGumbudo); ?>],
                    ['Artificiero (<?php echo $totalGumbudos['Artificiero']; ?>)', <?php echo $avgFame['Artificiero']; ?>, <?php echo $minFame['Artificiero']; ?>, <?php echo $maxFame['Artificiero']; ?>, <?php echo $sumFame['Artificiero']; ?>, <?php echo relativeFame('Artificiero', $avgFame, $dataGumbudo); ?>],
                    ['Asedio (<?php echo $totalGumbudos['Asedio']; ?>)', <?php echo $avgFame['Asedio']; ?>, <?php echo $minFame['Asedio']; ?>, <?php echo $maxFame['Asedio']; ?>, <?php echo $sumFame['Asedio']; ?>, <?php echo relativeFame('Asedio', $avgFame, $dataGumbudo); ?>],
                    ['Nigromante (<?php echo $totalGumbudos['Nigromante']; ?>)', <?php echo $avgFame['Nigromante']; ?>, <?php echo $minFame['Nigromante']; ?>, <?php echo $maxFame['Nigromante']; ?>, <?php echo $sumFame['Nigromante']; ?>, <?php echo relativeFame('Nigromante', $avgFame, $dataGumbudo); ?>],
                    ['Pestilente (<?php echo $totalGumbudos['Pestilente']; ?>)', <?php echo $avgFame['Pestilente']; ?>, <?php echo $minFame['Pestilente']; ?>, <?php echo $maxFame['Pestilente']; ?>, <?php echo $sumFame['Pestilente']; ?>, <?php echo relativeFame('Pestilente', $avgFame, $dataGumbudo); ?>]
                ]);

                var table = new google.visualization.Table(document.getElementById('table_summary'));
                table.draw(data, {});
            }
        </script>

        <br />
        <p><strong>Acciones:</strong> número de veces que ha actuado.</p>
        <p><strong>Fama media:</strong> media de la fama ganada por el Gumbudo por acción.</p>
        <p><strong>Min fama:</strong> mínimo de fama ganada en una acción.</p>
        <p><strong>Máx fama:</strong> máximo de fama ganada en una acción.</p>
        <p><strong>Suma fama:</strong> suma total de la fama ganada por el Gumbudo en todas sus acciones.</p>
        <p><strong>Fama relativa:</strong> media de la fama ganada por Gumbudo en relación a la cantidad de acciones que lleva a cabo a lo largo de su vida útil.</p>
        <?php } ?>
    </div>


</div>

<?php
    if (isset($acciones['Asaltante'])) {
?>
<div class="row">
    <div class="span2"></div>
    <div class="span10">
        <h3>Asaltante</h3>
        <div id="chart_div1" style="width: auto; height: 500px;"></div>

        <script type="text/javascript">
            //google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Ataques', 'Asaltante'],
                    <?php
                        $count = 1;
                        foreach ($acciones['Asaltante'] as $data) {
                            echo "[".$count.", ".$data['fame']."],";
                            $count++;
                        }
                    ?>
                ]);

                var options = {
                    title: 'Fama del Gumbudo en sus acciones',
                    colors:['#bf3950'],
                    hAxis: {title: 'Ataques',  titleTextStyle: {color: '#333'}, gridlines: {count:10}},
                    vAxis: {minValue: 0, title: 'Fama', gridlines: {count:-1}}
                };

                var chart = new google.visualization.AreaChart(document.getElementById('chart_div1'));
                chart.draw(data, options);
            }
        </script>
    </div>
</div>
<?php
    }
?>

<?php
    if (isset($acciones['Guardián'])) {
?>
<div class="row">
    <div class="span2"></div>
    <div class="span10">
        <h3>Guardián</h3>
        <div id="chart_div2" style="width: auto; height: 500px;"></div>

        <script type="text/javascript">
            //google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Acciones', 'Guardián'],
                    <?php
                        $count = 1;
                        foreach ($acciones['Guardián'] as $data) {
                            echo "[".$count.", ".$data['fame']."],";
                            $count++;
                        }
                    ?>
                ]);

                var options = {
                    title: 'Fama del Gumbudo en sus acciones',
                    colors:['#ff8139'],
                    hAxis: {title: 'Acciones',  titleTextStyle: {color: '#333'}, gridlines: {count:10}},
                    vAxis: {minValue: 0, title: 'Fama', gridlines: {count:-1}}
                };

                var chart = new google.visualization.AreaChart(document.getElementById('chart_div2'));
                chart.draw(data, options);
            }
        </script>
    </div>
</div>
<?php
    }
?>

<?php
    if (isset($acciones['Artificiero'])) {
?>
<div class="row">
    <div class="span2"></div>
    <div class="span10">
        <h3>Artificiero</h3>
        <div id="chart_div3" style="width: auto; height: 500px;"></div>

        <script type="text/javascript">
            //google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Ataques', 'Artificiero'],
                    <?php
                        $count = 1;
                        foreach ($acciones['Artificiero'] as $data) {
                            echo "[".$count.", ".$data['fame']."],";
                            $count++;
                        }
                    ?>
                ]);

                var options = {
                    title: 'Fama del Gumbudo en sus acciones',
                    colors:['#f0cc33'],
                    hAxis: {title: 'Ataques',  titleTextStyle: {color: '#333'}, gridlines: {count:10}},
                    vAxis: {minValue: 0, title: 'Fama', gridlines: {count:-1}}
                };

                var chart = new google.visualization.AreaChart(document.getElementById('chart_div3'));
                chart.draw(data, options);
            }
        </script>
    </div>
</div>
<?php
    }
?>

<?php
    if (isset($acciones['Asedio'])) {
?>
<div class="row">
    <div class="span2"></div>
    <div class="span10">
        <h3>Asedio</h3>
        <div id="chart_div4" style="width: auto; height: 500px;"></div>

        <script type="text/javascript">
            //google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Ataques', 'Asedio'],
                    <?php
                        $count = 1;
                        foreach ($acciones['Asedio'] as $data) {
                            echo "[".$count.", ".$data['fame']."],";
                            $count++;
                        }
                    ?>
                ]);

                var options = {
                    title: 'Fama del Gumbudo en sus acciones',
                    colors:['#60b97f'],
                    hAxis: {title: 'Ataques',  titleTextStyle: {color: '#333'}, gridlines: {count:10}},
                    vAxis: {minValue: 0, title: 'Fama', gridlines: {count:-1}}
                };

                var chart = new google.visualization.AreaChart(document.getElementById('chart_div4'));
                chart.draw(data, options);
            }
        </script>
    </div>
</div>
<?php
    }
?>

<?php
    if (isset($acciones['Nigromante'])) {
?>
<div class="row">
    <div class="span2"></div>
    <div class="span10">
        <h3>Nigromante</h3>
        <div id="chart_div5" style="width: auto; height: 500px;"></div>

        <script type="text/javascript">
            //google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Ataques', 'Nigromante'],
                    <?php
                        $count = 1;
                        foreach ($acciones['Nigromante'] as $data) {
                            echo "[".$count.", ".$data['fame']."],";
                            $count++;
                        }
                    ?>
                ]);

                var options = {
                    title: 'Fama del Gumbudo en sus acciones',
                    colors:['#4f77c1'],
                    hAxis: {title: 'Ataques',  titleTextStyle: {color: '#333'}, gridlines: {count:10}},
                    vAxis: {minValue: 0, title: 'Fama', gridlines: {count:-1}}
                };

                var chart = new google.visualization.AreaChart(document.getElementById('chart_div5'));
                chart.draw(data, options);
            }
        </script>
    </div>
</div>
<?php
    }
?>

<?php
    if (isset($acciones['Pestilente'])) {
?>
<div class="row">
    <div class="span2"></div>
    <div class="span10">
        <h3>Pestilente</h3>
        <div id="chart_div6" style="width: auto; height: 500px;"></div>

        <script type="text/javascript">
            //google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Ataques', 'Pestilente'],
                    <?php
                        $count = 1;
                        foreach ($acciones['Pestilente'] as $data) {
                            echo "[".$count.", ".$data['fame']."],";
                            $count++;
                        }
                    ?>
                ]);

                var options = {
                    title: 'Fama del Gumbudo en sus acciones',
                    colors:['#673c7d'],
                    hAxis: {title: 'Ataques',  titleTextStyle: {color: '#333'}, gridlines: {count:10}},
                    vAxis: {minValue: 0, title: 'Fama', gridlines: {count:-1}}
                };

                var chart = new google.visualization.AreaChart(document.getElementById('chart_div6'));
                chart.draw(data, options);
            }
        </script>
    </div>
</div>
<?php
    }
?>

<?php if(isset($file)) { ?>
<!-- Button to trigger modal -->
<div class="row">
    <div class="span4"></div>
    <div class="span4"><p><a href="#myModal" role="button" class="btn btn-large btn-info" data-toggle="modal">Tabla de valores para Excel</a></p></div>
    <div class="span4"></div>
</div>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Copiar y pegar en Excel</h3>
    </div>
    <div class="modal-body">
        <table>
            <tr><th>ID</th><th>Gumbudo</th><th>Fama</th><th>Timestamp</th></tr>
            <?php
                if (count($lines)>0) {
                    foreach ($lines as $line) {
                        list($id, $name, $fame, $time) = explode(',', $line);
                        echo '<tr><td>'.$id.'</td><td>'.$name.'</td><td>'.$fame.'</td><td>'.$time.'</td></tr>';
                    }
                }
            ?>
        </table>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
    </div>
</div>

<?php
    }
?>


<?php
    function relativeFame($name, $average, $data) {
        $rate = $data[$name]['rate'];
        if ($rate == 0 || $rate == NULL) $rate = 1;

        return round($average[$name]*($data[$name]['duration']/$rate),2);
    }
?>