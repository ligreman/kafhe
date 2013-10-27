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

// #Cada hora genero gungubos por criadores
// * */1 * * * /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron criadores

// #Cada hora entre las 7-18 miro a ver si repueblo gungubos
// #5 7-18 * * * /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron repopulateGungubos

// #Cada hora compruebo si hay algo en cola del cronPile
// * */1 * * * /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron processCronPile

// #Los viernes por la noche (el servidor tiene otra hora) pone los eventos en Calma
// 0 23 * * 4 /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron eventosEnCalma

// #Los lunes a las 9 de la mañana pongo los eventos en Preparativos
// 0 8 * * 1 /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron iniciarEventos

// #Todos los días a las 4 de la mañana hago backup de base de datos
// 0 3 * * * sh /home/kafhe/mysql_backup.sh



class CronCommand extends CConsoleCommand {
    public $global_param = true;

    /** Regenera el tueste del usuario $userId (ID User).
     */
    public function actionRegenerarTueste()
    {
        $this->logCron('Compruebo caducidad de modificadores.', 'info');
        Yii::app()->modifier->checkModifiersExpiration();

        $this->logCron('Iniciando regeneracion.', 'info');

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
            $kafheitas = $achikhoritas = array('numero'=>0, 'tueste'=>0);

            foreach ($usuarios as $usuario) {
                $regenerado = Yii::app()->tueste->getTuesteRegenerado($usuario);

                if ($regenerado !== false) {
                    $desbordeTueste = 0;

                    //Si ya estoy al máximo de tueste cambio mi estado a Criador, si soy Cazador, y miro el desborde
                    $usuario->ptos_tueste += $regenerado;
                    if ($usuario->ptos_tueste > intval(Yii::app()->config->getParam('maxTuesteUsuario')) ) {
                        $desbordeTueste = $usuario->ptos_tueste - intval(Yii::app()->config->getParam('maxTuesteUsuario'));
                        $usuario->ptos_tueste = intval(Yii::app()->config->getParam('maxTuesteUsuario'));

                        if ($usuario->status==Yii::app()->params->statusCazador)
                            $usuario->status = Yii::app()->params->statusCriador;
                    }

                    $usuario->last_regen_timestamp = date('Y-m-d H:i:s');
                    if (!$usuario->save())
                        $this->logCron('** ERROR al guardar el usuario ('.$usuario->id.') regenerando su tueste.', 'info');

                    $this->logCron('    Usuario '.$usuario->username.' (rango '.$usuario->rank.') - Tueste regenerado: '.$regenerado.'.', 'info');

                    //Guardo el tueste desbordado si hay, en el evento, si es un Criador o Baja
                    if ($event!=null && $desbordeTueste>0 && ($usuario->status==Yii::app()->params->statusCriador || $usuario->status==Yii::app()->params->statusBaja) ) {
                        if ($usuario->side == 'kafhe')
                            $event->stored_tueste_kafhe += $desbordeTueste;
                        elseif ($usuario->side == 'achikhoria')
                            $event->stored_tueste_achikhoria += $desbordeTueste;
                    }
                } else
                    $this->logCron('Usuario '.$usuario->username.' - Todavia no puede regenerar tueste.', 'info');

                //Cuento kafheitas y achis para ver si hay desequilibrio
                if ($usuario->side == 'kafhe') {
                    $kafheitas['numero']++;
                    $kafheitas['tueste'] += intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) + Yii::app()->tueste->getTuesteRegeneradoPorRango($usuario);
                }
                elseif ($usuario->side == 'achikhoria') {
                    $achikhoritas['numero']++;
                    $achikhoritas['tueste'] += intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) + Yii::app()->tueste->getTuesteRegeneradoPorRango($usuario);
                }
            }

            //Si están desequilibrados los grupos, genero tueste extra para el que menos tenga
            if ($kafheitas['numero'] != $achikhoritas['numero']) {
                $this->logCron('Bandos desequilibrados, genero tueste extra para el mas debil.', 'info');

                $dummy = User::model()->findByPk(1); //Uso el usuario admin como contador de tiempo para dar tueste de compensación
                $extra = abs($kafheitas['tueste'] - $achikhoritas['tueste']); //La diferencia de tueste base de ambos bandos
                $this->logCron('El extra de tueste es '.$extra.' (kafhe->'.$kafheitas['tueste'].' // achi->'.$achikhoritas['tueste'].').', 'info');

                if ($extra!==false) {
                    $dummy->last_regen_timestamp = date('Y-m-d H:i:s');
                    if (!$dummy->save())
                        $this->logCron('** ERROR al guardar el usuario ('.$dummy->id.') simulando regenerar su tueste.', 'info');

                    //Guardo en el almacén
                    if ($kafheitas['numero'] > $achikhoritas['numero']) {
                        $event->stored_tueste_achikhoria += $extra;
                        $this->logCron('Extra de tueste para Achikhoria de '.$extra.'.', 'info');
                    } else {
                        $event->stored_tueste_kafhe += $extra;
                        $this->logCron('Extra de tueste para Kafhe de '.$extra.'.', 'info');
                    }
                }
            }

            //Guardo el evento
            if (!$event->save())
                $this->logCron('** ERROR al guardar el evento ('.$event->id.') guardando el tueste desbordado.', 'info');
        } //foreach group

        return 0;
    }


    /** Los criadores cazan gungubos cada hora para cada bando
     */
    public function actionCriadores()
    {
        $this->logCron('Compruebo caducidad de modificadores.', 'info');
        Yii::app()->modifier->checkModifiersExpiration();


        //Para todos los eventos de estado "iniciado" (1)
        $events = Event::model()->findAll(array('condition'=>'status=:status', 'params'=>array(':status'=>Yii::app()->params->statusIniciado)));
        if ($events != null) {
            foreach($events as $event) {
                /*$criados = Yii::app()->gungubos->getGungubosCazados($event);
                $tuesteRestante = array('kafhe'=>0, 'achikhoria'=>0);

                if ($criados !== false) {

                    //Si se van a criar más de los que hay en la población se reparten en la proporcion correspondiente
                    if($event->gungubos_population == 0){
                        $criados['kafhe'] = 0;
                        $criados['achikhoria'] = 0;

                        //Como no había gungubos, lo que hago es repartir el tueste entre los demás jugadores
                        $tuesteRestante = Yii::app()->tueste->repartirTueste($event);
                        echo "No habia gungubos libres asi que reparto el tueste. Sobra: ".$tuesteRestante['kafhe']." kafhe; ".$tuesteRestante['achikhoria']." achikhoria.\n";
                    } else if(($criados['kafhe']+$criados['achikhoria']) > $event->gungubos_population) {
                        $proporcionKafhe = $criados['kafhe']/($criados['kafhe']+$criados['achikhoria']);
                        $criados['kafhe'] = intval($event->gungubos_population*$proporcionKafhe);
                        $criados['achikhoria'] = $event->gungubos_population - $criados['kafhe'];

                        echo "Criados ".$criados['kafhe']." gungubos para Kafhe. Evento ".$event->id.".\n";
                        echo "Criados ".$criados['achikhoria']." gungubos para Achikhoria. Evento ".$event->id.".\n";
                    }

                    //Guardo el evento
                    $event->gungubos_kafhe += $criados['kafhe'];
                    $event->gungubos_achikhoria += $criados['achikhoria'];
                    $event->stored_tueste_kafhe = $tuesteRestante['kafhe'];
                    $event->stored_tueste_achikhoria = $tuesteRestante['achikhoria'];
                    $event->last_gungubos_criadores = date('Y-m-d H:i:s');
                    $event->gungubos_population -= $criados['kafhe']+$criados['achikhoria'];

                    if (!$event->save())
                        echo "** ERROR al guardar el evento (".$event->id.") criando gungubos.\n";
                } else
                    echo "Todavia no puedo criar gungubos en el evento ".$event->id.".\n";*/

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
    }

    /** Repuebla los gungubos cada día
     */
    /*public function actionRepopulateGungubos()
    {
        Yii::log('Vamos a repoblar', 'info');
        echo "Iniciando repoblación\n";

        //Para todos los eventos de estado "iniciado" (1)
        $events = Event::model()->findAll(array('condition'=>'status=:status', 'params'=>array(':status'=>Yii::app()->params->statusIniciado)));
        if ($events != null) {
            foreach($events as $event) {
                //Miro a ver si ya he repoblado hoy en este evento
                if ($event->last_gungubos_repopulation!=""  &&  date('Y-m-d') == $event->last_gungubos_repopulation) {
                    echo "Todavía no se puede repoblar en el evento ".$event->id.".\n";
                    Yii::log('Aún no repueblo', 'info');
                    continue;
                }

                //A ver si el random dice que toca en esta hora. Repoblaré de 7am a 18pm.
                $hora = intval(date('H'));
                if ($hora>=7 && $hora<=18) { //Si son las 18 he de repoblar sí o sí, por lo que no comprobaré nada
                    $rand = mt_rand($hora,18);
                    if ($hora != $rand) { //Si es distino nada, no toca
                        echo "Ahora no toca repoblar en el evento ".$event->id.".\n";
                        continue;
                    }
                } elseif ($hora>18 && $hora<7) { //Las 18 la dejo fuera para repoblar sí o sí
                    echo "Los gungubos están dormidos, estas no son horas de repoblar en el evento ".$event->id.".\n";
                    continue;
                }

                //Repueblo gungubos en el evento
                $cuantos = mt_rand(7,13)*100;
                $event->gungubos_population += $cuantos; //Repueblo

                //Pongo la fecha de hoy
                $event->last_gungubos_repopulation = date('Y-m-d');

                if (!$event->save())
                    echo "** ERROR al guardar el evento (".$event->id.") repoblando gungubos.\n";

                echo "Repoblados ".$cuantos." gungubos en el evento ".$event->id.".\n";
                Yii::log('Repoblados', 'info');
            }
        }

        return 0;
    }*/
	
	/** Pone en estado Calma todos los eventos Iniciados de tipo desayuno
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

    /** Pone los eventos en estado Iniciado
     */
    public function actionIniciarEventos() {
        //Cojo todos los eventos en preparativos
        $events = Event::model()->findAll(array('condition'=>'type=:tipo AND status=:estado', 'params'=>array( ':tipo'=>'desayuno', ':estado'=>Yii::app()->params->statusPreparativos)));

        foreach($events as $event) {
            $event->status = Yii::app()->params->statusIniciado;

            if (!$event->save())
                $this->logCron('** ERROR al guardar el evento ('.$event->id.') Iniciándolo.', 'info');
            else {
                $this->logCron('Evento '.$event->id.' iniciado.', 'info');

                //Creo la notificación
                $nota = new Notification;
                $nota->message = 'Amados súbditos, un lunes os prometí y un lunes os doy, por lo tanto... ¡que se abra la veda de gungubos!';
                $nota->type = 'omelettus';
                if (!$nota->save())
                    $this->logCron('** ERROR al guardar la notificación de inicio del evento ('.$event->id.').', 'info');
            }
        }

        return 0;
    }
	

    /** Procesa la pila de tareas Cron
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
                $this->logCron('  La tarea cron tiene fecha programada.', 'info');
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
				case 'repopulateGungubos':
						$result = Yii::app()->event->repopulateGungubos($cronjob->params); //En params está el id del evento a repoblar
					break;
            }
			
			if ($result !== true)
                $this->logCron('**  Error en la tarea cron: '.$result.'.', 'info');

            $this->logCron('  Elimino la tarea '.$cronjob->operation.' ['.$cronjob->params.'] de la pila.', 'info');
            $cronjob->delete();
        }

        return 0;
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
