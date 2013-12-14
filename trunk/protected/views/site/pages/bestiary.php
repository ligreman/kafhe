<div class="paddedContent page">
    <?php
    /* @var $this SiteController */

    $this->pageTitle=Yii::app()->name . ' - Bestiario';

    $habilidades = Skill::model()->findAll(array('order'=>'category, type, name'));

    $array = array();

    foreach($habilidades as $habilidad) {
        $coste = '';

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
        );
    }

    $gridDataProvider = new CArrayDataProvider($array);

    ?>
    <h1>Bestiario</h1>

    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
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
    ));
    ?>

    <p class="right">Leyenda: T (Tueste), RT (ReTueste), § (lágrimas), t (tostólares)</p>


</div>