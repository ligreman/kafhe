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
			array('allow', 
				'actions'=>array('start'),
				'roles'=>array('Usuario'),
				'expression'=>"(isset(Yii::app()->event->model) && Yii::app()->event->status==Yii::app()->params->statusIniciado && Yii::app()->user->checkAccess('lanzar_evento'))", //Dejo entrar
			),
			array('allow', 
				'actions'=>array('finish'),
				'roles'=>array('Usuario'),
				'expression'=>"(isset(Yii::app()->event->model) && Yii::app()->event->status==Yii::app()->params->statusBatalla && isset(Yii::app()->event->caller) && Yii::app()->event->caller==Yii::app()->user->id)", //Dejo entrar 
			),
            array('allow',
                'actions'=>array('close'),
                'roles'=>array('Usuario'),
                'expression'=>"(isset(Yii::app()->event->model) && Yii::app()->event->status==Yii::app()->params->statusFinalizado && isset(Yii::app()->event->caller) && Yii::app()->event->caller==Yii::app()->user->id)", //Dejo entrar
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

	public function actionStart()
	{
		//Cambio el evento a estado 2 (batalla!!)
		if (!isset(Yii::app()->event->model))
			throw new CHttpException(400, 'Error al iniciar la batalla ya que no hay ningún evento activo.');
		
		$event = Yii::app()->event->model;
		$event->status = Yii::app()->params->statusBatalla;
					
		//Elijo al primer llamador
		$battleResult = Yii::app()->event->selectCaller();
		$event->caller_id = $battleResult['userId'];
		$event->caller_side = $battleResult['side'];
		
		//Guardo el evento
		if (!$event->save())
			throw new CHttpException(400, 'Error al guardar el estado del evento '.$event->id.' a '.$event->status.'.');


		//Aviso al llamador
		$caller = User::model()->findByPk($event->caller_id);
		$sent = Yii::app()->mail->sendEmail(array(
		    'to'=>$caller->email,
		    'subject'=>'¡A llamar!',
		    'body'=>'Ha dado inicio la batalla y el gran omelettus ha decidido que te toca llamar.'
		    ));
		if ($sent !== true)
            throw new CHttpException(400, $sent);


		//Aviso a los demás usuarios alistados en el evento de que se inicia la batalla
		$sql = 'SELECT u.email FROM user u, event e WHERE e.id='.$event->id.' AND u.group_id=e.group_id AND u.status='.Yii::app()->params->statusAlistado.';';
        $users = Yii::app()->db->createCommand($sql)->queryAll();
        if (count($users)>0) {
            foreach($users as $user) {
                if ($user['id'] != $event->caller_id)
                    $emails[] = $user['email'];
            }

            $sent = Yii::app()->mail->sendEmail(array(
                'to'=>$emails,
                'subject'=>'¡Comienza la batalla!',
                'body'=>'El gran omelettus te informa de que se ha iniciado la batalla.'
            ));
            if ($sent !== true)
                throw new CHttpException(400, $sent);
        }


        Yii::app()->user->setFlash('success', '¡Ha comenzado la batalla!');
		$this->redirect(array('event/index'));
		//$this->render('start');
	}

	//Finaliza la batalla y mostrará el botón de ya he llamado
	public function actionFinish()
	{
        //Cambio el evento a estado 3 de "asumo mi derrota"
        if (!isset(Yii::app()->event->model))
            throw new CHttpException(400, 'Error al finalizar la batalla asumiendo la derrota del usuario '.Yii::app()->user->id);

        $event = Yii::app()->event->model;
        $event->status = Yii::app()->params->statusFinalizado;

        //Guardo el evento
        if (!$event->save())
            throw new CHttpException(400, 'Error al guardar el estado del evento '.$event->id.' a '.$event->status.'.');

        //Aviso a todos de que asumo mi derrota
        $sql = 'SELECT u.email FROM user u, event e WHERE e.id='.$event->id.' AND u.group_id=e.group_id AND u.status='.Yii::app()->params->statusAlistado.';';
        $users = Yii::app()->db->createCommand($sql)->queryAll();
        if (count($users)>0) {
            foreach($users as $user) {
                if ($user['id'] != $event->caller_id)
                    $emails[] = $user['email'];
            }

            $sent = Yii::app()->mail->sendEmail(array(
                'to'=>$emails,
                'subject'=>Yii::app()->user->alias.' ha aceptado su derrota',
                'body'=>Yii::app()->user->alias.' ha asumido los designios del Gran Omelettus y derrotado procederá a llamar en los próximos minutos.'
            ));
            if ($sent !== true)
                throw new CHttpException(400, $sent);
        }

		$this->render('finish'); //mostraré el pedido y un botón de ya he llamado, aunque el mismo enlace salga en el menú
	}

	//Cerrar el evento
	public function actionClose()
    {
        //Cambio el evento a estado 3 de "asumo mi derrota"
        if (!isset(Yii::app()->event->model))
            throw new CHttpException(400, 'Error al cerrar la batalla tras haber llamado el usuario '.Yii::app()->user->id);

        $event = Yii::app()->event->model;
        $event->status = Yii::app()->params->statusCerrado;

        //Guardo el evento
        if (!$event->save())
            throw new CHttpException(400, 'Error al guardar el estado del evento '.$event->id.' a '.$event->status.'.');

        $sql = 'SELECT u.email FROM user u, event e WHERE e.id='.$event->id.' AND u.group_id=e.group_id AND u.status='.Yii::app()->params->statusAlistado.';';
        $users = Yii::app()->db->createCommand($sql)->queryAll();

        //Caducidad de modificadores de evento		
		Yii::app()->usertools->reduceEventModifiers($event->group_id);

        ///TODO doy experiencia y sumo llamadas y participaciones, pongo rangos como tienen que ser, elimino ptos de relanzamiento de la gente, y les pongo como Cazadores
        //si suben de nivel ganan azucarillo


        //Abro un evento nuevo de desayuno
        $nuevoEvento = new Event;
        $nuevoEvento->group_id = $event->group_id;
        $nuevoEvento->status = Yii::app()->params->statusIniciado;
        $nuevoEvento->type = 'desayuno';

        $fecha = new DateTime();
        $fecha->add(new DateInterval('P7D'));
        $nuevoEvento->date = $fecha->format('Y-m-d');

        if (!$nuevoEvento->save())
            throw new CHttpException(400, 'Error al crear un nuevo evento.');

        ///TODO creo los bandos aleatoriamente
        //Yii::app()->event->createSides($goupId);


        //Aviso a todos de que ya he llamado
        if (count($users)>0) {
            foreach($users as $user) {
                if ($user['id'] != $event->caller_id)
                    $emails[] = $user['email'];
            }

            $sent = Yii::app()->mail->sendEmail(array(
                'to'=>$emails,
                'subject'=>Yii::app()->user->alias.' ya ha llamado',
                'body'=>Yii::app()->user->alias.' ha realizado la pertinente llamada para solicitar las delicias y manjares que has pedido. Por favor, procede a reunirte cuanto antes con el resto de comensales para asistir al banquete.'
            ));
            if ($sent !== true)
                throw new CHttpException(400, $sent);
        }

        $this->redirect(array('event/index'));
    }

}