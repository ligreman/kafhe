<div id="hb_index" class="paddedContent page">
    <br />
    <h1>Grimorio personal de <?php echo Yii::app()->usertools->getAlias(Yii::app()->currentUser->id); ?></h1>

    <?php
    /* @var $this SiteController */

    $this->pageTitle=Yii::app()->name . ' - Grimorio';

    $habilidades = Skill::model()->findAll(array('order'=>'category, type, name'));

    ?>

    <!--<ul id="hb_index">-->

    <?php
    //Índice
    $index = 0;
	$commonSkill = $kafheSkill = $achiSkill = $libreSkill = array();
    foreach($habilidades as $habilidad) {
		if ($habilidad->require_user_side!==null) {
			if ($habilidad->require_user_side=='kafhe')
				$kafheSkill[] = $habilidad->name.'##'.$index;
			elseif ($habilidad->require_user_side=='achikhoria')
				$achiSkill[] = $habilidad->name.'##'.$index;
			elseif ($habilidad->require_user_side=='libre')
				$libreSkill[] = $habilidad->name.'##'.$index;
			else
				$commonSkill[] = $habilidad->name.'##'.$index;
		}

        //echo '<li><a href="#in_'.$index.'">'.$habilidad->name.'</a></li>';
        $index++;
    }

    sort($kafheSkill);
    sort($achiSkill);
    sort($libreSkill);
    sort($commonSkill);

	//Tabla de contenidos
    ?>
    <!--</ul>-->

	<table id="tocSkills">
		<thead><tr>
			<th>Comunes</th>
			<th>Kafhe</th>
			<th>Achikhoria</th>
			<th>Têh</th>
		</tr></thead>

		<tbody>
			<?php
				$sigo = false;
				do {
					$sigo = false;
					$fila = '<tr>';

					if (!empty($commonSkill)) {
						$aux = array_shift($commonSkill);
						$aux = explode('##', $aux);
						$fila .= '<td><a href="#in_'.$aux[1].'">'.$aux[0].'</a></td>';
						$sigo = true;
					} else
						$fila .= '<td></td>';

					if (!empty($kafheSkill)) {
						$aux = array_shift($kafheSkill);
						$aux = explode('##', $aux);
						$fila .= '<td><a href="#in_'.$aux[1].'">'.$aux[0].'</a></td>';
						$sigo = true;
					} else
						$fila .= '<td></td>';

					if (!empty($achiSkill)) {
						$aux = array_shift($achiSkill);
						$aux = explode('##', $aux);
						$fila .= '<td><a href="#in_'.$aux[1].'">'.$aux[0].'</a></td>';
						$sigo = true;
					} else
						$fila .= '<td></td>';

					if (!empty($libreSkill)) {
						$aux = array_shift($libreSkill);
						$aux = explode('##', $aux);
						$fila .= '<td><a href="#in_'.$aux[1].'">'.$aux[0].'</a></td>';
						$sigo = true;
					} else
						$fila .= '<td></td>';

					$fila .= '</tr>';

					if ($sigo==true) echo $fila;
				} while($sigo);
			?>
		</tbody>
	</table>






    <?php

    //Listado de habilidades
    $index = 1; //Empiezo en 1 más para que me deje el scroll bien
    foreach($habilidades as $habilidad) {
        $bandos = '';
        if ($habilidad->require_user_side!==null) {
            $aux = explode(',', $habilidad->require_user_side);
            foreach ($aux as $a) {
                $bandos .= '<span class="bando" title="'.Yii::app()->params->sideNames[$a].'">'.CHtml::image(Yii::app()->baseUrl."/images/modifiers/".$a.".png").'</span>';
            }

        } else {
            $bandos .= '<span class="bando" title="'.Yii::app()->params->sideNames['kafhe'].'">'.CHtml::image(Yii::app()->baseUrl."/images/modifiers/kafhe.png").'</span>';
            $bandos .= '<span class="bando" title="'.Yii::app()->params->sideNames['achikhoria'].'">'.CHtml::image(Yii::app()->baseUrl."/images/modifiers/achikhoria.png").'</span>';
            $bandos .= '<span class="bando" title="'.Yii::app()->params->sideNames['libre'].'">'.CHtml::image(Yii::app()->baseUrl."/images/modifiers/libre.png").'</span>';
        }

		?>
		<div class="hb">
			<div class="hb_header">
				<div class="hbh_icon">
				    <?php echo CHtml::image(Yii::app()->baseUrl."/images/skills/".$habilidad->keyword.".png",$habilidad->keyword); ?>
				</div>
				<div class="hbh_title">
					<p class="hbht_1"><?php echo $habilidad->name; ?></p>
					<p class="hbht_2"><?php echo ucfirst($habilidad->category); ?></p>
					<br /><br />
				</div>
				<div class="hbh_data">
					<p class="hbhd_result">
						<span title="Crítico" class="hbhd_critic"><?php echo $habilidad->critic; ?></span>
						<span title="Pifia" class="hbhd_fail"><?php echo $habilidad->fail; ?></span>
					</p>
					<ul class="hbhd_cost" id="costes">
						<?php if ($habilidad->cost_tueste!==null) echo '<li>'.$habilidad->cost_tueste.' tueste</li>'; ?>
						<?php if ($habilidad->cost_retueste!==null) echo '<li>'.$habilidad->cost_retueste.' retueste</li>'; ?>
						<?php if ($habilidad->cost_relanzamiento!==null) echo '<li>'.$habilidad->cost_relanzamiento.' lágrimas</li>'; ?>
						<?php if ($habilidad->cost_gungubos!==null) echo '<li>'.$habilidad->cost_gungubos.' Gungubos</li>'; ?>
					</ul>
				</div>
			</div>

			<div class="hb_body">
				<p>
					<?php
						echo str_replace('<br />', '</p><p>', $habilidad->description);
					?>
				</p>
			</div>

			<div class="hb_footer" id="in_<?php echo $index; ?>">
			    <div class="bandos">
                    <?php echo $bandos; ?>
			    </div>
				<?php if ($habilidad->require_user_min_rank!==null) echo '<p class="grey">Rango mínimo requerido '.$habilidad->require_user_min_rank.'</p>'; ?>
				<?php if ($habilidad->require_user_max_rank!==null) echo '<p class="grey">Rango máximo requerido '.$habilidad->require_user_max_rank.'</p>'; ?>

				<?php if ($habilidad->generates_notification==false) echo '<p class="green">No crea notificación en el muro</p>'; ?>				
				<?php if ($habilidad->overload==true) echo '<p class="red">Sobrecarga</p>'; ?>
				<br />
			</div>
		</div>

		<p class="centerContainer"><a href="#hb_index">Subir</a></p>
		<?php
        $index++;

    }

    //$gridDataProvider = new CArrayDataProvider($array);

    ?>


    <?php
    /*$this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$gridDataProvider,
        'template'=>"{summary}{items}{pager}",
        'summaryText' => 'Mostrando {start} - {end} de {count} habilidades',
        'columns'=>array(
            array('name'=>'category', 'header'=>'Categoría'),
            array('name'=>'name', 'header'=>'Nombre'),
            array('name'=>'description', 'header'=>'Descripción'),
            array('name'=>'coste', 'header'=>'Coste ejecución'),
            array('name'=>'criticfail', 'header'=>'Crítico/Pifia'),
            array('name'=>'rank', 'header'=>'Rango min/max requerido')
        ),
    ));*/
    ?>

    <!--<p class="right">Leyenda: T (Tueste), RT (ReTueste), § (lágrimas), t (tostólares)</p>-->



</div>
