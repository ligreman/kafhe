<?php

/*
	In your crontab, execute yiic dentro de protected: yiic <command-name> <action-name> --option1=value1 --option2=value2 ...
	
	Ejemplos:
	yiic test index --param1=News --param2=5 --param3=valorArray1 --param3=valorArray2 --param3=valorArray3

	// $param2 takes default value
	yiic test index --param1=News --param3=valorArray1
	
	//parametros globales
	yiic test index --global_param=8
	
	--------------------------------------------------------------------------------------------------------------------------
	
	Run yiic migrate in an action 
	Code
	private function runMigrationTool() {
		$commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
		$runner = new CConsoleCommandRunner();
		$runner->addCommands($commandPath);
		$commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
		$runner->addCommands($commandPath);
		$args = array('yiic', 'migrate', '--interactive=0');
		ob_start();
		$runner->run($args);
		echo htmlentities(ob_get_clean(), null, Yii::app()->charset);
	}
	How to use
	You can call it from a controller action like this:

	actionMigrate() {
		$this->runMigrationTool();
	}

    --------------------------------------------------------------------------------------------------------------------------

    $time = time();
    echo date('d-m-Y H:m:s')."\n";
    echo $time."\n";
    echo date('d-m-Y H:m:s', $time)."\n";
    echo print_r(getdate($time),true)."\n";
    echo print_r(getdate(strtotime(date('d-m-Y H:i:s'))),true);

    --------------------------------------------------------------------------------------------------------------------------

    //Si al yiic cron no se le pasa ninguna acción ejecuta la Index
    public function actionIndex ()
    {
        echo "Index";
        return 0;
    }

    //Ejemplo de acción
    public function actionParams ($param1, $param2='default', array $param3)
    {
        // here we are doing what we need to do
        echo "ok";
        return 0;
    }

    //NOTA no necesitaré comprobar modificadores ya que lo compruebo en cada carga de página y al regenerar tueste en el Cron
*/


// #Cada 10 minutos regenero tueste
// */10 * * * * /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron regenerarTueste

// #Cada hora activo a los criadores
// * */1 * * * /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron criadores

// #Cada hora entre las 7-18 miro a ver si repueblo gungubos
// #5 7-18 * * * /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron repopulateGungubos

// #Cada hora compruebo si hay algo en cola del cronPile
// * */1 * * * /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron processCronPile

// #Los jueves por la noche (el servidor tiene otra hora) pone los eventos en Calma
// 0 22 * * 4 /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron eventosEnCalma

// #Los lunes a las 9 de la mañana pongo los eventos en Preparativos
// 0 8 * * 1 /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron iniciarEventos

// #Todos los días a las 4 de la mañana hago backup de base de datos
// 0 3 * * * sh /home/kafhe/mysql_backup.sh



class CronCommand extends CConsoleCommand {
    public $global_param = true;

