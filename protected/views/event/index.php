<div id="menuContent" class="paddedContent">
    <?php
    $flashMessages = Yii::app()->user->getFlashes();
    if ($flashMessages) {
        echo '<ul class="flashes">';
        foreach($flashMessages as $key => $message) {
            echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
        }
        echo '</ul>';
    }
    ?>

    <h1 class="battle">Estado de la batalla: <?php echo Yii::app()->params->eventStatusNames[$battle->status]; ?></h1>
    <div id="battleContent">

    <?php
        $achikhoriaMembers = '<ul id="kafheMembers">';
        $kafheMembers = '<ul id="achikhoriaMembers">';
        $libre = '<ul id="libreMember">';
        foreach ($users as $user) {
            if ($user->active==false) {
                $colorInactive = 'col_retueste';
                $titleInactive = 'Inactivo';
            }else $colorInactive = $titleInactive = '';

            if($user->side == 'kafhe'){
                $kafheMembers .= '<li><strong>'.$user->alias.'</strong> (Rango '.$user->rank.'): <span class="'.$colorInactive.'" title="'.$titleInactive.'">'.Yii::app()->params->userStatusNames[$user->status].'</span></li>';
            }elseif($user->side == 'achikhoria'){
                $achikhoriaMembers .= '<li><strong>'.$user->alias.'</strong> (Rango '.$user->rank.'): <span class="'.$colorInactive.'" title="'.$titleInactive.'">'.Yii::app()->params->userStatusNames[$user->status].'</span></li>';
            }else{
				$libre .= '<li><strong>'.$user->alias.'</strong> (Rango '.$user->rank.'): <span class="'.$colorInactive.'" title="'.$titleInactive.'">'.Yii::app()->params->userStatusNames[$user->status].'</span></li>';
            }
        }
    ?>
    <div id="bandos">
        <div id="bandoKafhe">
            <h2>Kafheítas</h2>
            <?php
                echo $kafheMembers.'</ul>';
            ?>
        </div>
        <div id="bandoAchikhoria">
            <h2>Renunciantes</h2>
            <?php
            echo $achikhoriaMembers.'</ul>';
            ?>
        </div>
        <div id="bandoLibre">
            <h2>Iluminado</h2>
            <?php echo $libre.'</ul>';?>
        </div>
    </div>

    <p class="noteProbs">* En <span class="col_retueste">rojo</span> los jugadores inactivos.</p>

<?php
    //**************** Fama de bandos *********************
    $fameSides = Yii::app()->usertools->calculateSideFames(true);
    $prevEvent = Yii::app()->event->getPreviousEvent();
    $individualFame = Yii::app()->usertools->calculateFameDifferentials(true);

    if ($individualFame!==null):
?>


    <!--<div id="battleStatusChart"></div>-->
    <div id="battleStatusChart_diff"></div>
    <div id="battleIndividualFame"></div>

    <script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var newData = google.visualization.arrayToDataTable([
                ['Bando', 'Fama'],
                ['Renunciantes', <?php echo $fameSides['achikhoria']; ?>],
                ['Kafheitas', <?php echo $fameSides['kafhe']; ?>]
            ]);

            var options = {
                title: 'Distribución de fama por bandos',
                titleTextStyle: { fontName: 'Lato', fontSize: 20 },
                chartArea:{width: 600,height: 400},
                colors:['#673c7d','#f0cc33'],
                fontName: 'Lato',
                pieSliceText: 'percentage',
                height:500,
                diff: { innerCircle: { borderFactor: 0.02 } }
            };

            <?php if ($prevEvent===null):?>
                var chart = new google.visualization.PieChart(document.getElementById('battleStatusChart_diff'));
                chart.draw(newData, options);
            <?php else: ?>
                var oldData = google.visualization.arrayToDataTable([
                    ['Bando', 'Fama'],
                    ['Renunciantes', <?php echo $prevEvent->fame_achikhoria; ?>],
                    ['Kafheitas', <?php echo $prevEvent->fame_kafhe; ?>]

                ]);

                var chartDiff = new google.visualization.PieChart(document.getElementById('battleStatusChart_diff'));
                var diffData = chartDiff.computeDiff(oldData, newData);
                chartDiff.draw(diffData, options);
            <?php endif; ?>

        }

        google.setOnLoadCallback(drawChart2);
        function drawChart2() {
            var data = google.visualization.arrayToDataTable([
                ['Usuario', 'Fama'],
                <?php
                    foreach ($users as $user) {
                        if(isset($individualFame[$user->id])){
                            echo '["'.$user->alias.'",'.$individualFame[$user->id].'],';
                        }
                    }
                ?>

            ]);

            var options = {
                title: 'Desviación de la fama entre usuarios',
                titleTextStyle: { fontName: 'Lato', fontSize: 20 },
                legend: {position: 'none'},
                colors:['#60b97f'],
                fontName: 'Lato',
                height:500
            };

            var chart = new google.visualization.BarChart(document.getElementById('battleIndividualFame'));
            chart.draw(data, options);
        }


    </script>
    <?php endif; ?>


