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
// * */1 * * * /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron criarGungubos

// #Cada hora entre las 7-18 miro a ver si repueblo gungubos
// 5 7-18 * * * /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron repopulateGungubos

// #Cada hora compruebo si hay algo en cola del cronPile
// * */1 * * * /usr/local/bin/php /home/kafhe/kafhe/protected/yiic cron processCronPile

// #Todos los días a las 4 de la mañana hago backup de base de datos
// 0 4 * * * sh /home/kafhe/mysql_backup.sh


class CronCommand extends CConsoleCommand {
    public $global_param = true;

    /** Regenera el tueste del usuario $userId (ID User).
     * @param null $userId Usuario a regenerar tueste. Si $userId es nulo regenera el tueste de todos los usuarios de grupos activos.
     */
    public function actionRegenerarTueste($userId=null)
    {
        echo "Compruebo caducidad de modificadores.\n";
        Yii::app()->modifier->checkModifiersExpiration();

        echo "Iniciando regeneracion.\n";
        if ($userId === null) {
            echo "Regenero a todos los usuarios.\n";
            $grupos = Group::model()->findAll(array('condition'=>'active=1'));

            foreach($grupos as $grupo) {
                //Evento del grupo en estado Iniciado
                $event = Event::model()->find(array('condition'=>'group_id=:groupId AND type=:tipo AND status=:estado', 'params'=>array(':groupId'=>$grupo->id, ':tipo'=>'desayuno', ':estado'=>Yii::app()->params->statusIniciado)));

                echo "  Usuarios del grupo ".$grupo->name." (".$grupo->id.").\n";
                $usuarios = User::model()->findAll(array('condition'=>'group_id=:groupId', 'params'=>array(':groupId'=>$grupo->id)));
                $kafheitas = $achikhoritas = 0;

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
                            echo "** ERROR al guardar el usuario (".$usuario->id.") regenerando su tueste.\n";

                        echo "    Usuario ".$usuario->username." (rango ".$usuario->rank.") - Tueste regenerado: " . $regenerado."\n";

                        //Guardo el tueste desbordado si hay, en el evento, si es un Criador o Baja
                        if ($event!=null && $desbordeTueste>0 && ($usuario->status==Yii::app()->params->statusCriador || $usuario->status==Yii::app()->params->statusBaja) ) {
                            if ($usuario->side == 'kafhe')
                                $event->stored_tueste_kafhe += $desbordeTueste;
                            elseif ($usuario->side == 'achikhoria')
                                $event->stored_tueste_achikhoria += $desbordeTueste;
                        }
                    } else
                        echo "    Usuario ".$usuario->username." - Todavia no puede regenerar tueste.\n";

                    //Cuento kafheitas y achis para ver si hay desequilibrio
                    if ($usuario->side == 'kafhe') $kafheitas++;
                    elseif ($usuario->side == 'achikhoria') $achikhoritas++;
                }

                //Si están desequilibrados los grupos, genero tueste extra para el que menos tenga
                if ($kafheitas != $achikhoritas) {
                    echo "Bandos desequilibrados, genero tueste extra para el mas debil.\n";
                    $dummy = User::model()->findByPk(1);
                    $extra = Yii::app()->tueste->getTuesteRegenerado($dummy);

                    if ($extra!==false) {
                        $dummy->last_regen_timestamp = date('Y-m-d H:i:s');
                        if (!$dummy->save())
                            echo "** ERROR al guardar el usuario (".$dummy->id.") simulando regenerar su tueste.\n";

                        //Guardo en el almacén
                        if ($kafheitas>$achikhoritas) {
                            $event->stored_tueste_achikhoria += $extra;
                            echo "Extra de tueste para Achikhoria de ".$extra."\n";
                        } else {
                            $event->stored_tueste_kafhe += $extra;
                            echo "Extra de tueste para Kafhe de ".$extra."\n";
                        }
                    }
                }

                //Guardo el evento
                if (!$event->save())
                    echo "** ERROR al guardar el evento (".$event->id.") guardando el tueste desbordado.\n";
            } //foreach group

        } else {
            //Regenero a un usuario concreto
            $usuario = User::model()->findByPk($userId);
            $regenerado = Yii::app()->tueste->getTuesteRegenerado($usuario);

            if ($regenerado !== false) {
                $usuario->ptos_tueste = min( intval(Yii::app()->config->getParam('maxTuesteUsuario')), ($usuario->ptos_tueste+$regenerado) );
                $usuario->last_regen_timestamp = date('Y-m-d H:i:s');
                if (!$usuario->save())
                    echo "** ERROR al guardar el usuario (".$usuario->id.") regenerando su tueste.\n";

                echo "    Usuario ".$usuario->username." - Tueste regenerado: " . $regenerado."\n";
            } else
                echo "    Usuario ".$usuario->username." - Todavia no puede regenerar tueste.\n";
        }

        return 0;
    }


    /** Los criadores cazan gungubos cada hora para cada bando
     * @param null $eventId Evento para el que criar gungubos. Si es null miro todos los eventos en estado iniciado (1).
     */
    public function actionCriarGungubos($eventId=null)
    {
        echo "Compruebo caducidad de modificadores.\n";
        Yii::app()->modifier->checkModifiersExpiration();

        if ($eventId === null) {
            //Para todos los eventos de estado "iniciado" (1)
            $events = Event::model()->findAll(array('condition'=>'status=:status', 'params'=>array(':status'=>Yii::app()->params->statusIniciado)));
            if ($events != null) {
                foreach($events as $event) {
                    $criados = Yii::app()->gungubos->getGungubosCazados($event);
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
                        echo "Todavia no puedo criar gungubos en el evento ".$event->id.".\n";
                }
            }
        } else {
           /* //Para el evento concreto. Compruebo que está en estado (1) antes
            $event = Event::model()->findByPk($eventId);
            if ($event->status != Yii::app()->params->statusIniciado) return 0;

            $criados = Yii::app()->gungubos->getGungubosCriados($event);
            if ($criados !== false) {
                //Si se van a criar más de los que hay en la población se reparten en la proporcion correspondiente
                if($event->gungubos_population == 0){
                    $criados['kafhe'] = 0;
                    $criados['achikhoria'] = 0;
                }else if(($criados['kafhe']+$criados['achikhoria']) > $event->gungubos_population){
                    $proporcionKafhe = $criados['kafhe']/($criados['kafhe']+$criados['achikhoria']);
                    $criados['kafhe'] = $event->gungubos_population*$proporcionKafhe;
                    $criados['achikhoria'] = $event->gungubos_population - $criados['kafhe'];

                }

                //Guardo el evento
                $event->gungubos_kafhe += $criados['kafhe'];
                $event->gungubos_achikhoria += $criados['achikhoria'];
                $event->stored_tueste_kafhe = 0; //lo pongo a 0 porque se ha utilizado para generar gungubos
                $event->stored_tueste_achikhoria = 0;
                $event->last_gungubos_criadores = date('Y-m-d H:i:s');
                $event->gungubos_population -= $criados['kafhe']+$criados['achikhoria'];

                if (!$event->save())
                    echo "** ERROR al guardar el evento (".$event->id.") criando gungubos.\n";

                echo "Criados ".$criados['kafhe']." gungubos para Kafhe. Evento ".$event->id.".\n";
                echo "Criados ".$criados['achikhoria']." gungubos para Achikhoria. Evento ".$event->id.".\n";
            } else
                echo "Todavía no puede criar gungubos.\n";*/
        }

        return 0;
    }

    /** Repuebla los gungubos cada día
     */
    public function actionRepopulateGungubos()
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
    }

    /** Procesa la pila de tareas Cron
     */
    public function actionProcessCronPile()
    {
        $pila = Cronpile::model()->findAll();

        echo "Hay ".count($pila)." tareas en la pila de Cron.\n";

        foreach($pila as $cronjob) {
            echo "Procesando ".$cronjob->operation." [".$cronjob->params."].\n";

            switch($cronjob->operation) {
                case 'generateRanking':
                        Yii::app()->event->generateRanking();
                    break;
            }

            echo "Elimino la tarea ".$cronjob->operation." [".$cronjob->params."] de la pila.\n";
            $cronjob->delete();
        }

        return 0;
    }

}
