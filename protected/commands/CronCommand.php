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

*/

class CronCommand extends CConsoleCommand {
	public $global_param = true;
	
	//Ejecuta esto si no se pasa ninguna acción
	/*public function actionIndex ()
	{
		echo "Index";
		return 0;
	}
	
	//Acción por defecto: index
    public function actionParams ($param1, $param2='default', array $param3)
	{
        // here we are doing what we need to do
		echo "ok";
		return 0;
    }*/


	//NOTA no necesitaré comprobar modificadores ya que lo compruebo en cada carga de página y al regenerar tueste en el Cron
	
	
	/*
	*	Regenera el tueste del usuario $userId (ID User). Si $userId es nulo regenera el tueste de todos los usuarios de grupos activos.
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
				echo "  Usuarios del grupo ".$grupo->name." (".$grupo->id.").\n";
				$usuarios = User::model()->findAll(array('condition'=>'group_id=:groupId', 'params'=>array(':groupId'=>$grupo->id)));
				
				foreach ($usuarios as $usuario) {					
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
		
		/*$time = time();
		echo date('d-m-Y H:m:s')."\n";
		echo $time."\n";
		echo date('d-m-Y H:m:s', $time)."\n";
		echo print_r(getdate($time),true)."\n";
		echo print_r(getdate(strtotime(date('d-m-Y H:i:s'))),true);*/
		return 0;
	}
	
	/*
	*	Cría gungubos cada hora para el bando
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
						//Guardo el evento
						$event->gungubos_kafhe += $criados['kafhe'];
						$event->gungubos_achikhoria += $criados['achikhoria'];
						$event->last_gungubos_timestamp = date('Y-m-d H:i:s');
						
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


	public function actionGenerateRanking() {
        //Cojo los grupos
        $groups = Group::model()->findAll();

        foreach ($groups as $group) {
            //Primero saco el ranking actual de ese grupo
            $ranking = Ranking::model()->findAll(array('condition'=>'group_id=:grupo', 'params'=>array(':grupo'=>$group->id), 'order'=>'rank DESC'));

            //Saco los usuarios del grupo ordenados por rango
            $users = User::model()->findAll(array('order'=>'rank DESC'));

            if ($ranking == null)
                $ranking = array_slice($users, 0, 10); //Si no existía ranking cojo los 10 primeros usuarios y ese es el ranking
            else {

                foreach ($users as $user) {
                    $newR = new Ranking;
                    $newR->user->id = $user->id;
                    $newR->rank = $user->rank;
                    $newR->date = date('Y-m-d');

                    //Miro cada posición del ranking a ver dónde encaja
                    for ($i=0; $i<count($ranking); $i++) {
                        if ($user->rank >= $ranking[$i]->rank) {
                            //Coloco al usuario encima de esta posición del ranking
                            $ranking = array_splice($ranking, $i, 0, $newR);

                            break; //Termino de mirar posiciones del ranking para este usuario
                        }
                    }

                    //Si llego aquí es que no se ha metido el usuario aún, por lo que le pongo el último
                    array_push($ranking, $newR);
                }

            }

            //Por último, guardo el ranking de este grupo (los 10 primeros sólo)
            $connection = Yii::app()->db;

            $values = array();
            for ($i=0; $i<10; $i++) {
				if (!isset($ranking[$i])) break; //Paro si no hay más
				
                $values[] = "(".$ranking[$i]->id.", ".$ranking[$i]->rank.", '".$ranking[$i]->date."')";
            }

            $sql="INSERT INTO ranking (user_id, rank, date) VALUES ".implode(',',$values);
            $command=$connection->createCommand($sql);
            $command->execute();
        }

	    return 0;
	}
}
