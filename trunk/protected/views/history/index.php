<div id="menuContent">
    <div id="battleResults">
		<h1 class="battle">Los mejores de todos los tiempos</h1>
		<ol>
		<?php
			foreach ($ranking as $user) {
				echo '<li><strong>'.Yii::app()->usertools->getAlias($user['user_id']).'</strong>, rango '.$user['rank'].' el '.$user['date'].'</li>';
			}
		?>
		</ol>
	
	
        <h1 class="battle">Histórico de batallas</h1>

        <div id="lastBattleResult">
            <h2>Última batalla</h2>
            <!-- LLAMADOR ANTERIOR -->
            <?php
                $kafheVictory = true;
                $battleEqual = $event->gungubos_kafhe == $event->gungubos_achikhoria;
                if($event->gungubos_kafhe < $event->gungubos_achikhoria) $kafheVictory = false;
                $battleResult = "(".$event->gungubos_kafhe." - ".$event->gungubos_achikhoria.")";
                if($battleEqual){
                    echo '<p id="battleResult">La batalla finalizo en igualdad de condiciones. '.$battleResult.'</p>';
                }elseif($kafheVictory){
                    echo '<p id="battleResult">La batalla finalizo con una victoria del <strong>bando de '.Yii::app()->params->sideNames["kafhe"].' '.$battleResult.'</strong>.</p>';
                }else{
                    echo '<p id="battleResult">La batalla finalizó con una victoria del <strong>bando de '.Yii::app()->params->sideNames["achikhoria"].' '.$battleResult.'</strong>.</p>';
                }
            ?>
            <p><?php
                    if($kafheVictory && $event->caller_side == "kafhe"){
                        echo 'Así fue como ';
                    }else{
                        echo 'Sin embargo ';
                    }
                ?>el destino quiso que fuese <strong><?php echo Yii::app()->usertools->getAlias($event->caller_id); ?></strong> (bando de <?php echo Yii::app()->params->sideNames[$event->caller_side]?>) el elegido para llamar.</p>
        </div>
        <div id="lastBattleStatusChart"></div>

        <script type="text/javascript">
            google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Bando', 'Gungubos'],
                    ['Achikhoria', <?php echo $event->gungubos_achikhoria; ?>],
                    ['Kafhe', <?php echo $event->gungubos_kafhe; ?>]

                ]);

                var options = {
                    chartArea:{width: '500',height: '350', top: 10, left: 10},
                    colors:['#673c7d','#f0cc33'],
                    fontName: 'Lato',
                    pieSliceText: 'number',
                    width: 500,
                    height:350
                };

                var chart = new google.visualization.PieChart(document.getElementById('lastBattleStatusChart'));
                chart.draw(data, options);
                resizeNavBar();
            }
        </script>

    <?php
        // PEDIDO DEL EVENTO ANTERIOR
        if ($orders !== null):
            $itos = $orders['itos'];
            $noitos = $orders['noitos'];
            $bebidas = $orders['bebidas'];
            $comidas = $orders['comidas'];
            ?>
            <h2>Último Pedido</h2>
            <div id="pedidoCentrado">
                <div class="tipoPedido">
                    <h2 class="pedido">pedidos ITO</h2>
                    <ul class="pedido">
                        <li><p class="bebida">bebidas</p>
                            <ul class="bebida">
                                <?php foreach($itos['bebidas'] as $id=>$cantidad): ?>
                                    <li><span class="numeroVeces"><?php echo $cantidad.'</span><span class="veces"> x </span>'.$bebidas[$id]; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li><p class="comida">comidas</p>
                            <ul class="comida">
                                <?php foreach($itos['comidas'] as $id=>$cantidad): ?>
                                    <li><span class="numeroVeces"><?php echo $cantidad.'</span><span class="veces"> x </span>'.$comidas[$id]; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="tipoPedido">
                    <h2 class="pedido">pedidos normales</h2>
                    <ul  class="pedido">
                        <li><p class="bebida">bebidas</p>
                            <ul class="bebida">
                                <?php foreach($noitos['bebidas'] as $id=>$cantidad): ?>
                                    <li><span class="numeroVeces"><?php echo $cantidad.'</span><span class="veces"> x </span>'.$bebidas[$id]; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li><p class="comida">comidas</p>
                            <ul class="comida">
                                <?php foreach($noitos['comidas'] as $id=>$cantidad): ?>
                                    <li><span class="numeroVeces"><?php echo $cantidad.'</span><span class="veces"> x </span>'.$comidas[$id]; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        <p class="clear"><em>Más datos próximamente.</em></p>

    </div>
</div>