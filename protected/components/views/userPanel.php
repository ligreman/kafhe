<?php

define("MAXTUESTE", Yii::app()->config->getParam('maxTuesteUsuario'));

?>
<div id="content">
    <p id="user">

        <span id="userName"><?php echo $user->alias?></span>
        <span id="modificadores">
            <?php
                //Modificadores (provisional)
                if (Yii::app()->user->checkAccess('Usuario')) {
                    foreach(Yii::app()->usertools->modifiers as $modifier) {
                        echo ' '.$modifier->keyword.', ';
                    }
                }
            ?>
        </span>
    </p>
    <div id="energia">
        <div id="tuesteRetueste">
            <span id="tueste" class="w<?php echo floor(($user->ptos_tueste/MAXTUESTE)*100); ?>">
                <?php if($user->ptos_tueste > 0):?>
                    <span class="pin">
                        <span class="title"><?php echo $user->ptos_tueste; ?> puntos de tueste</span>
                    </span>
                <?php endif; ?>
            </span>
            <span id="retueste" class="w<?php echo floor(($user->ptos_retueste/MAXTUESTE)*100); ?>">
                <?php if($user->ptos_retueste > 0):?>
                    <span class="pin">
                        <span class="title"><?php echo $user->ptos_retueste; ?> puntos de retueste</span>
                    </span>
                <?php endif; ?>
            </span>
        </div>
    </div>
    <p class="dato">
        <span class="numero"><?php echo $user->rank; ?></span>
        <span class="concepto">escaqueos</span>
    </p>

    <p class="dato">
        <span class="numero"><?php echo $user->tostolares; ?></span>
        <span class="concepto">tostólares</span>
    </p>

    <p class="dato">
        <span class="numero">3</span>
        <span class="concepto">cofres</span>
    </p>

    <p class="dato">
        <span class="numero"><?php echo $user->azucarillos; ?></span>
        <span class="concepto">azucarillos</span>
    </p>

    <p class="dato">
        <span class="numero"><?php echo $user->ptos_relanzamiento; ?></span>
        <span class="concepto">p. relance</span>
    </p>
</div>
<div id="experiencia">
    <span id="xp" class="w50">
        <span class="pin">
                <span class="title">Faltan 300 puntos de experiencia</span>
        </span>
    </span>
</div>