    /** 10 minutos. Regenera el tueste del usuario $userId (ID User). Además comprueba si está activo o no el usuario.
     */
    public function actionRegenerarTueste()
    {
        $this->logCron('Compruebo caducidad de modificadores.', 'info');
        Yii::app()->modifier->checkModifiersExpiration();

        $this->logCron('Iniciando regeneracion y chequeo de estado.', 'info');

        $grupos = Group::model()->findAll(array('condition'=>'active=1'));

        foreach($grupos as $grupo) {
            //Evento del grupo en estado Iniciado
            $event = Event::model()->find(array('condition'=>'group_id=:groupId AND type=:tipo AND status=:estado', 'params'=>array(':groupId'=>$grupo->id, ':tipo'=>'desayuno', ':estado'=>Yii::app()->params->statusIniciado)));
            if ($event === null) {
                $this->logCron('  Evento del grupo '.$grupo->id.' no Iniciado', 'info');
                continue; // Si el evento de este grupo no está en estado Iniciado no regenero tueste
            }

            $this->logCron('  Usuarios del grupo '.$grupo->name.' ('.$grupo->id.').', 'info');
            $usuarios = User::model()->findAll(array('condition'=>'group_id=:groupId', 'params'=>array(':groupId'=>$grupo->id)));

            foreach ($usuarios as $usuario) {
                $this->logCron('    Usuario '.$usuario->username.' (rango '.$usuario->rank.'):', 'info');
                $regenerado = Yii::app()->tueste->getTuesteRegenerado($usuario);

                if ($regenerado !== false) {
                    //Si ya estoy al máximo de tueste cambio mi estado a Criador, si soy Cazador, y miro el desborde
                    $usuario->ptos_tueste += $regenerado;

                    if ($usuario->ptos_tueste > intval(Yii::app()->config->getParam('maxTuesteUsuario')) )
                        $usuario->ptos_tueste = intval(Yii::app()->config->getParam('maxTuesteUsuario'));

                    $usuario->last_regen_timestamp = date('Y-m-d H:i:s');
                    $this->logCron('        - Tueste regenerado: '.$regenerado.'.', 'info');
                } else
                    $this->logCron('        - Todavia no puede regenerar tueste.', 'info');


                //Si el usuario estaba inactivo, le resto fama
                if ($usuario->status == Yii::app()->params->statusInactivo)
                    $usuario->fame -= intval(Yii::app()->config->getParam('lostFameByInactivity'));
                else {
                    //Compruebo si está inactivo el usuario
                    $user_inactive_time = strtotime($usuario->last_activity) + 25*60*60; //Le sumo 25 horas para ver si ha pasado
                    if (time() > $user_inactive_time) {
                        $usuario->status = Yii::app()->params->statusInactivo;
                        $this->logCron('        - Estado inactivo.', 'info');
                    }
                }

                if (!$usuario->save())
                    $this->logCron('** ERROR al guardar el usuario ('.$usuario->id.') regenerando su tueste.', 'info');


            }

            //Guardo el evento
            /*if (!$event->save())
                $this->logCron('** ERROR al guardar el evento ('.$event->id.') guardando el tueste desbordado.', 'info');*/
        } //foreach group

        return 0;
    }



    /** Los criadores cazan gungubos cada hora para cada bando
     */
    /*public function actionCriadores()
    {
        $this->logCron('Compruebo caducidad de modificadores.', 'info');
        Yii::app()->modifier->checkModifiersExpiration();


        //Para todos los eventos de estado "iniciado" (1)
        $events = Event::model()->findAll(array('condition'=>'status=:status', 'params'=>array(':status'=>Yii::app()->params->statusIniciado)));
        if ($events != null) {
            foreach($events as $event) {
                //Reparto el tueste (*NEW)
                //Compruebo si ha pasado el tiempo suficiente para criar en el evento
                $last_born = strtotime($event->last_gungubos_criadores);
                if (time() < ($last_born + intval(Yii::app()->config->getParam('tiempoCriaGungubos')) ) ) {
                    $this->logCron('Todavia no puedo activar a los criadores en el evento '.$event->id.'.', 'info');
                    continue;
                }

                $tuesteRestante = Yii::app()->tueste->repartirTueste($event);
                $this->logCron('Reparto el tueste que sobra: '.$tuesteRestante['kafhe'].' kafhe; '.$tuesteRestante['achikhoria'].' achikhoria.', 'info');

                //Guardo el evento
                $event->stored_tueste_kafhe = $tuesteRestante['kafhe'];
                $event->stored_tueste_achikhoria = $tuesteRestante['achikhoria'];
                $event->last_gungubos_criadores = date('Y-m-d H:i:s');

                if (!$event->save())
                    $this->logCron('** ERROR al guardar el evento ('.$event->id.') activando criadores.', 'info');
            }
        } else
            $this->logCron('No hay eventos Iniciados.', 'info');

        return 0;
    }*/

