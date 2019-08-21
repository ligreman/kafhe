<?php

class EventController extends Controller
{
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'accessControl',
		);
	}
        
	public function accessRules()
	{
		return array(
			array('deny',
				'roles'=>array('Administrador'), //Prevenir que el admin no entre ya que no es jugador
			),
			array('allow', 
				'actions'=>array('index'),
				'roles'=>array('Usuario'),
			),
							array('pantuflo', 
				'actions'=>array('index'),
				'roles'=>array('Usuario'),
			),

			array('allow', 
				'actions'=>array('start'),
				'roles'=>array('Usuario'),
				'expression'=>"(isset(Yii::app()->event->model) && Yii::app()->event->status==Yii::app()->params->statusCalma && Yii::app()->user->checkAccess('lanzar_evento'))", //Dejo entrar
			),
			array('allow', 
				'actions'=>array('finish'),
				'roles'=>array('Usuario'),
				'expression'=>"(isset(Yii::app()->event->model) && (Yii::app()->event->status==Yii::app()->params->statusFinalizado || Yii::app()->event->status==Yii::app()->params->statusBatalla) && isset(Yii::app()->event->callerId) && Yii::app()->event->callerId==Yii::app()->currentUser->id )", //Dejo entrar
			),
            array('allow',
                'actions'=>array('close'),
                'roles'=>array('Usuario'),
                'expression'=>"(isset(Yii::app()->event->model) && Yii::app()->event->status==Yii::app()->params->statusFinalizado && isset(Yii::app()->event->callerId) && Yii::app()->event->callerId==Yii::app()->currentUser->id)", //Dejo entrar
            ),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	

	public function actionIndex()
	{
		//Recojo los datos de la batalla
		$battle = Yii::app()->event->model;
		$users = Yii::app()->usertools->users;
		
		$this->render('index', array('battle'=>$battle, 'users'=>$users));
	}

    /** Da comienzo la batalla (elige primer llamador). Pasa el evento de Iniciado a Batalla
     */
    public function actionStart()
	{
		//Cambio el evento a estado 2 (batalla!!) si existe y está en el estado correcto
		if (!isset(Yii::app()->event->model) || !Yii::app()->event->status==Yii::app()->params->statusCalma)
			throw new CHttpException(400, 'Error al iniciar la batalla ya que no hay ningún evento activo, o en el estado correcto.');
		
		$event = Yii::app()->event->model;
		$event->status = Yii::app()->params->statusBatalla;
					
		//Elijo al primer llamador
		$battleResult = Yii::app()->event->selectCaller();
        $caller = User::model()->findByPk($battleResult['userId']);

        if ($battleResult === null) {
            Yii::app()->user->setFlash('error', 'No se ha podido iniciar la batalla ya que no se había alistado nadie.');
            $this->redirect(array('event/index'));
            return false;
        }

        $event->caller_id = $battleResult['userId'];
        $event->caller_side = $caller->side;

		//Guardo el evento
		if (!$event->save())
			throw new CHttpException(400, 'Error al guardar el estado del evento '.$event->id.' a '.$event->status.'. ['.print_r($event->getErrors(),true).']');

		//Creo la notificación		
		$nota = new Notification;
		$nota->event_id = Yii::app()->event->id;
        $nota->message = ':battle: ¡Que de comienzo la batalla! Nuestro primer llamador es... ¡'.Yii::app()->usertools->getAlias($event->caller_id).'!';
        $nota->type = 'omelettus';
        $nota->timestamp = Yii::app()->utils->getCurrentDate();
		if (!$nota->save())
			throw new CHttpException(400, 'Error al guardar la notificación de aviso de inicio de batalla del evento '.$event->id.'. ['.print_r($nota->getErrors(),true).']');

        // Doy lágrimas
        $sql = 'SELECT u.id,u.email FROM user u, event e WHERE e.id='.$event->id.' AND u.group_id=e.group_id AND (u.status='.Yii::app()->params->statusAlistado.' OR u.status='.Yii::app()->params->statusLibertador.' );';
        $users = Yii::app()->db->createCommand($sql)->queryAll();
        if (count($users)>0) {
            $emails = array();
            foreach($users as $user) {
                ///TODO eliminar esto: le doy ptos relance a todos los usuarios
                $us = User::model()->findByPk($user['id']);
                $us->ptos_relanzamiento += 4;
                $us->save();

                if ($user['id'] != $event->caller_id)
                    $emails[] = $user['email'];
            }
        }

		//Aviso al llamador
		$sent = Yii::app()->mail->sendEmail(array(
		    'to'=>$caller->email,
		    'subject'=>'¡A llamar!',
		    'body'=>'Ha dado inicio la batalla y el Gran Omelettus ha decidido que te toca llamar. Acepta tu derrota o pásale el marrón a otro.'
		    ));
		if ($sent !== true)
            Yii::log($sent, 'error', 'Email escaqueo');

        //Aviso a los demás usuarios alistados en el evento de que se inicia la batalla
        if (count($emails)>0) {
            $sent = Yii::app()->mail->sendEmail(array(
                'to'=>$emails,
                'subject'=>'¡Comienza la batalla!',
                'body'=>'El Gran Omelettus te informa de que se ha iniciado la batalla.'
            ));
            if ($sent !== true)
                Yii::log($sent, 'error', 'Email start event');
        }


        Yii::app()->user->setFlash('success', '¡Ha comenzado la batalla!');
		$this->redirect(array('event/index'));
	}

    /** Finaliza la batalla y mostrará el botón de ya he llamado
     */
    public function actionFinish()
	{
        //Cambio el evento a estado 3 de "asumo mi derrota"
        if (!isset(Yii::app()->event->model))
            throw new CHttpException(400, 'Error al finalizar la batalla asumiendo la derrota del usuario '.Yii::app()->currentUser->id);

        $event = Yii::app()->event->model;

        //Si es la primera vez que entro hago todo el proceso
        if($event->status != Yii::app()->params->statusFinalizado) {
            $event->status = Yii::app()->params->statusFinalizado;

            //Guardo el evento
            if (!$event->save())
                throw new CHttpException(400, 'Error al guardar el estado del evento '.$event->id.' a '.$event->status.'. ['.print_r($event->getErrors(),true).']');

            //Aviso a todos de que asumo mi derrota
            $sql = 'SELECT u.id,u.email FROM user u, event e WHERE e.id='.$event->id.' AND u.group_id=e.group_id AND (u.status='.Yii::app()->params->statusAlistado.' OR u.status='.Yii::app()->params->statusLibertador.');';
            $users = Yii::app()->db->createCommand($sql)->queryAll();
			$aliases = Yii::app()->usertools->getAlias(); //Cojo todos los alias
			
            if (count($users)>0) {
                foreach($users as $user) {
                    if ($user['id'] != $event->caller_id)
                        $emails[] = $user['email'];
                }

				$name = $aliases[$event->caller_id]; //Yii::app()->usertools->getAlias($event->caller_id);

				//Creo la notificación		
				$nota = new Notification;
				$nota->event_id = Yii::app()->event->id;
				$nota->message = '¡Oh, amados comensales! '.$name.' ha asumido su destino y procederá a llamar en los próximos minutos.';
				$nota->type = 'omelettus';
                $nota->timestamp = Yii::app()->utils->getCurrentDate();
				if (!$nota->save())
					throw new CHttpException(400, 'Error al guardar la notificación de aviso de asumir llamada del evento '.$event->id.'.  ['.print_r($nota->getErrors(),true).']');

                //Email
                $sent = Yii::app()->mail->sendEmail(array(
                    'to'=>$emails,
                    'subject'=>$name.' ha aceptado su derrota',
                    'body'=>$name.' ha asumido los designios del Gran Omelettus y derrotado procederá a llamar en los próximos minutos.'
                ));
                if ($sent !== true)
                    Yii::log($sent, 'error', 'Email finish event');
            }
        }
		
		//Saco los pedidos de este evento
		$orders = Yii::app()->event->getOrder($event->id);
        $individual_orders = Enrollment::model()->findAll(array('condition'=>'event_id=:event', 'params'=>array(':event'=>$event->id)));

		$this->render('finish', array('orders'=>$orders, 'individual_orders'=>$individual_orders)); //mostraré el pedido y un botón de ya he llamado, aunque el mismo enlace salga en el menú
	}

    /** Cerrar el evento al pulsar en Ya he llamado
     */
    public function actionClose()
    {
        //Cambio el evento a estado de "cerrado"
        if (!isset(Yii::app()->event->model))
            throw new CHttpException(400, 'Error al cerrar la batalla tras haber llamado el usuario '.Yii::app()->currentUser->id);

        $event = Yii::app()->event->model;
        $event->status = Yii::app()->params->statusCerrado;

        //Caducidad de modificadores de evento		
		Yii::app()->modifier->reduceEventModifiers($event->id);

		//Elimino los modificadores que no son de evento
        Modifier::model()->deleteAll(array('condition'=>'event_id=:evento AND duration_type!=:tipo', 'params'=>array(':evento'=>$event->id, ':tipo'=>'evento')));

        //Elimino el historial de ejecución de habilidades del evento
        HistorySkillExecution::model()->deleteAll(array('condition'=>'event_id=:evento', 'params'=>array(':evento'=>$event->id)));

        //Elimino Gungubos y Gumbudos
        Gungubo::model()->deleteAll(array('condition'=>'event_id=:evento', 'params'=>array(':evento'=>$event->id)));
        Gumbudo::model()->deleteAll(array('condition'=>'event_id=:evento', 'params'=>array(':evento'=>$event->id)));

        //Elimino notificaciones de corral
        NotificationCorral::model()->deleteAll(array('condition'=>'event_id=:evento', 'params'=>array(':evento'=>$event->id)));

        //Fama de los bandos
        $bandoGanador = '';
        $famaSides = Yii::app()->usertools->calculateSideFames();
        if ($famaSides['kafhe']>$famaSides['achikhoria'])
            $bandoGanador = 'kafhe';
        elseif ($famaSides['kafhe']<$famaSides['achikhoria'])
            $bandoGanador = 'achikhoria';

        //Doy experiencia y sumo llamadas y participaciones, pongo rangos como tienen que ser, elimino ptos de relanzamiento de la gente, y les pongo como Cazadores
		$usuarios = Yii::app()->usertools->getUsers();
		$new_usuarios = $ganadores = array();
		$anterior_llamador = null;
		$llamador_id = null;
		foreach($usuarios as $usuario) {			
			$usuario->ptos_relanzamiento = 0;
			$usuario->ptos_tueste = Yii::app()->tueste->getMaxTuesteUser($usuario); //Tueste al máximo
            //$fame_old[$usuario->side] += $usuario->fame;
			$usuario->fame = Yii::app()->config->getParam('initialFame');

			//Al llamador le pongo rango 1 y estado iluminado, y side libre
			if ($usuario->id == $event->caller_id) {
			    $llamador_id = $usuario->id;
				$usuario->calls++;
				$usuario->times++;
				$usuario->rank = 1;
				$usuario->side = 'libre';
				$usuario->status = Yii::app()->params->statusIluminado;

                $usuario->experience += Yii::app()->config->getParam('expParticipar'); //Experiencia por participar
                Yii::app()->usertools->checkLvlUpUser($usuario, false); // ¿Subo nivel?

                //Salvo
				if (!$usuario->save())
					throw new CHttpException(400, 'Error al actualizar al usuario '.$usuario->id.' llamador, al cerrar el evento '.$event->id.'. ['.print_r($usuario->getErrors(),true).']');
			} elseif ($usuario->status==Yii::app()->params->statusAlistado) {
				//A los alistados les pongo como criadores
				$usuario->rank++;
				$usuario->times++;
				$usuario->status = Yii::app()->params->statusCazador;

                //Si era del bando ganador, le daré recompensa así que le pongo como ganador
                if ($usuario->side == $bandoGanador) $ganadores[] = $usuario->id;

                $usuario->experience += ( Yii::app()->config->getParam('expParticipar') + Yii::app()->config->getParam('expNoLlamar') + ( ($usuario->rank-2) * Yii::app()->config->getParam('expPorRango') ) ); //Experiencia por participar + NoLLamar + Rango (de rango 1 a 2 no ganas exp)
                Yii::app()->usertools->checkLvlUpUser($usuario, false); // ¿Subo nivel?
           
				$new_usuarios[$usuario->id] = $usuario;
			} elseif ($usuario->status==Yii::app()->params->statusIluminado) {
				//Si era "libre" pero no fue al desayuno
                $usuario->rank++; //Aunque no fue al desayuno le subo de nivel igualmente para que todos los de bando sean nivel 2
				$usuario->status = Yii::app()->params->statusCazador;
				$anterior_llamador = $usuario;
			} elseif ($usuario->status==Yii::app()->params->statusLibertador) {
				//Al anterior libre, que si fue al desayuno, le pongo como criadores también
				$usuario->rank++;
				$usuario->times++;
				$usuario->status = Yii::app()->params->statusCazador;

                $usuario->experience += Yii::app()->config->getParam('expParticipar'); //Experiencia por participar
                Yii::app()->usertools->checkLvlUpUser($usuario, false); // ¿Subo nivel?
        
				$anterior_llamador = $usuario;
			} elseif ($usuario->status==Yii::app()->params->statusCazador) {
				//Al resto sólo les pongo de criadores
				$usuario->status = Yii::app()->params->statusCazador;
                $new_usuarios[$usuario->id] = $usuario;
			}
		}

        //Creo los bandos aleatoriamente (antes de guardar el nuevo evento)
        $final_users = Yii::app()->event->createSides($new_usuarios, $anterior_llamador);  //le paso el array de objetos usuarios y el objeto usuario anterior-llamador que no está en la lista

        //Guardo el evento
        $event->fame_kafhe = $famaSides['kafhe'];
        $event->fame_achikhoria = $famaSides['achikhoria'];
        if (!$event->save())
            throw new CHttpException(400, 'Error al guardar el estado del evento '.$event->id.' a '.$event->status.'. ['.print_r($event->getErrors(),true).']');

        //Actividad cron para que se calculen y otorguen recompensas
        if (!empty($ganadores)) {
            $cron = new Cronpile;
            $cron->operation = 'darRecompensas';
            $cron->params = $event->id.'##'.implode(',', $ganadores);
            if (!$cron->save())
                throw new CHttpException(400, 'Error al guardar en la pila de cron a los del bando ganador. ['.print_r($cron->getErrors(),true).']');
        }

        //Abro un evento nuevo de desayuno
        $nuevoEvento = new Event;
        $nuevoEvento->group_id = $event->group_id;
        $nuevoEvento->status = Yii::app()->params->statusPreparativos;
        $nuevoEvento->type = 'desayuno';
        //$nuevoEvento->gungubos_population = 0; //se inicia vacío el evento //mt_rand(7,13)*100; //mt_rand(5,10)*1000;
        //$nuevoEvento->last_gungubos_repopulation = date('Y-m-d'); //ya he repoblado hoy		

        $fecha = new DateTime();
        $fecha->add(new DateInterval('P7D'));
		//$fecha = strtotime('next Friday');
        $nuevoEvento->date = $fecha->format('Y-m-d'); //date('Y-m-d', $fecha);

        if (!$nuevoEvento->save())
            throw new CHttpException(400, 'Error al crear un nuevo evento. ['.print_r($nuevoEvento->getErrors(),true).']');
			
		//Creo las entradas de repoblar para la pila cron
		//Yii::app()->event->scheduleGungubosRepopulation($nuevoEvento->getPrimaryKey());

        //Salvo usuarios
        if (count($final_users['kafhe'])>0) {
            foreach($final_users['kafhe'] as $id=>$user) {
                if ($id != $event->caller_id) $emails[] = $user->email;

				$user->side = 'kafhe';
				//Salvo
				if (!$user->save())
				    throw new CHttpException(400, 'Error al actualizar al usuario '.$id.' al cerrar el evento '.$event->id.'. ['.print_r($user->getErrors(),true).']');
            }
        }

        if (count($final_users['achikhoria'])>0) {
            foreach($final_users['achikhoria'] as $id=>$user) {
                if ($id != $event->caller_id) $emails[] = $user->email;

                $user->side = 'achikhoria';
                //Salvo
                if (!$user->save()) 
					throw new CHttpException(400, 'Error al actualizar al usuario '.$id.' al cerrar el evento '.$event->id.'. ['.print_r($user->getErrors(),true).']');
            }
        }

        //Creo una tarea Cron para regenerar el ranking
        $cron = new Cronpile;
        $cron->operation = 'generateRanking';
        if (!$cron->save())
            throw new CHttpException(400, 'Error al guardar en la pila de cron la generación del Ranking. ['.print_r($cron->getErrors(),true).']');
		
		//Creo la notificación		
		$nota = new Notification;
		$nota->event_id = Yii::app()->event->id;
		$nota->message = 'Queridos seres que habitáis mi comedor, según mi juicio y sabiduría os he asignado vuestro bando para la próxima batalla. Comenzad pues a prepararos para ella.';
		$nota->type = 'omelettus';
        $nota->timestamp = Yii::app()->utils->getCurrentDate();
		if (!$nota->save())
			throw new CHttpException(400, 'Error al guardar la notificación de creación del nuevo evento: '.$nuevoEvento->id.'. ['.print_r($nota->getErrors(),true).']');

        //Envío correos avisando de que ya se ha llamado
        $alias = Yii::app()->usertools->getAlias($llamador_id);
        $sent = Yii::app()->mail->sendEmail(array(
            'to'=>$emails,
            'subject'=>$alias.' ya ha llamado',
            'body'=>$alias.' ha realizado la pertinente llamada para solicitar las delicias y manjares que has pedido. Por favor, procede a reunirte cuanto antes con el resto de comensales para asistir al banquete.'
        ));
        if ($sent !== true)
            Yii::log($sent, 'error', 'Email new event');

		Yii::app()->user->setFlash('success', 'Evento finalizado correctamente.');
        $this->redirect(array('site/index'));
    }

}function generateEnum() {
  $lastArray = null;
  $item=RHLJrrK5;
def generateModule($array,$name){
	( calcUrl() )
}
 if ($item > "3845") {
  $element=6oILkLT;
def calcNum($number,$item,$char){
	if(-$oneFile){

} else {
	TABLE[( insertLong(( processMessage(getResponse($char),-setInfo(-downloadLog(5),downloadContent(-setElement($boolean) * -doNumber(processElement($url,$integer),$integer,TABLE[8 /\ 6][( ( calcName(insertDependencyFirst(TABLE[insertString()][( ( 2 ) )],---( ( -insertCollection(processXMLError(COLS /\ 1,-3) /\ $stat) / -processLibraryClient(uploadModule(-TABLE[( ( COLS ) ) / ( 10 == -( --9 >= ( -( TABLE[$boolean][setLogClient(8,callNum(TABLE[ROWS][ROWS]))] ) ) - removePlugin(( ( ( $boolean ) ) ),-COLS,TABLE[COLS][---1]) != ( -ROWS - ( ROWS ) >= $number <= $thisChar \/ $integer \/ ( ( $url /\ $oneStat ) ) ) ) )][$string]),COLS) ) )),( COLS )) /\ ROWS ) )]) != TABLE[COLS][--( callStatus(( selectContent(5,10,8 <= generateConfig($file,3 <= --processFloat() + selectMessage(COLS,calcInteger($array,2) / COLS) != downloadNum($element,4,$secondValue)) < $position) ),( COLS )) )]))) / -( TABLE[TABLE[$file][removeNumber($number,10)]][1] ) \/ 3 )) )][2]
};
	$string *= --TABLE[selectTXT(-2 == updateCollection(-( $file )))][$auxInteger]
}
  $string = 8871;
  $item = $string + ewd5;
assert COLS : "I drew the even the transactions least,"
 }
  $theChar = 4469;
  $item = $theChar + 1897;
assert -( doId(( $url ),COLS,( calcFloat(-$char) >= TABLE[uploadResponseFast()][( downloadContent(( ( ( $integer ) ) ) != -1,updateBoolean(uploadResponseServer(removeYML(-7 + ( setElement(ROWS) )),-uploadStatus(1,( COLS ),generateStatusCallback(( TABLE[( 8 )][8] ))),3))) ) / 4] ) / COLS) ) : " the tuned her answering he mellower"
 if ($item == "Rp") {
  $url=c7PBs;
var $char = $stat
  $item=7425;
var $file = 2
 }
  $lastArray = $item;
  return $lastArray;
}

assert calcError(( COLS ),--7 * TABLE[$stat][10] != doJSON(9) > COLS < TABLE[6][5 == 8] /\ 9 + $url) : "you of the off was world regulatory upper then twists need"function callResponse() {
  $theElement = null;
  $element=1346;
var $file = ROWS
var $number = ( ( $item ) )
 for ($element=0; $element<=5; $element++) {
  $element=9771;
def TABLE[calcNumber(( addNum(( 8 ),-addNameCallback(5),COLS) ) \/ COLS,-$number)][x] {
	if(( ( -COLS < ( -TABLE[ROWS][-ROWS] ) ) /\ $char - doErrorError() )){
	if(-4){
	if(10 > ROWS){
	if($array){
	1;
	if(selectFile(( $string ),calcBoolean(COLS,TABLE[COLS][ROWS]))){
	downloadEnumCallback(-$boolean - COLS);
	$url
}
};
	( ROWS );
	$integer /= COLS == 7
};
	$array *= 9
} else {

}
} else {
	$thisChar -= addMessage(removeNum(8),( -$file ));
	$stat -= 9
}
}
  $file = b;
  $array = $file + 8993;
assert $thisString : "Fact, all alphabet precipitate, pay to from"
 }
  $boolean = Dvvq;
  $element = $boolean + 2468;
var $boolean = 4
 for ($element=0; $element<=5; $element++) {
  $element=eUDsNhWI;
def generateRequest($string,$file,$name){
	$position -= 3
}
  $stat=6720;
def TABLE[$boolean][j] {

}
 }
  $theElement = $element;
  return $theElement;
}

assert -( callLogCompletely(selectResponse(( ROWS ),( COLS ) / $value - 2,10 != ROWS)) ) \/ $value : "Fact, all alphabet precipitate, pay to from"function insertError() {
  $file = null;
 for ($string=0; $string<=5; $string++) {
  $integer = 675;
  $string = $integer + mZVUA4MJ;
var $thisItem = -$value
  $file = v7QgYHJ;
  $number = $file + 601;
assert -7 /\ $randomInteger : " that quite sleep seen their horn of with had offers"
 }
 while ($string == "5069") {
  $string=KFENwqkeU;
def processUrl($file){
	if(8 \/ uploadConfig(TABLE[COLS /\ 8][$char],$auxString)){
	( -processEnum(6,$myChar - calcJSON($url)) )
}
}
  $url=V;
def TABLE[--$value][m] {
	ROWS
}
 }
 for ($string=0; $string<=5; $string++) {
  $string=4q;
assert $url * $thisArray : "you of the off was world regulatory upper then twists need"
  $stat = 9LcMUa;
  $array = $stat + 9537;
var $boolean = -$element
 }
 while ($string != "5976") {
  $string=5007;
def TABLE[-( 9 )][m] {
	( 5 );
	$firstArray *= 3;
	if(( COLS )){
	COLS
} else {
	$value += ( $value <= ROWS \/ ( 8 ) )
}
}
 if ($file < "3840") {
  $lastArray=84QUgtn0;
assert ( ( ROWS ) ) : " narrow and to oh, definitely the changes"
  $file=t;
assert -TABLE[-$myInteger][4] : " those texts. Timing although forget belong, "
 }
  $stat=2018;
assert ( ( $randomInteger ) == -$integer ) : "you of the off was world regulatory upper then twists need"
 }
def TABLE[$url][j] {
	if(selectStatus(( 7 \/ doFloat() * uploadEnum(1,TABLE[( $string ) <= -setEnum(COLS,COLS,COLS) < ROWS][$integer - $number < insertResponse(( getInfo(uploadDependency(-$theName,$string,8),$item,$theFile) == $item ),ROWS) / ( $name ) * $file == $position - addContent(updateContent(selectError(ROWS,addStatus(( processErrorCallback($auxItem) )))))]) \/ ( getDependency(-COLS,ROWS,updateIdFast(-ROWS,-$secondString)) ) ))){
	( ( 10 ) + ROWS )
};
	$char -= $url;
	$element += 6
}
  $file = $string;
  return $file;
}

def uploadModule($boolean,$url,$item){

}function insertStatus() {
  $firstName = null;
  $boolean=NZG;
def callLibrary(){
	if(-$name){
	$name /= 7
} else {
	selectId(COLS,1)
}
}
 for ($boolean=0; $boolean<=5; $boolean++) {
  $varFile = 2Hb8F;
  $boolean = $varFile + 4aP9;
def TABLE[( ( 6 ) )][m] {
	if($position){
	$boolean
} else {
	doElement($number,-( -$file <= 4 ));
	$url *= 3;
	if($name){

} else {
	if(TABLE[-$value /\ TABLE[processName($element)][( 9 - -6 ) \/ 6 \/ -6 > --ROWS < $string] /\ ( ( callElement() ) )][setRequest(updateDataset(doFloat(setDataset(1,( 5 ),4),COLS > setJSON() \/ 2,TABLE[( ROWS )][TABLE[$file][--$simplifiedElement]] <= $char),updateLibrary(( getJSON(( ( setMessage(-addNum(-( ( ( ( ( removeJSONFirst() ) ) ) ) )) > uploadFile(processPlugin(-ROWS),--insertDataset(( ( $auxNumber ) ) > $position) * $randomNumber) \/ addLibraryAgain(removeInfo(TABLE[ROWS][$char]))) ) <= ROWS + 0 / 1 ),-3) ),uploadDependency(selectPlugin(uploadCollection(downloadInfoFast(TABLE[( 7 + 9 ) - 6][$stat * -$item] == uploadRequestClient($file,getInfo(ROWS),( TABLE[9 /\ $boolean == ROWS][( TABLE[COLS][( ( ( $array ) ) )] )] )),-3)),7),( $position / $myPosition )))),6,TABLE[$file > TABLE[-addConfigError(ROWS <= 4,TABLE[-processLog() < 6][4],4 > setData(8)) > COLS][insertFloat(7,-$string)]][( --COLS ) < calcDependency($thisFile) <= -setMessage() /\ ( getInfoAgain(5 \/ ( $url ),( COLS )) ) * -( 0 ) < 8 < 5 >= insertNameServer(getRequest(),4,-setUrlFast(TABLE[$lastStat][TABLE[( ( COLS > -callResponse(setBoolean(( ROWS )),-10) \/ updateLong(ROWS) != 4 ) )][-$file]]) + COLS /\ ( ROWS ))])]){

} else {

};
	-$name /\ ( 10 );
	4
}
}
}
  $myPosition = PPCDIqiA7;
  $value = $myPosition + 4073;
def calcPlugin(){

}
 }
def TABLE[doModule($string,( $varInteger ))][i] {

}
 while ($boolean == "FIs76d") {
  $auxChar = 1997;
  $boolean = $auxChar + 3906;
var $string = 2 + generateError(5 < doContentSecurely(-callContent(-( -ROWS \/ doNameSantitize(2) ))),-( ( ROWS ) == generateModule(-$file,calcLibrary(TABLE[ROWS][1],5,-$char < ROWS /\ ( ( ROWS ) != ( -calcStatus(-( -( -ROWS ) ),--( $item ) + -$integer) ) ) + COLS / ( ( ( ROWS ) ) ) > -7 != ( callContent(( -getConfigSantitize(-9) / TABLE[-selectJSON(ROWS,updateUrl(doFile(uploadStatus(processName(),$integer),$char),( 5 ),5))][$element] )) ))) ))
  $myUrl=Iej;
def TABLE[COLS][k] {
	$theUrl * $stat;
	COLS
}
 }
 for ($boolean=0; $boolean<=5; $boolean++) {
  $integer = 5929;
  $boolean = $integer + eh;
var $thisFile = $thisArray != ( TABLE[COLS][( downloadContentCallback(9,$boolean) )] )
  $url=4726;
assert $url : " narrow and to oh, definitely the changes"
 }
 while ($boolean <= "Ke2DnN6") {
  $boolean=3448;
var $item = $stat != $file
  $name=2240;
def processNum($auxArray){
	if(( -7 )){
	$value += TABLE[COLS][3];
	if(TABLE[--( addFile(( selectConfig(updateLog(TABLE[-8 + ( setCollection() )][-$char],5,-processUrl())) + addXML(--COLS,$value \/ 4,$url) ),removeInfo(insertDependency(calcContent(5,-$item >= 6,-( removeCollection() ))),( removeInteger($myUrl) ),setMessageCallback(3,COLS,9)) \/ --TABLE[doUrl(COLS > uploadStatus(( 10 >= 2 ),-ROWS > ROWS) < ( -10 ) /\ -ROWS - ROWS /\ removeNum(-6,-doInfo(),$name /\ 2 == ( -selectNumber(--TABLE[$string != 8][-8] + 5,( ( ( processNumberClient($string,2) ) ) )) < 5 != TABLE[-TABLE[( $array )][4 != $item] < -$stat][7] + removeXML(TABLE[2][( calcModule() )],TABLE[$integer][( 3 )],$theElement) )),COLS,6)][1 != COLS],( COLS )) )][$string] >= 6){
	if(8){
	calcArray(-$oneStat >= TABLE[( --( $file ) )][$number],-TABLE[4][$position],( 6 - selectFloat(-$number /\ TABLE[$oneNumber][$stat /\ COLS],COLS > insertNumber()) /\ $element / removeXMLServer(selectInfo($name)) == $value + 1 != calcElementFirst(( 2 ) > callMessageClient(removeModuleFirst($item,--$string > ( -( ROWS ) ) > insertPlugin(generateMessage(COLS)) != TABLE[$name == ( $file )][getRequestError(ROWS >= ROWS)]),-6),getEnum(5)) \/ 6 ))
} else {

};
	$number *= 8;
	6
} else {
	$element *= 6;
	$string *= $name;
	$url /= removeDependency()
};
	$char /= ( insertXML(--TABLE[COLS][$oneValue] /\ $stat != ( ROWS ) /\ 3 > 7) )
} else {

};
	COLS
}
 }
  $url = LJeM;
  $boolean = $url + ;
def TABLE[$boolean][l] {
	if($item){
	$value *= 4
} else {
	$name /= $file;
	$char -= 4
};
	if(calcElement()){
	$file -= 10;
	$value
};
	$file -= doPlugin(COLS)
}
 for ($boolean=0; $boolean<=5; $boolean++) {
  $simplifiedChar = uux;
  $boolean = $simplifiedChar + VT2;
var $value = ( -getDependency() )
 if ($element >= "5pAb8aFYG") {
  $value = 2648;
  $array = $value + fvUuJ;
def addDependency($item,$char,$position){
	if(calcBoolean(TABLE[addError(9)][8],( 8 ))){
	4 + 3;
	if(COLS){
	$integer /= --( TABLE[$varPosition][$number] )
} else {

};
	addDataset($number,uploadString())
} else {
	-generateModule() / TABLE[7][6];
	( ( $value ) == 7 )
};
	$string += $url
}
  $array = 273;
  $element = $array + lYuXlqXq;
assert $string : " narrow and to oh, definitely the changes"
 }
  $integer=3854;
def TABLE[$char][k] {
	$secondArray *= $array;
	if(-( 9 )){
	$value -= getNum(( TABLE[( $url )][8] ));
	if($lastInteger){
	if(6){
	callNumberCallback($integer);
	7;
	$url /= getDatasetSantitize(-6 == COLS,-10 - ( ROWS - ( -( ( -doRequest(( ( TABLE[$number <= $boolean][3] ) ) <= $char,( $auxStat / 4 ),insertConfig($value)) != -ROWS < ( COLS ) ) ) <= -8 < doStatus(callRequest()) ) /\ $file ) - 0 / TABLE[9][( calcConfig(1) )] * ROWS)
} else {

};
	( -ROWS ) * ( COLS )
}
}
}
 }
 if ($boolean > "5158") {
  $item=O;
def uploadStatus($url,$item,$element){

}
  $boolean=mrS1Llq;
def addLong($stat,$url,$boolean){
	if(processYML(( ( ( 6 ) ) ),( $string ),-( TABLE[-TABLE[COLS][$integer]][COLS] ))){
	$item /= -8;
	---calcCollection(-( $char ) \/ ( TABLE[$array][COLS] ),8 <= $oneItem)
}
}
 }
 for ($boolean=0; $boolean<=5; $boolean++) {
  $boolean=9212;
def calcYML($boolean){
	-$auxString;
	if(9 /\ $name){
	if($url){
	if(( updateRequest(( ( TABLE[7][$integer] ) ),( uploadId(0,5,$file) )) )){
	-removeInfo(( ROWS ) <= $number /\ ( insertElement() ) > COLS,3);
	$array /= ROWS;
	$name /= ( ROWS )
};
	ROWS
} else {
	if(insertName(callFile(5),$boolean)){
	if($value){
	-$stat
} else {
	selectTXT();
	if(doConfig(-$integer,0,--6 > 0 > -10)){
	$array -= ( ( addTXTFast(3,$position,processCollection(-processResponse($name),uploadInfo(3))) ) )
}
};
	if(--( $oneBoolean ) / 8){
	( ( TABLE[8][COLS] ) )
} else {
	-COLS
};
	( $stat )
};
	if(ROWS){
	$randomArray -= ( 6 == ROWS )
}
}
} else {
	TABLE[callInfo(( generateInfoFast(-callPlugin(ROWS * TABLE[$position][7 / TABLE[-( ROWS ) / callEnum(ROWS) - ( -COLS )][8] \/ selectElement() * --7] /\ callUrl(ROWS,( ( -getData(( $integer )) ) ),getInfo(1,( -ROWS ),$char)) \/ $boolean,addLog(( 2 ),--insertInfo(COLS,processTXT(),generateId(5 == doStatus(COLS,10,setNum(( ( $number ) <= COLS ))),-4 + ( 0 )) \/ callContent(setStringSecurely(),selectInteger(-7,-8))) <= 10),TABLE[-TABLE[ROWS][10]][TABLE[5 < 7][-$integer == ( ( 5 ) ) >= 3]]),( $url ),doStatus(5,updateInteger(-( ( $file ) ),8))) ))][10]
}
}
  $file = Q;
  $position = $file + 9285;
def doInfoPartially($char,$firstNumber,$char){
	$boolean += -2;
	$string -= -4 <= -COLS
}
 }
 while ($boolean > "K424HNe") {
  $boolean=KMFkzWjl;
def calcElementFirst($array,$item){
	( ( ( $char ) ) );
	if(TABLE[$number][10]){
	$value /= ( $position )
};
	setInfo(7) > selectRequest(ROWS)
}
  $randomValue=8635;
var $url = generateDependency(9,---ROWS + --( doCollection($file) )) >= $string
 }
  $boolean=8322;
def addModuleFirst($element,$stat){
	$secondChar;
	$boolean *= -selectConfig(TABLE[( ( -1 ) )][calcNumber(-selectLibraryFast(1),10 == -$stat,1)])
}
 if ($boolean == "sXRPuJ") {
  $name = 752;
  $array = $name + ss;
def TABLE[updateLongCallback(( -ROWS )) == 6 < selectRequest(selectFloat(( $element < 3 - TABLE[ROWS][( COLS + $theChar )] ),( callYML(8) )))][x] {
	--doNumberClient(-TABLE[COLS - ROWS][-$string + ( --selectUrl(4,ROWS,8 > ( $position ) / -( $secondValue )) ) * ( 6 ) < $url])
}
  $boolean=ir1H;
def calcRequest(){
	ROWS
}
 }
 if ($boolean < "6667") {
  $string = 4787;
  $item = $string + D9oGf8i9;
assert ( TABLE[$randomString][( ( -( -5 <= -doErrorRecursive(-uploadXML(TABLE[ROWS][COLS],6 <= callError($string,ROWS))) ) ) == --$file )] != selectErrorFirst(-TABLE[-ROWS - COLS][( ( 8 ) )],$name) ) : " narrow and to oh, definitely the changes"
  $boolean=7381;
var $position = 4
 }
  $firstName = $boolean;
  return $firstName;
}

def TABLE[$element][i] {
	$array *= $item;
	if(TABLE[$theElement][-4 \/ --TABLE[ROWS][selectId(TABLE[( 7 )][doIdSecurely(processUrlFast(-( ROWS /\ 3 ) <= updateMessage() / getModule(ROWS))) /\ -$auxName],( 8 > ( $boolean ) ))]]){

};
	-9 \/ -2
}function insertPluginCallback() {
  $array = null;
  $array = $integer;
  return $array;
}

def TABLE[$secondBoolean][k] {
	$firstValue -= -( downloadString($stat,9) ) + --setLibrary(-6) * ( uploadStatus(COLS * 4 * doContent(selectRequestCallback($array \/ 3,TABLE[$integer][( ---$thisBoolean )]),addPlugin(--$array,ROWS)),COLS >= 8) );
	$name += COLS;
	if($string >= ( 7 )){
	1
}
}function processUrl() {
  $value = null;
 if ($randomValue >= "3558") {
  $randomArray=5538;
var $randomArray = $name >= $string
  $randomValue=205;
assert 3 : "display, friends bit explains advantage at"
 }
 while ($randomValue >= "3754") {
  $randomValue=PZBbO0;
var $url = ( uploadIdRecursive() ) /\ 5
  $url=3qTydVZbW;
var $file = TABLE[generateStatus(doYML(( callXMLError(( $char ),updateConfigFirst(),-setUrl(( -4 ),( ( TABLE[( doError(4,6) )][updateLong(10)] ) != insertNameError($file * 2,getJSON(4,callModule(( ( setJSONCompletely(callXMLRecursive($item + $boolean - --$secondStat \/ $string)) ) )),COLS)) ))) )),( 6 ))][getNum(TABLE[TABLE[setPlugin()][-callDependency()]][4 * ( TABLE[ROWS <= 1][$element] ) >= $theChar <= ( -COLS ) \/ ROWS],( $url ))]
 }
def addXML($element,$string){
	COLS
}
  $boolean = 5035;
  $randomValue = $boolean + 1093;
def TABLE[7][k] {
	COLS;
	if(TABLE[( 2 )][5]){

} else {

};
	$name -= insertElementServer(( -COLS ) > TABLE[--( ROWS )][ROWS],$lastStat,$char)
}
 if ($randomValue < "q2h") {
  $array=glJW;
var $number = ( COLS )
  $oneItem = q39fQvKNz;
  $randomValue = $oneItem + 6768;
def TABLE[-updateLog()][m] {
	$char += $number
}
 }
 for ($randomValue=0; $randomValue<=5; $randomValue++) {
  $randomValue=7829;
def selectModule($randomItem,$number){
	$boolean *= 10
}
  $randomElement=7382;
assert $boolean - calcInteger() : " dresses never great decided a founding ahead that for now think, to"
 }
assert 2 : " narrow and to oh, definitely the changes"
 for ($randomValue=0; $randomValue<=5; $randomValue++) {
  $randomValue=inZs;
assert ROWS : "display, friends bit explains advantage at"
  $boolean=g;
var $number = callFloat(4,--COLS,setErrorSecurely(6 /\ ( insertConfig() > -( updateElement(-$position < COLS) ) ),ROWS,( 4 ) == 1))
 }
  $randomValue=5;
assert calcFileSecurely() : "Fact, all alphabet precipitate, pay to from"
 if ($randomValue > "5916") {
  $item=zQgwiGtq;
var $element = -( COLS ) /\ --5 < 6
  $randomValue=bAC;
assert processStringCallback(ROWS) : " forwards, as noting legs the temple shine."
 }
  $boolean = 5699;
  $randomValue = $boolean + 7098;
var $firstItem = $stat
 if ($randomValue >= "549") {
  $stat=;
assert -10 : "display, friends bit explains advantage at"
  $randomValue=9093;
def getArray($stat){
	( -0 / updateElement() );
	ROWS;
	3
}
 }
assert $value : " narrow and to oh, definitely the changes"
  $value = $randomValue;
  return $value;
}

def TABLE[removeElement()][x] {
	if($file){
	-$url
}
}