
<div id="energia">
    <?php
        $toastPerInterval = Yii::app()->tueste->getTuesteRegenerado($user);
        $intervalSeconds = Yii::app()->config->getParam('tiempoRegeneracionTueste');
        $toastPerHour = (3600 / $intervalSeconds) * $toastPerInterval;
    ?>
    <div id="tuesteRetueste">
        <span id="tueste" class="w<?php echo floor(($user->ptos_tueste/$maxTueste)*100); ?>">
            <?php if($user->ptos_tueste > 0):?>
                <span class="pin">
                    <span class="title"><?php echo $user->ptos_tueste; ?> puntos de tueste (<?php echo $toastPerHour;?> PT/h)</span>
                </span>
            <?php endif; ?>
        </span>
        <?php if($user->ptos_retueste > 0):?>
            <span id="retueste" class="w<?php echo floor(($user->ptos_retueste/$maxTueste)*100); ?>">
                <span class="pin">
                    <span class="title"><?php echo $user->ptos_retueste; ?> puntos de retueste</span>
                </span>
            </span>
        <?php endif; ?>
    </div>
</div>
<?php
//Validador de habilidades
$validator = new SkillValidator;
$prevCategory = '';
?>
<div id="skillsPanel">
    <ul>
        <?php foreach($skills as $skill):
            if ($prevCategory=='') $prevCategory = $skill->category;
            elseif ($prevCategory!=$skill->category) {
                echo '<li class="separator"></li>';
                $prevCategory = $skill->category;
            }

            $execCode = $validator->canExecute($skill);

            //Se muestra siempre que el bando, estado del jugador, estado del desayuno o talentos adquiridos no sean un requisito.
            if ($execCode < 3 || $execCode == 8): ?>
                <li><?php
                    $class = 'skillImage';
                    if($execCode != 1) $class .= " grayScale";
                    $img = CHtml::image(Yii::app()->baseUrl."/images/skills/".$skill->keyword.".png",$skill->keyword, array('class' => $class));
                    echo CHtml::link($img, null, array('class' => 'skillLink', 'data-skill'=>$skill->id,'title' => $skill->name.': '.str_replace('<br />', ' ', $skill->description)));
                    ?>
                    <section class="skillDescriptionIndividual" data-skill="<?php echo $skill->id; ?>">
                        <div class="sdcontent">
                            <h1><span><?php echo $skill->name; ?></span><?php echo CHtml::image(Yii::app()->baseUrl."/images/skills/".$skill->keyword.".png",$skill->keyword, array('class' => $class)); ?></h1>
                            <p class="skillDesc"><?php echo $skill->description; ?></p>
                            <?php if($execCode == 2):?>
                                <p class="mensajeDesactivado">No tienes suficiente tueste, retueste, tostólares, gungubos o lágrimas de gungubo para pagar el coste de la habilidad.</p>
                            <?php endif; ?>
                            <?php if($execCode == 8): ?>
                                <p class="mensajeDesactivado">Hay modificadores que te impiden ejecutar la habilidad.</p>
                            <?php endif; ?>
                            <dl>
                                <dt>Coste: </dt>
                                <dd><?php
                                    $textoCoste = array();
                                    if($skill->cost_tueste!==null && $skill->cost_tueste > 0) {
                                        $costeFinal = Yii::app()->skill->calculateCostTueste($skill);
                                        $txtt = ' '.$skill->cost_tueste;
                                        if ($costeFinal > $skill->cost_tueste)
                                            $txtt .= ' <span title="Tueste extra por sobrecarga, etc." class="rojo">+ '.($costeFinal - $skill->cost_tueste).'</span> = '.$costeFinal;

                                        $txtt .= ' (<span class="col_tueste">tueste</span>)';
                                        $textoCoste[] = $txtt;
                                    }
                                    if($skill->cost_retueste!==null && $skill->cost_retueste > 0) $textoCoste[] = ' '.$skill->cost_retueste.' (<span class="col_retueste">retueste</span>)';
                                    if($skill->cost_relanzamiento!==null && $skill->cost_relanzamiento > 0) $textoCoste[] = ' '.$skill->cost_relanzamiento.' (<span class="col_lagrima">lágrimas</span>)';
                                    if($skill->cost_tostolares!==null && $skill->cost_tostolares > 0) $textoCoste[] = ' '.$skill->cost_tostolares.' (<span class="col_tostolar">tostólares</span>)';
                                    if($skill->cost_gungubos!==null && $skill->cost_gungubos > 0) $textoCoste[] = ' '.$skill->cost_gungubos.' (<span class="col_gungubo">gungubos</span>)';

                                    if (empty($textoCoste)) $textoCoste[] = '0';
                                    echo implode(' +',$textoCoste);

                                    ?></dd>
                                <dt>Probabilidad de Crítico:</dt>
                                <dd><?php echo $skill->critic; ?></dd>
                                <dt>Probabilidad de Pifia:</dt>
                                <dd><?php echo $skill->fail; ?></dd>

                                <?php if($execCode == 1): ?>

                                <?php if($skill->require_target_user==true): ?>

                                    <dt>Objetivo</dt>
                                    <dd class="targetList">
                                        <ul>
                                            <?php
                                            //Objetivo concreto, de todos los bandos o de uno concreto según require_target_side
                                            foreach($targets as $target){
                                                if($skill->require_target_side===null || $target->side == $skill->require_target_side){ ?>
                                                    <li class="<?php echo $target->side;?>" target_id="<?php echo $target->id;?>"><?php echo $target->alias;?></li>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </dd>

                                <?php elseif($skill->require_target_side!==null && $skill->require_target_side!==''):
                                    //El objetivo será un bando u el otro
                                    ?>

                                    <dt>Objetivo</dt>
                                    <dd class="targetList">
                                        <ul>
                                            <li class="kafhe" target_id="kafhe">Kafheítas</li>
                                            <li class="achikhoria" target_id="achikhoria">Renunciantes</li>
                                        </ul>
                                    </dd>

                                <?php elseif($skill->keyword==Yii::app()->params->skillGumbudoAsaltante || $skill->keyword==Yii::app()->params->skillGumbudoGuardian):
                                    //Armnas de gungubos
                                    ?>

                                    <dt>Armas</dt>
                                    <dd class="weaponList">
                                        <ul>
                                            <li class="" weapon="<?php echo Yii::app()->params->gumbudoWeapon1; ?>"><a href="#"><?php echo Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon1]; ?></a></li>
                                            <li class="" weapon="<?php echo Yii::app()->params->gumbudoWeapon2; ?>"><a href="#"><?php echo Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon2]; ?></a></li>
                                            <li class="" weapon="<?php echo Yii::app()->params->gumbudoWeapon3; ?>"><a href="#"><?php echo Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon3]; ?></a></li>
                                        </ul>
                                    </dd>

                                <?php endif; ?>
                            </dl>
                            <p class="skillButtons">
                                <?php echo CHtml::link('Aceptar', Yii::app()->createUrl('skill/execute', array('skill_id'=>$skill->id)), array('class'=>'btn btncommon acceptButton'));?>
                                <?php echo CHtml::link('Cancelar', null, array('class' => 'btn cancelButton'));?>
                            </p>
                        <?php else: ?>
                            </dl>
                            <p class="skillButtons centerContainer">
                                <?php echo CHtml::link('Cerrar', null, array('class' => 'btn cancelButton'));?>
                            </p>
                        <?php endif; //Fin de if de execCode 1 para mostrar objetivo y botones?>
                        </div>
                    </section>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
<section class="skillDescription">
</section>