    /** 1 hora Repuebla los gungubos cada hora
     */     
    public function actionGungubosLifecycle()
    {
        $this->logCron('Iniciando la repoblacion.', 'info');

        //Para todos los eventos de estado "iniciado" (1)
        $events = Event::model()->findAll(array('condition'=>'type=:tipo AND status=:estado', 'params'=>array( ':tipo'=>'desayuno', ':estado'=>Yii::app()->params->statusIniciado)));
        if ($events != null) {
            foreach($events as $event) {
                //Miro a ver si ha pasado una hora desde la última repoblación
                if ($event->last_gungubos_repopulate_timestamp!="") {
                    $last_repopulate = strtotime($event->last_gungubos_repopulate_timestamp);

                    if (time() < ($last_repopulate + 3600)) {
                        $this->logCron('    Todavia no puedo repoblar gungubos en el evento '.$event->id.'.', 'info');
                        continue;
                    }
                }

                $this->logCron('  Repoblando en el evento '.$event->id.'.', 'info');
                $gungubosNuevosSQL = array();
				
				//Quito contador a Gungubos, tanto los que tienen Criadores como los que no
				$this->reduceHealthGungubos($event->id);

                //Repueblo gungubos en cada corral de los jugadores
                $jugadores = User::model()->findAll(array('condition'=>'group_id=:grupo', 'params'=>array(':grupo'=>$event->group_id)));
                foreach($jugadores as $jugador) {
                    //Calculo cuántos le toca
                    $gungubos_en_corral = intval(Gungubo::model()->count(array('condition'=>'owner_id=:owner AND location=:lugar', 'params'=>array(':owner'=>$jugador->id, ':lugar'=>'corral'))) );
                    $max_repoblar = max($gungubos_en_corral, Yii::app()->config->getParam('maxGungubosCorral'));

                    $a_repoblar = mt_rand(6,10); // 5 + [1-5]
                    $a_repoblar = min($max_repoblar, $a_repoblar); //Calculo cuantos caben en el corral

                    $this->logCron('     + Para el jugador '.$jugador->id.' corresponden '.$a_repoblar.' gungubos.', 'info');

                    for($i=1; $i<=$a_repoblar; $i++) {
                        $gungubosNuevosSQL[] = "(".$event->id.", ".$jugador->id.", ".Yii::app()->config->getParam('gunguboHealth').", 'corral', 'normal', '".date('Y-m-d H:i:s')."')"; //event_id, owner_id, health, location, condition
                    }

                    //Notificación de corral para el jugador
                    $noti = new NotificationCorral;
                    $noti->event_id = $event->id;
                    $noti->user_id = $jugador->id;
                    $noti->message = 'Repoblados '.$a_repoblar.' Gungubos en tu corral.';

                    if (!$noti->save())
                        $this->logCron('** ERROR al guardar la notificación de gungubos repoblados para el jugador '.$jugador->username.' del evento ('.$event->id.').', 'info');
                }              

                //Salvo los gungubos nuevos
                Yii::app()->db->createCommand('INSERT INTO gungubo (`event_id`, `owner_id`, `health`, `location`, `condition_status`, `birthdate`) VALUES '.implode(',', $gungubosNuevosSQL).';')->query();

                $event->last_gungubos_repopulate_timestamp = date('Y-m-d H:i:s'); //Actualizo la hora de repoblación
                if (!$event->save())
                    $this->logCron('** ERROR al guardar el evento ('.$event->id.') repoblando gungubos', 'info');

                $this->logCron('    Repoblados gungubos en el evento '.$event->id.'.', 'info');
            }
        }

        return 0;
    }

