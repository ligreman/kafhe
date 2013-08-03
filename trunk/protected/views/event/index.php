<?php
/* @var $this EventController */

$this->breadcrumbs=array(
	'Event',
);
?>

<?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
    }
?>

<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>Kafhe: <?php echo $battle->gungubos_kafhe; ?> gungubos.</p>
<p>Achikhoria: <?php echo $battle->gungubos_achikhoria; ?> gungubos.</p>

<p>Estado de los jugadores:</p>
<?php 
	foreach ($users as $user) {	
		$this->widget('zii.widgets.CDetailView', array(
			'data'=>$user,
			'attributes'=>array(				
				'alias',        // an attribute of the related object "owner"
				'status'
			),
		));	
    }
?>
<ul>
<li>0 - criador</li>
<li>1 - cazador</li>
<li>2 - alistado</li>
<li>3 - baja</li>
</ul>
<?php
echo "Elijo a...";
$res = Yii::app()->event->selectCaller();
print_r($res);

 echo "<br><br>";

 $arr = Yii::app()->usertools->calculateProbabilities(Yii::app()->user->group_id, true, $res['side']);
 print_r($arr);
 /*
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
 
 echo "<br><br>Hago 10000 veces el sorteo";
 
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
/*
    $data['to'] = array('lomas.garcia@gmail.com', 'cgoo85@gmail.com');
    $data['subject'] = 'Email de prueba desde Kafhe 3.0';
    $data['body'] = 'Hola caracola. Esta es la plantilla por defecto de los emails.';
    $sent = Yii::app()->mail->sendEmail($data);
    if ($sent !== true)
        echo $sent;*/


	 //envio mails
	/* $mail = new YiiMailer();
	//$mail->clearLayout();//if layout is already set in config
	$mail->setFrom('omelettus@gmail.com', 'John Doe');
	$mail->setTo('omelettus@gmail.com');
	$mail->setSubject('Mail subject');
	$mail->setBody('Simple message');*/


	/*Setting addresses 

	When using methods for setting addresses (setTo(), setCc(), setBcc(), setReplyTo()) any of the following is valid for arguments:

	$mail->setTo('john@example.com');
	$mail->setTo(array('john@example.com','jane@example.com'));
	$mail->setTo(array('john@example.com'=>'John Doe','jane@example.com'));*/


	/*$mail->IsSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';  // Specify main and backup server
	$mail->Port = 465; 
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
	}*/


 ?>
