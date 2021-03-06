<div id="menuContent">
    <?php
    $flashMessages = Yii::app()->user->getFlashes();
    if ($flashMessages) {
        echo '<ul class="flashes">';
        foreach($flashMessages as $key => $message) {
            echo '<li><div class="flash-' . $key . '">' . $message . "</div></li>\n";
        }
        echo '</ul>';
    }
    ?>

    <?php if(!empty($ranking)): ?>
    <div id="rankRanking">
        <h1 class="battle">Los mejores de todos los tiempos</h1>
        <ol>
            <?php
            $pos = 1;
            foreach ($ranking as $user) {
                echo '<li id="rankingPos'.$pos.'" title="'.date("d/m/Y", strtotime($user['date'])).'"><strong>Rango '.Yii::app()->usertools->roman_numerals($user['rank']).'</strong><em>'.Yii::app()->usertools->getAlias($user['user_id']).'</em></li>';
                $pos++;
            }
            ?>
        </ol>
    </div>
    <?php endif;?>
    <div id="battleResults">
		<h1 class="battle">Histórico de batallas</h1>

		<?php if ($event!==null): ?>

        <div id="lastBattleResult">
            <h2>Última batalla</h2>
            <!-- LLAMADOR ANTERIOR -->
            <?php
                $kafheVictory = true;
                $battleEqual = $event->fame_kafhe == $event->fame_achikhoria;
                if($event->fame_kafhe < $event->fame_achikhoria) $kafheVictory = false;
                $battleResult = "(".$event->fame_kafhe." - ".$event->fame_achikhoria.")";
                if($battleEqual){
                    echo '<p id="battleResult">La batalla finalizó en igualdad de condiciones. '.$battleResult.'</p>';
                }elseif($kafheVictory){
                    echo '<p id="battleResult">La batalla finalizó con una victoria de los <strong>Kafheitas '.$battleResult.'</strong>.</p>';
                }else{
                    echo '<p id="battleResult">La batalla finalizó con una victoria de los <strong>Renunciantes '.$battleResult.'</strong>.</p>';
                }
            ?>
            <p><?php
                    if(($kafheVictory && $event->caller_side == "kafhe") || (!$kafheVictory && $event->caller_side != "kafhe")){
                        echo 'Sin embargo ';
                    }else{
                        echo 'Así fue como ';
                    }
                ?>el destino quiso que fuese <strong><?php echo Yii::app()->usertools->getAlias($event->caller_id); ?></strong> (<?php if($event->caller_side ===  "kafhe") echo "Kafheita"; else echo "Renunciante";?>) el elegido para llamar.</p>
        </div>
        <div id="lastBattleStatusChart"></div>

        <script type="text/javascript">
            google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Bando', 'Gungubos'],
                    ['Renunciantes', <?php echo $event->fame_achikhoria; ?>],
                    ['Kafheitas', <?php echo $event->fame_kafhe; ?>]

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

                    <div class="tipoPedido borderRight">
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

                    <?php if ($individual_orders!==null): ?>
                    <div class="tipoPedido">
                        <h2 class="pedido">pedidos individuales</h2>
                        <?php
                        //$bebidas = $individual_orders['bebidas'];
                        //$comidas = $individual_orders['comidas'];
                        ?>
                        <ul class="individualOrder">
                            <?php foreach($individual_orders as $order): ?>
                                <li>
                                    <strong><?php  echo Yii::app()->usertools->getAlias($order->user_id);?></strong>:
                                    <?php if ($order->ito) echo "ITO de ";?>
                                    <?php if (!empty($order->drink_id)) echo $bebidas[$order->drink_id];?>
                                    <?php if (!empty($order->drink_id) && !empty($order->meal_id)) echo " y ";?>
                                    <?php if (!empty($order->meal_id)) echo $comidas[$order->meal_id];?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; //individual order ?>
                </div>


            <div class="clear"></div>

            <?php endif; //orders ?>

        <?php endif; //event ?>

        <div class="clear"></div>

        <p class="clear"><em>Más datos próximamente.</em></p>

    </div>
</div>