    /** 30 minutos. Reduce contadores de Gungubos sin criadores */
    public function actionReduceHealthGungubosWithoutNurse()
    {
        $this->logCron('Reduzco los contadores de los Gungubos que no tienen un Criador en el corral.', 'info');

        //Para todos los eventos de estado "iniciado" (1)
        $events = Event::model()->findAll(array('condition'=>'type=:tipo AND status=:estado', 'params'=>array( ':tipo'=>'desayuno', ':estado'=>Yii::app()->params->statusIniciado)));
        if ($events != null) {
            foreach($events as $event) {
                $this->logCron('  Corrales del evento '.$event->id.'.', 'info');

                //Repueblo gungubos en cada corral de los jugadores
                $jugadores = User::model()->findAll(array('condition'=>'group_id=:grupo', 'params'=>array(':grupo'=>$event->group_id)));
                foreach($jugadores as $jugador) {
                    $this->logCron('     Corral del jugador '.$jugador->username.'.', 'info');

                    //Saco sus Gumbudos Criadores
                    $gumbudos = Gumbudo::model()->count(array('condition'=>'owner_id=:owner AND event_id=:evento AND class=:clase', 'params'=>array(':owner'=>$jugador->id, ':evento'=>$event->id, ':clase'=>Yii::app()->params->gumbudoClassCriador)));

                    if (intval($gumbudos)==0) {
                        //Quito contador a los Gungubos de este jugador que no tiene Criador
                        $this->reduceHealthGungubos($event->id, $jugador->id);
                        $this->logCron('      - No tiene Criador.', 'info');
                    } else {
                        //Si tiene criadores le doy fama
                        $jugador->fame += 1;
                        if (!$jugador->save())
                            $this->logCron('** ERROR al actualizar la fama del jugador ('.$jugador->id.') por tener Criadores.', 'info');
                    }
                }
            }
        }

        return 0;
    }

    /** 1 hora Cada hora ciclo de vida de gumbudos: activo guardianes,
     */
    public function actionGumbudosLifecycle() {
        $this->logCron('Ciclo de vida de los Gumbudos.', 'info');

        //Para todos los eventos de estado "iniciado" (1)
        $events = Event::model()->findAll(array('condition'=>'type=:tipo AND status=:estado', 'params'=>array( ':tipo'=>'desayuno', ':estado'=>Yii::app()->params->statusIniciado)));

        if ($events != null) {
            foreach($events as $event) {
                $this->logCron('  Comprobando el evento '.$event->id.'.', 'info');

                //Activo a los Gumbudos Guardianes normales para que defiendan.
                Gumbudo::model()->updateAll(array('actions'=>Yii::app()->config->getParam('gumbudoGuardianActions')),'event_id=:evento', array(':evento'=>$event->id));

                //Para los gumbudos guardianes con trait Acorazado es una defensa más de la de por defecto
                Gumbudo::model()->updateAll(array('actions'=>'('.intval(Yii::app()->config->getParam('gumbudoGuardianActions').'+trait_value)')),'event_id=:evento AND trait=:trait', array(':evento'=>$event->id, 'trait'=>Yii::app()->params->traitAcorazado));

                $this->logCron('    Activados los Gumbudos guardianes en el evento '.$event->id.'.', 'info');
            }
        }

        return 0;
    }

    /** 5 minutos
     */
    public function actionMuerteGumbudos() {
        $this->logCron('Muerte de Gumbudos.', 'info');

        //Para todos los eventos de estado "iniciado" (1)
        $events = Event::model()->findAll(array('condition'=>'type=:tipo AND status=:estado', 'params'=>array( ':tipo'=>'desayuno', ':estado'=>Yii::app()->params->statusIniciado)));

        if ($events != null) {
            foreach($events as $event) {
                //Cojo Gumbudos que hayan caducado
                $gumbudos = Gumbudo::model()->findAll(array('condition'=>'NOW()>ripdate AND event_id=:evento', 'params'=>array(':evento'=>$event->id)));

                //Mato a los gumbudos que se les haya pasado el arroz
                Gumbudo::model()->deleteAll(array('condition'=>'NOW()>ripdate AND event_id=:evento', 'params'=>array(':evento'=>$event->id)));
                $this->logCron('    Eliminados los Gumbudos caducados en el evento '.$event->id.'.', 'info');

                //Quito los pilacron de los gumbudos muertos
                $ids = array();
                foreach ($gumbudos as $gumbudo) {
                    $ids[] = 'params='.$gumbudo->id;
                }

                if (!empty($ids)) {
                    Cronpile::model()->deleteAll(array('condition'=>'type=:tipo AND ('.implode(' OR ', $ids).')', 'params'=>array(':tipo'=>'gumbudo')));
                    $this->logCron('    Eliminadas las entradas en Cronpile de los Gumbudos caducados en el evento '.$event->id.'.', 'info');
                }
            }
        }

        return 0;
    }
	
