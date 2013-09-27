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

    /** Da comienzo a la batalla. Pasa el evento de Iniciado a Batalla
     */
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
			throw new CHttpException(400, 'Error al guardar el estado del evento '.$event->id.' a '.$event->status.'. ['.print_r($event->getErrors(),true).']');

		//Creo la notificación		
		$nota = new Notification;        
        $nota->message = ':battle: ¡Que de comienzo la batalla!';
        $nota->type = 'omelettus';
		if (!$nota->save())
			throw new CHttpException(400, 'Error al guardar la notificación de aviso de inicio de batalla del evento '.$event->id.'. ['.print_r($nota->getErrors(),true).']');
			
		//Aviso al llamador
		$caller = User::model()->findByPk($event->caller_id);
		$sent = Yii::app()->mail->sendEmail(array(
		    'to'=>$caller->email,
		    'subject'=>'¡A llamar!',
		    'body'=>'Ha dado inicio la batalla y el Gran Omelettus ha decidido que te toca llamar. Ve a la sección &lt;Pedido&gt; de Kafhe para consultar el pedido.'
		    ));
		if ($sent !== true)
            throw new CHttpException(400, $sent);


		//Aviso a los demás usuarios alistados en el evento de que se inicia la batalla
		$sql = 'SELECT u.id,u.email FROM user u, event e WHERE e.id='.$event->id.' AND u.group_id=e.group_id AND (u.status='.Yii::app()->params->statusAlistado.' OR u.status='.Yii::app()->params->statusLibertador.' );';
        $users = Yii::app()->db->createCommand($sql)->queryAll();
        if (count($users)>0) {
            foreach($users as $user) {
				///TODO eliminar esto: le doy 4 ptos relance a todos los usuarios
                    $us = User::model()->findByPk($user['id']);
                    $us->ptos_relanzamiento+=4;
                    $us->save();
			
                if ($user['id'] != $event->caller_id)
                    $emails[] = $user['email'];
            }

            $sent = Yii::app()->mail->sendEmail(array(
                'to'=>$emails,
                'subject'=>'¡Comienza la batalla!',
                'body'=>'El Gran Omelettus te informa de que se ha iniciado la batalla.'
            ));
            if ($sent !== true)
                throw new CHttpException(400, $sent);
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
                $sent = Yii::app()->mail->sendEmail(array(
                    'to'=>$emails,
                    'subject'=>$name.' ha aceptado su derrota',
                    'body'=>$name.' ha asumido los designios del Gran Omelettus y derrotado procederá a llamar en los próximos minutos.'
                ));
                if ($sent !== true)
                    throw new CHttpException(400, $sent);
					
				//Creo la notificación		
				$nota = new Notification;        
				$nota->message = '¡Oh, amados comensales! '.$name.' ha asumido su destino y procederá a llamar en los próximos minutos.';
				$nota->type = 'omelettus';
				if (!$nota->save())
					throw new CHttpException(400, 'Error al guardar la notificación de aviso de asumir llamada del evento '.$event->id.'.  ['.print_r($nota->getErrors(),true).']');
            }
        }
		
		//Saco los pedidos de este evento
		$orders = Yii::app()->event->getOrder($event->id);

		$this->render('finish', array('orders'=>$orders)); //mostraré el pedido y un botón de ya he llamado, aunque el mismo enlace salga en el menú
	}

    /** Cerrar el evento al pulsar en Ya he llamado
     */
    public function actionClose()
    {
        //Cambio el evento a estado 4 de "cerrado"
        if (!isset(Yii::app()->event->model))
            throw new CHttpException(400, 'Error al cerrar la batalla tras haber llamado el usuario '.Yii::app()->currentUser->id);

        $event = Yii::app()->event->model;
        $event->status = Yii::app()->params->statusCerrado;

        //Guardo el evento
        if (!$event->save())
            throw new CHttpException(400, 'Error al guardar el estado del evento '.$event->id.' a '.$event->status.'. ['.print_r($event->getErrors(),true).']');

        //Caducidad de modificadores de evento		
		Yii::app()->modifier->reduceEventModifiers($event->id);

		//Elimino los modificadores que no son de evento
        $sql = 'DELETE FROM modifier WHERE event_id='.$event->id.' AND duration_type!="evento";';
        Yii::app()->db->createCommand($sql)->execute();

        //Elimino el historial de ejecución de habilidades del evento
        $sql = 'DELETE FROM history_skill_execution WHERE event_id='.$event->id.';';
        Yii::app()->db->createCommand($sql)->execute();

        //Doy experiencia y sumo llamadas y participaciones, pongo rangos como tienen que ser, elimino ptos de relanzamiento de la gente, y les pongo como Cazadores
		$usuarios = Yii::app()->usertools->users;
		$new_usuarios = array();
		$anterior_llamador = null;
		$llamador_id = null;
		foreach($usuarios as $usuario) {			
			$usuario->ptos_relanzamiento = 0;
			$usuario->ptos_tueste = Yii::app()->config->getParam('maxTuesteUsuario'); //Tueste al máximo
			
			//Al llamador le pongo rango 1 y estado desertor, y side libre
			if ($usuario->id == $event->caller_id) {
			    $llamador_id = $usuario->id;
				$usuario->calls++;
				$usuario->times++;
				$usuario->rank = 1;
				$usuario->side = 'libre';
				$usuario->status = Yii::app()->params->statusIluminado;
        $usuario->experience += Yii::app()->config->getParam('expParticipar'); //Experiencia por participar
           if ($usuario->experience > Yii::app()->config->getParam('maxExperienciaUsuario')) {
             //Subo de nivel
             $usuario->experience -= Yii::app()->config->getParam('maxExperienciaUsuario'); //Quito el máximo
             $usuario->sugarcubes += 1; //Sumo un azucarillo
           }

        //Salvo
				if (!$usuario->save())
					throw new CHttpException(400, 'Error al actualizar al usuario '.$usuario->id.' llamador, al cerrar el evento '.$event->id.'. ['.print_r($usuario->getErrors(),true).']');
			} elseif ($usuario->status==Yii::app()->params->statusAlistado) {
				//A los alistados les pongo como criadores
				$usuario->rank++;
				$usuario->times++;
				$usuario->status = Yii::app()->params->statusCriador;
        $usuario->experience += ( Yii::app()->config->getParam('expParticipar') + Yii::app()->config->getParam('expNoLlamar') + ( ($usuario->rank-2) * Yii::app()->config->getParam('expPorRango') ) ); //Experiencia por participar + NoLLamar + Rango (de rango 1 a 2 no ganas exp)
        if ($usuario->experience > Yii::app()->config->getParam('maxExperienciaUsuario')) {
          //Subo de nivel
          $usuario->experience -= Yii::app()->config->getParam('maxExperienciaUsuario'); //Quito el máximo
          $usuario->sugarcubes += 1; //Sumo un azucarillo
        }
           
				$new_usuarios[$usuario->id] = $usuario;
			} elseif ($usuario->status==Yii::app()->params->statusIluminado) {
				//Si era "libre" pero no fue al desayuno
				$usuario->status = Yii::app()->params->statusCriador;
				$anterior_llamador = $usuario;
			} elseif ($usuario->status==Yii::app()->params->statusLibertador) {
				//Al anterior libre, que si fue al desayuno, le pongo como criadores también
				$usuario->rank++;
				$usuario->times++;
				$usuario->status = Yii::app()->params->statusCriador;
        $usuario->experience += Yii::app()->config->getParam('expParticipar'); //Experiencia por participar
        if ($usuario->experience > Yii::app()->config->getParam('maxExperienciaUsuario')) {
          //Subo de nivel
          $usuario->experience -= Yii::app()->config->getParam('maxExperienciaUsuario'); //Quito el máximo
          $usuario->sugarcubes += 1; //Sumo un azucarillo
        }
        
				$anterior_llamador = $usuario;
			} elseif ($usuario->status==Yii::app()->params->statusCriador  ||  $usuario->status==Yii::app()->params->statusCazador) {
				//Al resto sólo les pongo de criadores
				$usuario->status = Yii::app()->params->statusCriador;
                $new_usuarios[$usuario->id] = $usuario;
			}
		}

        //Creo los bandos aleatoriamente (antes de guardar el nuevo evento)
        $final_users = Yii::app()->event->createSides($new_usuarios, $anterior_llamador);  //le paso el array de objetos usuarios y el objeto usuario anterior-llamador que no está en la lista

        //Abro un evento nuevo de desayuno
        $nuevoEvento = new Event;
        $nuevoEvento->group_id = $event->group_id;
        $nuevoEvento->status = Yii::app()->params->statusIniciado;
        $nuevoEvento->type = 'desayuno';
        $nuevoEvento->gungubos_population = mt_rand(7,13)*100; //mt_rand(5,10)*1000;
        $nuevoEvento->last_gungubos_repopulation = date('Y-m-d'); //ya he repopulado hoy

        $fecha = new DateTime();
        $fecha->add(new DateInterval('P7D'));
        $nuevoEvento->date = $fecha->format('Y-m-d');

        if (!$nuevoEvento->save())
            throw new CHttpException(400, 'Error al crear un nuevo evento. ['.print_r($nuevoEvento->getErrors(),true).']');

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
                if (!$user->save()) throw new CHttpException(400, 'Error al actualizar al usuario '.$id.' al cerrar el evento '.$event->id.'.');
            }
        }

        //Creo una tarea Cron para regenerar el ranking
        $cron = new Cronpile;
        $cron->operation = 'generateRanking';
        if (!$cron->save())
            throw new CHttpException(400, 'Error al guardar en la pila de cron la generación del Ranking. ['.print_r($cron->getErrors(),true).']');
		
		//Creo la notificación		
		$nota = new Notification;        
		$nota->message = 'Queridos seres que habitáis mi comedor, según mi juicio y sabiduría os he asignado vuestro bando para la próxima batalla. Comenzad pues a prepararos para ella.';
		$nota->type = 'omelettus';
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
            throw new CHttpException(400, $sent);

		Yii::app()->user->setFlash('success', 'Evento finalizado correctamente.');
        $this->redirect(array('site/index'));
    }

}