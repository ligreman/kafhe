<div id="menuContent">

    <h1 class="battle">Histórico de batallas</h1>


    <!-- LLAMADOR ANTERIOR -->
    <p>Llamador del evento anterior: <?php echo Yii::app()->usertools->getAlias($event->caller_id); ?></p>


<?php
    // PEDIDO DEL EVENTO ANTERIOR
    if ($orders !== null):
        $itos = $orders['itos'];
        $noitos = $orders['noitos'];
        $bebidas = $orders['bebidas'];
        $comidas = $orders['comidas'];
        ?>
        <p>Pedido del evento anterior:</p>
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

</div>