	/* 15 minutos */
	public function actionCheckQuemados()
	{
		$this->logCron('Quemadura en los corrales.', 'info');
		
		//Cojo todos los eventos iniciados
		$events = Event::model()->findAll(array('condition'=>'type=:tipo AND status=:estado', 'params'=>array( ':tipo'=>'desayuno', ':estado'=>Yii::app()->params->statusIniciado)));

		foreach($events as $event) {		
			//Resto un contador a los Gungubos con quemadura de todos los corrales del evento y miro los que mueren
			$mueren = $this->reduceHealthGungubos($event->id, null, Yii::app()->params->conditionQuemadura);
			
			//Por cada muerto por quemadura miro a ver si quema a otros
			$probabilidadPropagar = Yii::app()->config->getParam('quemaduraProbabilidadPropagacion');			
			$minQuemados = Yii::app()->config->getParam('quemaduraMinQuemados');
			$maxQuemados = Yii::app()->config->getParam('quemaduraMaxQuemados');
			
			$nuevos_quemados = 0;
			for ($i=1; $i<=$mueren['condicion']; $i++) {
				$tirada = mt_rand(1,100);
				if ($tirada <= $probabilidadPropagar) {
					//Les quemo!!!
					$cuantos = mt_rand($minQuemados, $maxQuemados);
					$nuevos_quemados += $cuantos;
				}
			}
			
			//Pongo quemadura a los nuevos Gungubos quemados
			
			//Notifico de las muertes y nuevos quemados
		}
		
		return 0;
	}

	
	/** Pone en estado Calma todos los eventos Iniciados de tipo desayuno los Viernes
	*/
	public function actionEventosEnCalma() {
		//Cojo todos los eventos iniciados
		$events = Event::model()->findAll(array('condition'=>'type=:tipo AND status=:estado', 'params'=>array( ':tipo'=>'desayuno', ':estado'=>Yii::app()->params->statusIniciado)));

		foreach($events as $event) {
            $event->status = Yii::app()->params->statusCalma;

            if (!$event->save())
                $this->logCron('** ERROR al guardar el evento ('.$event->id.') poniéndolo en estado Calma.', 'info');
            else {
                $this->logCron('Evento '.$event->id.' puesto en calma.', 'info');

                //Creo la notificación
                $nota = new Notification;
                $nota->message = 'Valientes y valientas, habéis luchado con honor y valor. Descansad ahora a la espera de mi veredicto del día de mañana.';
                $nota->type = 'omelettus';
                if (!$nota->save())
                    $this->logCron('** ERROR al guardar la notificación de fin de batalla del evento ('.$event->id.').', 'info');
            }
        }

		return 0;
	}

    /** Pone los eventos en estado Iniciado, los lunes. Ya sea un evento nuevo (que viene de Preparativos) o un evento que la semana anterior no se completó porque no hubo desayuno (en Calma)
     */
    public function actionIniciarEventos() {
        //Cojo todos los eventos en preparativos
        $events = Event::model()->findAll(array('condition'=>'type=:tipo AND (status=:estado OR status=:estado2)', 'params'=>array( ':tipo'=>'desayuno', ':estado'=>Yii::app()->params->statusPreparativos, ':estado2'=>Yii::app()->params->statusCalma)));

        foreach($events as $event) {
            $event->status = Yii::app()->params->statusIniciado;

            if (!$event->save())
                $this->logCron('** ERROR al guardar el evento ('.$event->id.') Iniciandolo.', 'info');
            else {
                $this->logCron('Evento '.$event->id.' iniciado.', 'info');

                //Programo la cría de gungubos
                //Yii::app()->event->scheduleGungubosRepopulation($event->id);

                //Creo la notificación
                $nota = new Notification;
                $nota->message = 'Amados súbditos, un lunes os prometí y un lunes os doy, por lo tanto... ¡comienza la temporada de cría de gungubos!';
                $nota->type = 'omelettus';
                if (!$nota->save())
                    $this->logCron('** ERROR al guardar la notificación de inicio del evento ('.$event->id.').', 'info');
            }
        }
		
		//A todos los jugadores les pongo activos y actualizo su last_activity
		User::model()->updateAll(array('status'=>Yii::app()->params->statusCazador, 'last_activity'=>date('Y-m-d H:i:s')));

        return 0;
    }
	

