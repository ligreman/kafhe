<div id="menuContent" class="paddedContent">
    <h1 class="battle">Pedido</h1>

    <?php
        $itos = $orders['itos'];
        $noitos = $orders['noitos'];
        $bebidas = $orders['bebidas'];
        $comidas = $orders['comidas'];
    ?>
    <p>Omelettus ha decidido que seas tú el que haga el pedido de hoy. Prepárate para llamar a: <?php echo Yii::app()->config->getParam('informacionCafeteria'); ?></p>
    <div id="pedidoCentrado">
        <div class="tipoPedido">
            <h2 class="pedido">pedidos ITO</h2>
            <ul class="pedido">
                <li><p class="bebida">bebidas</p>
                    <ul class="bebida">
                        <?php foreach($itos['bebidas'] as $id=>$cantidad): ?>
                            <li><span class="numeroVeces"><?php
                                echo $cantidad.'</span><span class="veces"> x </span>'.$bebidas[$id];
                                $img = CHtml::image(Yii::app()->baseUrl.'/images/tick.png','Oído Cocina',array('title' => 'Oído Cocina')    );
                                echo CHtml::link($img,'#');
                                ?></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li><p class="comida">comidas</p>
                    <ul class="comida">
                        <?php foreach($itos['comidas'] as $id=>$cantidad): ?>
                            <li><span class="numeroVeces"><?php echo $cantidad.'</span><span class="veces"> x </span>'.$comidas[$id];
                            $img = CHtml::image(Yii::app()->baseUrl.'/images/tick.png','Oído Cocina',array('title' => 'Oído Cocina'));
                            echo CHtml::link($img,'#');
                            ?></li>
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
                            <li><span class="numeroVeces"><?php echo $cantidad.'</span><span class="veces"> x </span>'.$bebidas[$id];
                            $img = CHtml::image(Yii::app()->baseUrl.'/images/tick.png','Oído Cocina',array('title' => 'Oído Cocina'));
                            echo CHtml::link($img,'#');
                            ?></li>
                            <?php endforeach; ?>
                    </ul>
                </li>
                <li><p class="comida">comidas</p>
                    <ul class="comida">
                        <?php foreach($noitos['comidas'] as $id=>$cantidad): ?>
                            <li><span class="numeroVeces"><?php echo $cantidad.'</span><span class="veces"> x </span>'.$comidas[$id];
                            $img = CHtml::image(Yii::app()->baseUrl.'/images/tick.png','Oído Cocina',array('title' => 'Oído Cocina'));
                            echo CHtml::link($img,'#');
                            ?></li>
                            <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="clear"></div>
    <h2 class="pedidoIndividual">Pedidos individuales</h2>
    <?php if ($individual_orders!==null): ?>
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
    <?php endif; //individual order ?>

    <?php /*if (count($individual_orders) > 0): ?>
        <?php
            $bebidas = $orders['bebidas'];
            $comidas = $orders['comidas'];
        ?>
        <ul class="individualOrder">
        <?php foreach($individual_orders as $order): ?>
            <li>
                <strong><?php  echo Yii::app()->usertools->getAlias($order->user_id);?></strong>: 
                <em><?php  echo $bebidas[$order->drink_id];?></em> y 
                <em><?php  echo $comidas[$order->meal_id];?></em>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif;*/ ?>
</div>