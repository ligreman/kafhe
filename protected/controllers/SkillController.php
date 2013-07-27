<?php

class SkillController extends Controller
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
			  'actions'=>array('execute', 'cooperate'),
			  'roles'=>array('Usuario'), 
			),
			array('deny',  // deny all users
			  'users'=>array('*'),
			),
		);
	}
  
	public function actionExecute($skill_id, $target_id=null) //Automáticamente asocia $skill_id = $_GET['skill_id'] y si no existe lanza excepción 404 controlada
	{	
		//Obtengo la skill y mi usuario
		$skill = Skill::model()->findByPk($skill_id);
		$user = User::model()->findByPk(Yii::app()->user->id);
		if ($target_id!==null) $target = User::model()->findByPk($target_id);
		else $target = null;
		
		//Creo una instancia del validador de habilidades
		$validator = new SkillValidator;
		
		if ($validator->canExecute($skill, $user, $target, true)) {
			//Ejecuto la habilidad
			Yii::app()->skill->executeSkill($skill, $user, $target);
			$msg = "Mensaje: ".Yii::app()->skill->resultMessage;
			
			Yii::app()->user->setFlash('success', "Habilidad ejecutada con éxito. ".$msg);
		}
		else {			
			Yii::app()->user->setFlash('error', "No se ha podido ejecutar la habilidad. ".$validator->getLastError());
		}
		
		
		$this->redirect(array('character/skills'));		
	}

	public function actionCooperate($skill_id)
	{
	}
}