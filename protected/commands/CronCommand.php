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

                        echo "    Usuario ".$usuario->username." - Tueste regenerado: " . $regenerado."\n";

                        //Guardo el tueste desbordado si hay, en el evento si está en estado Iniciado
                        if ($event!=null && $desbordeTueste>0) {
                            if ($usuario->side == 'kafhe')
                                $event->stored_tueste_kafhe += $desbordeTueste;
                            elseif ($usuario->side == 'achikhoria')
                                $event->stored_tueste_achikhoria += $desbordeTueste;

                            if (!$event->save())
                                echo "** ERROR al guardar el evento (".$event->id.") guardando el tueste desbordado.\n";
                        }
                    } else
                        echo "    Usuario ".$usuario->username." - Todavia no puede regenerar tueste.\n";
                }
            }

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


    /** Cría gungubos cada hora para cada bando
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
                    $criados = Yii::app()->gungubos->getGungubosCriados($event);

                    if ($criados !== false) {

                        //Si se van a criar más de los que hay en la población se reparten en la proporcion correspondiente
                        if($event->gungubos_population = 0){
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
                        $event->last_gungubos_timestamp = date('Y-m-d H:i:s');
                        $event->gungubos_population -= $criados['kafhe']+$criados['achikhoria'];

                        if (!$event->save())
                            echo "** ERROR al guardar el evento (".$event->id.") criando gungubos.\n";

                        echo "Criados ".$criados['kafhe']." gungubos para Kafhe. Evento ".$event->id.".\n";
                        echo "Criados ".$criados['achikhoria']." gungubos para Achikhoria. Evento ".$event->id.".\n";
                    } else
                        echo "Todavia no puede criar gungubos.\n";
                }
            }
        } else {
            //Para el evento concreto. Compruebo que está en estado (1) antes
            $event = Event::model()->findByPk($eventId);
            if ($event->status != Yii::app()->params->statusIniciado) return 0;

            $criados = Yii::app()->gungubos->getGungubosCriados($event);
            if ($criados !== false) {
                //Guardo el evento
                $event->gungubos_kafhe += $criados['kafhe'];
                $event->gungubos_achikhoria += $criados['achikhoria'];
                $event->last_gungubos_timestamp = date('Y-m-d H:i:s');

                if (!$event->save())
                    echo "** ERROR al guardar el evento (".$event->id.") criando gungubos.\n";

                echo "Criados ".$criados['kafhe']." gungubos para Kafhe.\n";
                echo "Criados ".$criados['achikhoria']." gungubos para Achikhoria.\n";
            } else
                echo "Todavía no puede criar gungubos.\n";
        }

        return 0;
    }

    /** Procesa la pila de tareas Cron
     */
    public function actionProcessCronPile() {
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
