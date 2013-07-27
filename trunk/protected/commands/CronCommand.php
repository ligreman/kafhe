<?php

/*
	In your crontab, execute yiic dentro de protected: yiic <command-name> <action-name> --option1=value1 --option2=value2 ...
	
	Ejemplos:
	yiic test index --param1=News --param2=5 --param3=valorArray1 --param3=valorArray2 --param3=valorArray3

	// $param2 takes default value
	yiic test index --param1=News --param3=valorArray1
	
	//parametros globales
	tiic test index --global_param=8

*/

class CronCommand extends CConsoleCommand {
	public $global_param = true;
	
	//Ejecuta esto si no se pasa ninguna acción
	/*public function actionIndex ()
	{
		echo "Index";
		return 0;
	}*/
	
	//Acción por defecto: index
    public function actionParams ($param1, $param2='default', array $param3)
	{
        // here we are doing what we need to do
		echo "ok";
		return 0;
    }
	
	
	
	/*
	*	Regenera el tueste del usuario $user (Object User). Si $user es nulo regenera el tueste de todos los usuarios de grupos activos.
	*/
	public function actionRegenerarTueste ($user=null)	
	{
		echo "Iniciando regeneracion.\n";
		if ($user === null) {
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
						$usuario->save();
					}
					
					echo "    Usuario ".$usuario->username." - Tueste regenerado: " . $regenerado."\n";
				}
			}
			
		}
		
		/*$time = time();
		echo date('d-m-Y H:m:s')."\n";
		echo $time."\n";
		echo date('d-m-Y H:m:s', $time)."\n";
		echo print_r(getdate($time),true)."\n";
		echo print_r(getdate(strtotime(date('d-m-Y H:i:s'))),true);*/
		return 0;
	}
}