    /** 5 minutos Procesa la pila de tareas Cron
     */
    public function actionProcessCronPile()
    {		
        $pila = Cronpile::model()->findAll();
		//$now = time();
		$dateNow = Yii::app()->event->getCurrentDate();
		$now = strtotime($dateNow);

        foreach($pila as $cronjob) {
			$result = true;
            $this->logCron('Procesando '.$cronjob->operation.' ['.$cronjob->params.'] programado para '.$cronjob->due_date.'.', 'info');

			if ($cronjob->due_date !== NULL) {
				$fecha = strtotime($cronjob->due_date);
				
				if ($now <= $fecha) {
                    $this->logCron('  Todavia no se tiene que ejecutar esta tarea.', 'info');
					continue; //Me salto la tarea porque aún no ha de lanzarse
				}
			}

            switch($cronjob->operation) {
                case 'generateRanking':
                        $result = Yii::app()->event->generateRanking();
                    break;
                case 'gumbudoAsaltanteAttack':
                        $result = Yii::app()->gumbudos->gumbudoAsaltanteAttack($cronjob->params); //En params va el id del gumbudo que ataca
                    break;
				case 'gumbudoNigromanteAttack':
						$result = Yii::app()->gumbudos->gumbudoNigromanteAttack($cronjob->params); //En params va el id del gumbudo que ataca
					break;
                case 'gumbudoArtificieroAttack':
                    $result = Yii::app()->gumbudos->gumbudoArtificieroAttack($cronjob->params); //En params va el id del gumbudo que ataca
                    break;
            }
			
			if ($result !== true)
                $this->logCron('**  Error en la tarea cron: '.$result.'.', 'info');

            $this->logCron('  Elimino la tarea '.$cronjob->operation.' ['.$cronjob->params.'] de la pila.', 'info');
            //$cronjob->delete();
        }

        return 0;
    }


    /********************************************************************************************************/
    /********************************************************************************************************/


    private function reduceHealthGungubos($event_id, $owner_id=null, $solo_condicion=null)
    {
		$mueren = array();
		
        $owner = '';
        if ($owner_id!==null) {
            $owner = ' AND owner_id='.$owner_id.' ';
        }
		
		$condition = '';
		if ($solo_condicion!==null) {
			$condition = ' AND condition_status="'.$solo_condicion.'" ';
		}

        //Quito un contador de todos los gungubos de los corrales.
        Gungubo::model()->updateCounters(array('health'=>-1),'event_id=:evento AND location=:lugar'.$owner.$condition, array(':evento'=>$event_id, ':lugar'=>'corral'));

        //Los que hayan muerto por causas naturales
		if ($solo_condicion!==null) {
			$mueren['natural'] = Gungubo::model()->deleteAll(array('condition'=>'event_id=:evento AND health<=0 AND location=:lugar AND condition_status=:condicion'.$owner, 'params'=>array(':evento'=>$event_id, ':lugar'=>'corral', ':condicion'=>'normal')));
		}

        //Actualizo los que mueran por causas no naturales pal cementerio
        $mueren['condicion'] = Gungubo::model()->updateAll(array('location'=>'cementerio'),'event_id=:evento AND health<=0 AND location=:lugar AND condition_status!=:condicion'.$owner, array(':evento'=>$event_id, ':lugar'=>'corral', ':condicion'=>'normal'));
		
		return $mueren;
    }


    /** Loguea un mensaje
     * @param $message Mensaje en texto
     * @param string $type Tipo de mensaje: info, error, trace
     */
    private function logCron($message, $type='info')
    {
        echo "[".strtoupper($type)."] ".$message."\n";
        Yii::log($message, $type);
    }

}