<?php
    //************************ PROPABILIDADES USUARIOS ***************************
    $arr = Yii::app()->usertools->calculateUsersProbabilities();
    //print_r($arr);

    if ($arr!==null):
?>
	
	<!--<p class="clear noteProbs">* Nota: la probabilidad de un bando de salir elegido es inversamente proporcional a la cantidad de gungubos que tiene.</p>-->

    <div id="generalProbs">
        <div id="userProbabilities"></div>
    </div>

    <script type="text/javascript">
        //google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart3);
        function drawChart3() {
            var data = google.visualization.arrayToDataTable([
            ['Usuario', 'Probabilidad'],
            <?php
                $offset = 0;
                $miOffset = null;
                foreach ($users as $user) {
                    if(isset($arr[$user->id])){
                        echo '["'.$user->alias.'",'.$arr[$user->id].'],';

                        //Me saco yo si estoy entre los que se pintan
                        if ($user->id == Yii::app()->currentUser->id)
                            $miOffset = $offset;
                    }

                    $offset++;
                }
            ?>
        ]);

        var options = {
            title: 'Probabilidad de cada usuario',
            titleTextStyle: { fontName: 'Lato', fontSize: 20 },
            chartArea:{width: 600,height: 400},
            colors:['#bf3950','#ff8139','#f0cc33','#60b97f','#4f77c1','#673c7d','#ff2c61','#8f6255','#e2a30a','#00924a','#50cae6','#2a0e3d'],
            fontName: 'Lato',
            pieSliceText: 'percentage',
            height:500,
            slices: {  <?php echo $miOffset; ?>: {offset: 0.1} }
        };

        var chart = new google.visualization.PieChart(document.getElementById('userProbabilities'));
        chart.draw(data, options);
        }
    </script>
    <?php else: ?>
        <br /><p>No se ha alistado ningún jugador, por lo que no hay estadísticas.</p>
    <?php endif; ?>


    <?php /*
    <div id="sideProbs">
        <h2>Probabilidad dentro del bando</h2>
        <div id="userProbabilitiesAchikhoriaSide"></div>

        <div id="userProbabilitiesKafheSide"></div>
    </div>

    <script type="text/javascript">
        //google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart4);
        function drawChart4() {
            var data = google.visualization.arrayToDataTable([
                ['Usuario', 'Probabilidad'],
                <?php
                    //$kSide = 0;
                    //$aSide = 0;
                    foreach ($users as $user) {
                        if(isset($arr[$user->id]) && $user->side == 'achikhoria'){
                            echo '["'.$user->alias.'",'.$arr[$user->id].'],';
                        }
                    }
                ?>
            ]);

            var options = {
                chartArea:{width: '600',height: '400', top: 10, left:10},
                colors:['#673c7d','#2a0e3d'],
                fontName: 'Lato',
                pieSliceText: 'number',
                width: 300,
                height:300
            };

            var chart = new google.visualization.PieChart(document.getElementById('userProbabilitiesAchikhoriaSide'));
            chart.draw(data, options);
        }
    </script>
    <script type="text/javascript">
        //google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart5);
        function drawChart5() {
            var data = google.visualization.arrayToDataTable([
                ['Usuario', 'Probabilidad'],
                <?php
                    //$kSide = 0;
                    //$aSide = 0;
                    foreach ($users as $user) {
                        if(isset($arr[$user->id]) && $user->side == 'kafhe'){
                            echo '["'.$user->alias.'",'.$arr[$user->id].'],';
                        }
                    }
                ?>
            ]);

            var options = {
                chartArea:{width: '600',height: '400', top: 10, left:10},
                colors:['#f0cc33','#e2a30a'],
                fontName: 'Lato',
                pieSliceText: 'number',
                width: 300,
                height:300
            };

            var chart = new google.visualization.PieChart(document.getElementById('userProbabilitiesKafheSide'));
            chart.draw(data, options);

            resizeNavBar();
        }
    </script>

    <div class="clear"></div>


     echo "<br><br>";
     $random = mt_rand(1, 10000);
     echo "random: ".$random;
     echo "<br><br>";

     $suma = 0;
     foreach($arr as $user=>$valor) {
        $valor = $valor * 100; //tiene 2 decimales así que lo convierto a entero
        if ($valor == 0) continue;

        echo "valor: ".$valor." // suma: ".$suma." ** De ".($suma+1)." a ".($valor+$suma)."<br>";
        if ( (($suma+1) <= $random) && ($random <= ($suma+$valor)) )
            echo "Ta tocao: ".$user."<br>";

        $suma += $valor;
     }*/

    /* echo "<br><br>Hago 10000 veces el sorteo";

     echo "<br><br>";
     $tocaA = array(2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0);

     for ($i=1; $i<=10000; $i++) {
        $tocaA = toca($arr, $tocaA);
     }

     print_r($tocaA);

     function toca($arr, &$tocaA) {
        $random = mt_rand(1, 10000);
        $anterior = 0;

        if ($arr===null) return null;

        foreach($arr as $user=>$valor) {
            $valor = $valor * 100; //tiene 2 decimales así que lo convierto a entero
            if ($valor == 0) continue;

            if ( (($anterior+1) <= $random) && ($random <= ($anterior+$valor)) )
                $tocaA[$user]++;

            $anterior += $valor;
         }

        return $tocaA;
     }



     echo "<br><br>EMAIL: ";
    */
       /* $data['to'] = array('cgoo85@gmail.com');
        $data['subject'] = 'Email de prueba desde Kafhe 3.0';
        $data['body'] = 'Hola caracola. Esta es la plantilla por defecto de los emails.';
        $sent = Yii::app()->mail->sendEmail($data);
        if ($sent !== true)
            echo $sent;
*/

         //envio mails
        /*$mail = new YiiMailer();
        //$mail->clearLayout();//if layout is already set in config
        $mail->setFrom('omelettus@gmail.com', 'John Doe');
        $mail->setTo('omelettus@gmail.com');
        $mail->setSubject('Mail subject');
        $mail->setBody('Simple message');


        /*Setting addresses

        When using methods for setting addresses (setTo(), setCc(), setBcc(), setReplyTo()) any of the following is valid for arguments:

        $mail->setTo('john@example.com');
        $mail->setTo(array('john@example.com','jane@example.com'));
        $mail->setTo(array('john@example.com'=>'John Doe','jane@example.com'));*/


        /*$mail->IsSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup server
        $mail->Port = 587;
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'omelettus@gmail.com';                            // SMTP username
        $mail->Password = 'om3l3ttus';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
        $mail->IsHTML(true);                                  // Set email format to HTML
        if ($mail->send()) {
            echo "Email enviado";
            //Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
        } else {
            echo "Error enviando email: ".$mail->getError();
            //Yii::app()->user->setFlash('error','Error while sending email: '.$mail->getError());
        }
*/

     ?>
    </div>
    <br class="clear" />
</div>
