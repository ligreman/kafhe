<?php

//$maxTueste = Yii::app()->config->getParam('maxTuesteUsuario');
//$skillsHidden = isset(Yii::app()->request->cookies['skillsHidden']) ? Yii::app()->request->cookies['skillsHidden']->value : '1';

?>
<div id="upContent">
    <div id="mainUserBlock">
        <p id="user">

            <span id="userName"><?php echo $user->alias; ?></span>
            <span id="modificadores">
                <?php
                //Modificadores (provisional)
                echo CHtml::image(Yii::app()->baseUrl."/images/modifiers/".$user->side.".png",$user->side,array('class' => 'modifier','title' => 'Perteneces al bando de '.Yii::app()->params->sideNames[$user->side]));
                if (Yii::app()->user->checkAccess('Usuario')) {
                    foreach(Yii::app()->usertools->modifiers as $modifier) {
                        if($modifier->duration_type=='horas') {
                            $duration = $modifier->duration * 60 * 60; //en segundos
                            $duration = (strtotime($modifier->timestamp) + $duration) - time();
                            $duration = gmdate("H:i:s", $duration);
                            $duration_type = $modifier->duration_type;
                        } else {
                            $duration = $modifier->duration;
                            $duration_type = $modifier->duration_type;
                        }
                        echo CHtml::image(Yii::app()->baseUrl."/images/modifiers/".$modifier->keyword.".png",$modifier->keyword,array('class' => 'modifier','title' => ucfirst($modifier->keyword).': '.$duration.' '.$duration_type));
                    }
                }
                ?>
            </span>
        </p>
        <div id="energia">
            <div id="tuesteRetueste">
                <span id="tueste" class="w<?php echo floor(($user->ptos_tueste/$maxTueste)*100); ?>">
                    <?php if($user->ptos_tueste > 0):?>
                        <span class="pin">
                            <span class="title"><?php echo $user->ptos_tueste; ?> puntos de tueste</span>
                        </span>
                    <?php endif; ?>
                </span>
                <span id="retueste" class="w<?php echo floor(($user->ptos_retueste/$maxTueste)*100); ?>">
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
            <span class="concepto">rango</span>
        </p>

        <p class="dato">
            <span class="numero"><?php echo $user->tostolares; ?></span>
            <span class="concepto">tostólares</span>
        </p>

        <?php /*
        <p class="dato">
            <span class="numero">3</span>
            <span class="concepto">cofres</span>
        </p>

        <p class="dato">
            <span class="numero"><?php echo $user->sugarcubes; ?></span>
            <span class="concepto">azucarillos</span>
        </p>
        */ ?>

        <p class="dato">
            <span class="numero"><?php echo $user->ptos_relanzamiento; ?></span>
            <span class="concepto">p. relance</span>
        </p>

        <p id="skillsIcon">
            <a>
                <img src="<?php echo Yii::app()->baseUrl?>/images/skillsIcon.png">
            </a>
        </p>
    </div>
    <div id="skillsUserBlock" <?php if($skillsHidden=="1"){echo 'style="display:none;"';}else{echo 'class="visible"';} ?>>
        <?php
        //Validador de habilidades
        $validator = new SkillValidator;
        //$user = User::model()->findByPk(Yii::app()->currentUser->id); //viene del controlador
        ?>
        <ul>
            <?php foreach($skills as $skill):?>
                <?php if ($validator->canExecute($skill, $user)): ?>
                    <li><?php
                        $img = CHtml::image(Yii::app()->baseUrl."/images/skills/".$skill->keyword.".png");
                        echo CHtml::link($img, null, array('class' => 'skillLink','title' => $skill->name.': '.$skill->description));
                        ?>
                        <section class="skillDescription">
                            <div class="sdcontent">
                                <h1><span><?php echo $skill->name; ?></span><?php echo CHtml::image(Yii::app()->baseUrl."/images/skills/".$skill->keyword.".png"); ?></h1>
                                <p class="skillDesc"><?php echo $skill->description; ?></p>
                                <dl>
                                    <dt>Coste: </dt>
                                    <dd><?php
                                        if($skill->cost_tueste!==null && $skill->cost_tueste > 0) echo ' '.$skill->cost_tueste.' (tueste)';
                                        if($skill->cost_retueste!==null && $skill->cost_retueste > 0) echo ' '.$skill->cost_retueste.' (retueste)';
                                        if($skill->cost_relanzamiento!==null && $skill->cost_relanzamiento > 0) echo ' '.$skill->cost_relanzamiento.' (relanzamiento)';
                                        if($skill->cost_tostolares!==null && $skill->cost_tostolares > 0) echo ' '.$skill->cost_tostolares.' (tostólares)';
                                        ?></dd>
                                    <dt>Probabilidad de Crítico:</dt>
                                    <dd><?php echo $skill->critic; ?></dd>
                                    <dt>Probabilidad de Pifia:</dt>
                                    <dd><?php echo $skill->fail; ?></dd>
                                    
									<?php if($skill->require_target): ?>
                                    
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
										
                                    <?php elseif($skill->require_target_side !== null): 
										//El objetivo será un bando u el otro									
										?>
									
                                        <dt>Objetivo</dt>
                                        <dd class="targetList">
                                            <ul>
												<li class="kafhe" target_id="kafhe">Kafheítas</li>
                                                <li class="achikhoria" target_id="achikhoria">Achikhoritas</li>
                                            </ul>
                                        </dd>
										
                                    <?php endif; ?>
                                </dl>
                                <p class="skillButtons">
                                    <?php echo CHtml::link('Aceptar', Yii::app()->createUrl('skill/execute', array('skill_id'=>$skill->id)), array('class'=>'btn btncommon acceptButton'));?>
                                    <?php echo CHtml::link('Cancelar', null, array('class' => 'btn cancelButton'));?>
                                </p>
                            </div>
                        </section>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<div id="experiencia">
    <?php $pExp = floor(100*(Yii::app()->currentUser->experience/Yii::app()->config->getParam('maxExperienciaUsuario'))); ?>
    <span id="xp<?php if($pExp == 0) echo 0;?>" class="w<?php echo $pExp;?>">
        <span class="pin">
                <span class="title">Faltan <?php echo ( intval(Yii::app()->config->getParam('maxExperienciaUsuario')) - Yii::app()->currentUser->experience ); ?> puntos de experiencia</span>
        </span>
    </span>
</div>