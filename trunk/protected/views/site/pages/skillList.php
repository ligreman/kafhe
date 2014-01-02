<div class="paddedContent page">
    <h1>Grimorio</h1>

    <?php
    /* @var $this SiteController */

    $this->pageTitle=Yii::app()->name . ' - Grimorio';

    $habilidades = Skill::model()->findAll(array('order'=>'category, type, name'));

    ?>

    <ul id="hb_index">

    <?php
    //Índice
    $index = 0;
    foreach($habilidades as $habilidad) {
        echo '<li><a href="#in_'.$index.'">'.$habilidad->name.'</a></li>';
        $index++;
    }

    ?>

    </ul>

    <?php

    //Listado de habilidades
    $index = 1; //Empiezo en 1 más para que me deje el scroll bien
    foreach($habilidades as $habilidad) {
        /*$coste = '';

        if ($habilidad->cost_tueste!==null) {
            $coste .= $habilidad->cost_tueste.'T';
        }
        if ($habilidad->cost_retueste!==null) {
            $coste .= ' '.$habilidad->cost_retueste.'RT';
        }
        if ($habilidad->cost_relanzamiento!==null) {
            $coste .= ' '.$habilidad->cost_relanzamiento.'§';
        }
        if ($habilidad->cost_tostolares!==null) {
            $coste .= ' '.$habilidad->cost_tostolares.'t';
        }

        $array[] = array(
            'id'=>1,
            'category'=>ucfirst($habilidad->category),
            'name'=>$habilidad->name,
            'description'=>$habilidad->description,
            'coste'=>$coste,
            'criticfail'=>$habilidad->critic.' / '.$habilidad->fail,
            'rank'=>$habilidad->require_user_min_rank.' / '.$habilidad->require_user_max_rank
        );*/

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





<!--








<html>
	<head>
		<style type="text/css">
			@import url(http://fonts.googleapis.com/css?family=Lato:400,700italic);
			html, body, div, span, applet, object, iframe,h1, h2, h3, h4, h5, h6, p, blockquote, pre,a, abbr, acronym, address, big, cite, code,del, dfn, em, img, ins, kbd, q, s, samp,small, strike, strong, sub, sup, tt, var,b, u, i, center,dl, dt, dd, ol, ul, li,fieldset, form, label, legend,table, caption, tbody, tfoot, thead, tr, th, td,article, aside, canvas, details, embed,figure, figcaption, footer, header, hgroup,menu, nav, output, ruby, section, summary,time, mark, audio, video {margin: 0; padding: 0;border: 0;font-size: 100%;vertical-align: baseline;}
			article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section {display: block;}
			body {line-height: 1;}
			ol, ul {list-style: none;}
			blockquote, q {quotes: none;}
			blockquote:before, blockquote:after,q:before, q:after {content: '';content: none;}
			table {				border-collapse: collapse;				border-spacing: 0;			}
			a:focus{				color:inherit;			}
			body { padding: 10em; }

			.hb {
				position: relative;
				border: 4px solid #CCC;
				width: 80%;
				padding: 1%;
				margin: 0 auto;
			}
			.hb_header {
				width: 100%;
			}
			
			.hbh_icon {
				width: 10%;
				height: 100px;
				background: cyan;
				display: inline-block;
			}
			.hbh_icon img {
				width: 100%;
			}
			.hbh_title {
				width: 65%;
				display: inline-block;
			}
			.hbht_1 {
				font-size: 1.5em;
				margin-bottom: 5px;
			}
			.hbht_1.kafhe {
				padding-left: 30px;
				background: url() no-repeat center center;
			}
			.hbht_1.achikhoria {
				padding-left: 30px;
				background: url() no-repeat center center;
			}
			.hbht_1.teh {
				padding-left: 30px;
				background: url() no-repeat center center;
			}
			.hbht_2 {
				font-size: 0.8em;
			}
			.hbht_2.kafhe, .hbht_2.achikhoria, .hbht_2.teh {
				padding-left: 30px;
			}
			.hbh_data {
				width: 20%;
				float: right;
				text-align: right;
			}
			.hbhd_result {
				margin: 5px 5px auto auto;
				font-weight: bold;
			}
			.hbhd_result span {
				border: 1px solid;
				border-radius: 15px;
				padding: 1px 3px;	
				margin: 0 5px;
			}
			.hbhd_result span.hbhd_critic {
				border-color: transparent;
				background-color: #2CE00C;
				box-shadow: 0 0 2px #000;
			}
			.hbhd_result span.hbhd_fail {
				border-color: transparent;
				background-color: #E20A0A;
				box-shadow: 0 0 2px #000;
				color: #F5F5F5;
			}
			.hbhd_cost {
				margin-top: 15px;
			}

			.hb_body {
				margin: 20px 1em 0 1em;
				text-align: justify;
				font-size: 1.1em;
			}
			.hb_body p {
				margin: 10px 0;
			}

			.hb_footer {
				margin-top: 15px;
				width: 100%;
				text-align: right;
			}
			.hb_footer p {
				display: inline;
				border: 2px solid;
				border-radius: 5px;
				padding: 0 3px;
				margin-left: 15px;
			}
			.hb_footer p.red {
				border-color: #851010;
				background-color: #F08686;
			}
			.hb_footer p.green {
				border-color: #006400;
				background-color: #8EDB91;
			}
			.hb_footer p.grey {
				border-color: #5F5F5F;
				background-color: #D4D4D4;
			}
		</style>
	</head>
	<body>



<div class="hb">

	<div class="hb_header">
		<div class="hbh_icon">
		</div>
		<div class="hbh_title">
			<p class="hbht_1">Titulo</p>
			<p class="hbht_2">Categoria</p>
			<br /><br />
		</div>
		<div class="hbh_data">
			<p class="hbhd_result"><span title="Crítico" class="hbhd_critic">20</span><span title="Pifia" class="hbhd_fail">40</span></p>
			<p class="hbhd_cost"><ul>
				<li>25 tueste</li>
				<li>27 retueste</li>
				<li>28 lagrimas</li>
				<li>29 gungubos</li>
			</ul></p>
		</div>
	</div>

	<div class="hb_body">
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut sapien eros, vehicula eget felis in, tempus venenatis justo. Maecenas volutpat sapien ac quam pellentesque lobortis. Etiam id lacus felis. Nam tempor justo nec tempus dictum. Curabitur rhoncus iaculis lectus eu porta. Duis elementum porta leo, et volutpat dui imperdiet sed. Mauris non orci vel diam pulvinar ullamcorper quis non tellus. Maecenas a nulla metus.</p>

		<p>Praesent posuere sapien at enim hendrerit posuere. Etiam ultricies mauris dapibus lectus sollicitudin, ullamcorper vulputate nisl laoreet. Mauris eu erat ligula. Nunc nunc dolor, dignissim a justo vitae, hendrerit imperdiet mauris.</p>		
	</div>

	<div class="hb_footer">
		<p class="grey">Uno</p>
		<p class="grey">Dos</p>
		<p class="green">Cuatro</p>
		<p class="red">Tres</p>
	</div>


</div>



	</body>
</html>

-->