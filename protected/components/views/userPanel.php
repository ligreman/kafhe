<div id="upContent">
    <div id="mainUserBlock">
        <p id="user">

            <span id="userName"><?php echo $user->alias; ?></span>
            <span id="sideStatus">
                <?php
                //Modificadores
                if (Yii::app()->user->checkAccess('Usuario')) {
                    //Bando y estado
                    if(Yii::app()->user->side ===  "kafhe") {
                        $side = "Kafheíta";
                    } elseif(Yii::app()->user->side ===  "achikhoria") {
                        $side = "Renunciante";
                    } else {
                        $side = "Iluminado";
                    }
                    echo CHtml::image(Yii::app()->baseUrl."/images/modifiers/".$user->side.".png",Yii::app()->params->sideNames[$user->side],array('class' => 'modifier','title' => $side));
                    echo CHtml::image(Yii::app()->baseUrl."/images/modifiers/status".$user->status.".png",Yii::app()->params->userStatusNames[$user->status],array('class' => 'modifier','title' => ''.Yii::app()->params->userStatusNames[$user->status]));
                }
                ?>
            </span>
        </p>
        <p class="dato">
            <span class="concepto">rango</span>
            <span class="numero"><?php echo $user->rank; ?></span>
        </p>

        <p class="dato">
            <span class="numero"><?php echo $user->ptos_relanzamiento; ?></span>
          <span class="concepto"><abbr title="lágrimas de gungubo">lágrimas</abbr></span>
        </p>

        <p class="dato">
            <span class="numero"><?php echo $user->sugarcubes; ?></span>
            <span class="concepto">azucarillos</span>
        </p>

        <p class="dato" title="Te faltan <?php echo (Yii::app()->config->getParam('maxExperienciaUsuario')-Yii::app()->currentUser->experience); ?> puntos">
            <?php $pExp = floor(100*(Yii::app()->currentUser->experience/Yii::app()->config->getParam('maxExperienciaUsuario'))); ?>
            <span class="numero"><?php echo $pExp; ?>%</span>
            <span class="concepto">experiencia</span>
        </p>
        <p id="modificadores">
                <?php
                //Modificadores
                if (Yii::app()->user->checkAccess('Usuario')) {
                    //Modificadores de habilidades
                    foreach($modifiers as $modifier) {
                        if($modifier->duration_type=='horas') {
                            $duration = $modifier->duration * 60 * 60; //en segundos
                            $duration = (strtotime($modifier->timestamp) + $duration) - time();
                          //$duration = gmdate("H:i:s", $duration);
                          $duration = str_pad(floor($duration/3600), 2, '0', STR_PAD_LEFT).':'.str_pad(($duration/60)%60, 2, '0', STR_PAD_LEFT).':'.str_pad($duration%60, 2, '0', STR_PAD_LEFT);
                            $duration_type = $modifier->duration_type;
                        } else {
                            $duration = $modifier->duration;
                            $duration_type = $modifier->duration_type;
                        }

                        $title = ucfirst($modifier->keyword).': '.$duration.' '.$duration_type;

                        if($modifier->value != null) $title .= ' ('.$modifier->value.')';

                        echo CHtml::image(Yii::app()->baseUrl."/images/modifiers/".$modifier->keyword.".png",$modifier->keyword,array('class' => 'modifier','title' => $title));
                    }
                }
                ?>
            </p>
    </div>
</div>
   