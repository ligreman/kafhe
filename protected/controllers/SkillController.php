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

			//Feedback para el usuario
			switch(Yii::app()->skill->result) {
                case 'fail': $feedback = 'Has pifiado al ejecutar '.$skill->name.'.'; break;
                case 'normal': $feedback = 'Has ejecutado '.$skill->name.' correctamente.'; break;
                case 'critic': $feedback = '¡Has hecho un crítico al ejecutar '.$skill->name.'!'; break;
			}
			Yii::app()->user->setFlash(Yii::app()->skill->result, $feedback);

			//Creo la notificación si no es la skill Disimular o tengo ésta activa
            if (!$this->skillNotification(Yii::app()->skill, $user))
                throw new CHttpException(400, 'Error al guardar una notificación por habilidad ('.$skill_id.').');
		}
		else {			
			Yii::app()->user->setFlash('error', "No se ha podido ejecutar la habilidad. ".$validator->getLastError());
		}


        $this->redirect(Yii::app()->getRequest()->getUrlReferrer());
	}

	public function actionCooperate($skill_id)
	{
	}
	
	
	private function skillNotification($skill, $user) 
	{
		//Si es la habilidad Disimular, no la muestro
		if ($skill->keyword == 'disimular') return true;
		
		//Si el usuario tiene el modificador "disimulando" activo, resto usos y no muestro la notificación
		if (Yii::app()->usertools->inModifiers('disimulando')) {			
			if (!Yii::app()->usertools->reduceModifierUses('disimulando'))
				throw new CHttpException(400, 'Error al reducir los usos de un modificador ('.$modifier.').');
			return true;
		}				
		
		//Si no, pues creo la notificación
		$nota = new Notification;
		$nota->sender = $skill->caster;
		$nota->recipient_original = $skill->originalTarget;
		$nota->recipient_final = $skill->finalTarget;
		$nota->message = $skill->resultMessage; //Mensaje para el muro
		$nota->type = $user->side;

		return $nota->save();
	}
}