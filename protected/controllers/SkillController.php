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
  
	public function actionExecute($skill_id, $target_id=null, $side=null, $extra_param=null) //Automáticamente asocia $skill_id = $_GET['skill_id'] y si no existe lanza excepción 404 controlada
	{	
		//Obtengo la skill y mi usuario
		$skill = Skill::model()->findByPk($skill_id);
		$caster = Yii::app()->currentUser->model;
		if ($target_id!==null) {
			/*if (is_numeric($target_id)) //$target_id!='kafhe' && $target_id!='achikhoria' && $target_id!='libre')
				$target = User::model()->findByPk($target_id);
			else
				$target = $target_id;*/
			$target = User::model()->findByPk($target_id);
		} else $target = null;
		
		//Creo una instancia del validador de habilidades
		$validator = new SkillValidator;
$extra_param='garras';
		if ($validator->canExecute($skill, $target, $side, $extra_param, true) == 1) {
			//Ejecuto la habilidad
			if (!Yii::app()->skill->executeSkill($skill, $target, $side, $extra_param)) {
                Yii::app()->user->setFlash('error', "Se profujo un fallo al ejecutar la habilidad. ".Yii::app()->skill->error);
			} else {
                //Doy experiencia por ejecutar la habilidad si no es pifia
                if (Yii::app()->skill->result != 'fail') {
                    $exp_ganada = round(Yii::app()->config->getParam('expPorcentajeHabilidadPorTueste')*$skill->cost_tueste/100) + round(Yii::app()->config->getParam('expPorcentajeHabilidadPorRetueste')*$skill->cost_retueste/100);
                    $caster->experience += $exp_ganada;
                    Yii::app()->usertools->checkLvlUpUser($caster, false);

                    //Salvo
                    if (!$caster->save())
                        throw new CHttpException(400, 'Error al guardar el usuario '.$user->id.' tras obtener experiencia por habilidad ('.$skill->id.').');
                }

			    //Creo la notificación si no es la skill Disimular o tengo ésta activa
                if (!$this->skillNotification(Yii::app()->skill))
                    throw new CHttpException(400, 'Error al guardar una notificación por habilidad ('.$skill_id.').');

                //Creo una entrada en el historial con la ejecución
                $hist = new HistorySkillExecution();
                $hist->skill_id = $skill->id;
                $hist->caster_id = Yii::app()->skill->caster;
                $hist->target_final = Yii::app()->skill->finalTarget;
                $hist->result = Yii::app()->skill->result;
                $hist->event_id = Yii::app()->event->id;

                if (!$hist->save())
                    throw new CHttpException(400, 'Error al guardar el historial de la ejecución de la habilidad ('.$skill->keyword.'). ['.print_r($hist->getErrors(),true).']');
            }
		}
		else {			
			Yii::app()->user->setFlash('error', "No se ha podido ejecutar la habilidad. ".$validator->getLastError());
		}


        $this->redirect(Yii::app()->getRequest()->getUrlReferrer());
	}

	public function actionCooperate($skill_id)
	{
	}
	
	
	private function skillNotification($skill) 
	{
	    //Si la skill no ha de crear notificación
	    if ($skill->generatesNotification==false)
	        return true;

		//Si la habilidad ejecutándose no pifió y es Disimular o Impersonar o Trampa, no la muestro
		if ($skill->result!='fail' && ($skill->keyword==Yii::app()->params->skillDisimular || $skill->keyword==Yii::app()->params->skillImpersonar || $skill->keyword==Yii::app()->params->skillTrampa))
		    return true;
		
		//Si el usuario tiene el modificador "disimulando" activo, resto usos y no muestro la notificación
		$modifier = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierDisimulando);
		if ($modifier !== false) {
			if (!Yii::app()->modifier->reduceModifierUses($modifier))
				throw new CHttpException(400, 'Error al reducir los usos de un modificador ('.$modifier->keyword.').');
			return true;
		}

        //Si no, pues creo la notificación
		$nota = new Notification;
        $nota->recipient_original = $skill->originalTarget;
        $nota->recipient_final = $skill->finalTarget;
        $nota->message = $skill->resultMessage; //Mensaje para el muro
        $nota->type = Yii::app()->currentUser->side;

        //Si el usuario está impersonando, cambio el objetivo original
        $modifier = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierImpersonando);
        if ($modifier !== false) {
            if (!Yii::app()->modifier->reduceModifierUses($modifier))
                throw new CHttpException(400, 'Error al reducir los usos de un modificador ('.$modifier->keyword.').');

            $alt_sender = Yii::app()->usertools->randomUser(null, null, array($skill->caster, $skill->finalTarget));
            if ($alt_sender===null) $nota->sender = $skill->caster;
            $nota->sender = $alt_sender->id;
        } else
            $nota->sender = $skill->caster;

		return $nota->save();
	}
}