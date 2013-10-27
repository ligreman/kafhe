<?php
/* @var $this HomeController */

$this->breadcrumbs=array(
	'Home',
);
?>

<?php
$flashMessages = Yii::app()->user->getFlashes();
if ($flashMessages) {
    echo '<div class="flashes">';
    foreach($flashMessages as $key => $message) {
        echo '<p><div class="flash-' . $key . '">' . $message . "</div></p>\n";
    }
    echo '</div>';
}
?>

<div class="row">
    <div class="span3">
        <?php
        if (isset($files)) {
            $this->widget('bootstrap.widgets.TbMenu', array(
                'type'=>'list',
                'items'=>$files,
            ));
        }
        ?>
    </div>

    <div class="span9">
        <?php
            if(!isset($contenido)) {
                ?>
                <p>Selecciona un archivo de log</p>
                <?php
            } else {
                $this->widget('bootstrap.widgets.TbButton', array(
                    'label'=>'Borrar fichero',
                    'icon'=>'remove white',
                    'type'=>'warning', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'size'=>null, // null, 'large', 'small' or 'mini'
                    'url'=>Yii::app()->baseUrl.'/administration/logs/delete?file='.$file
                ));


                $this->widget('bootstrap.widgets.TbGridView', array(
                    'type'=>'striped bordered condensed',
                    'dataProvider'=>$contenido,
                    'template'=>"{pager}{items}{pager}",
                    'columns'=>array(
                        array('name'=>'date', 'header'=>'Fecha'),
                        array('name'=>'type', 'header'=>'Tipo'),
                        array('name'=>'description', 'header'=>'Descripción'),
                        array('name'=>'stack', 'header'=>'Stack Trace'),
                    ),
                ));
            }

        ?>
    </div>


</div>

<script type="text/javascript">
    <!--
    $(document).ready(function() {
        //Formatea el Stack Trace
        $("table.items td").each(function() {
            var txt = $(this).text();

            var n = txt.indexOf("<br>");
            if (n != -1) {
                txt.replace("&lt;br&gt;", "<br />");
                $(this).html(txt);
            }
        });
    });
    //-->
</script>
