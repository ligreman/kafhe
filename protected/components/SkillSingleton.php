<?php

/**
 * SkillSingleton para operaciones relacionadas con las habilidades
 * Podré acceder a resultMessage así: Yii::app()->skill->resultMessage; ya que es un CApplicationComponent.
 * Si no, tendría que acceder con Yii::app()->skill->getResultMessage();
 */
class SkillSingleton extends CApplicationComponent
{	
	private $_caster; //ID
	private $_originalTarget; //ID
	private $_finalTarget;  //ID
	private $_keyword = '';
	private $_result = '';
	private $_resultMessage = '';
	private $_error = '';

    public function executeSkill($skill, $user, $target, $side)
    {
        $this->_error = '';
		$this->_keyword = $skill->keyword;

        //Saco los nombres de los que intervienen
		$this->_caster = $user->id;
		if ($target === null) $this->_originalTarget = $user->id;
		else $this->_originalTarget = $target->id;

		//compruebo caducidad de modificadores		
		Yii::app()->usertools->checkModifiersExpiration();

        //Calculo cuál es el objetivo final, por si hay escudos y demás cosas por ahí
        $finalTarget = $this->calculateFinalTarget($skill, $user, $target, $side);

        //Compruebo si es crítico o pifia
		//son porcentajes. PIFIA: de 1 a (1+(fail-1)) || CRÍTICO: de (100-(critic-1)) a 100
		$critic = 100 - ($this->criticValue($skill, $user)-1);
		$fail = 1 + ($this->failValue($skill, $user)-1);
		$tirada = mt_rand(1,100);
		
		//Resultado de la ejecución
		if ($tirada <= $fail)
			$this->_result = 'fail'; //Pifia
		else {
			//Normal o Crítico
			$this->_result = 'normal';
			
			if ($tirada >= $critic)
				$this->_result = 'critic'; //Crítico
			
			//Pago el coste
			if ($this->paySkillCosts($skill, $user, $this->_result) === false)
                throw new CHttpException(400, $this->_error);
			
			//Ejecuto la skill
			switch ($skill->keyword) {
				case Yii::app()->params->skillHidratar: $this->hidratar($skill, $user, $finalTarget); break;
                case Yii::app()->params->skillDesecar: $this->desecar($skill, $user, $finalTarget); break;
				case Yii::app()->params->skillDisimular: $this->disimular($skill, $user, $finalTarget); break;
				case Yii::app()->params->skillCazarGungubos: $this->cazarGungubos($skill, $user); break;
				case Yii::app()->params->skillEscaquearse: $this->escaquearse($skill, $user); break;
				case Yii::app()->params->skillGungubicidio: $this->gungubicidio($skill, $user); break;
			}
			
		}
		
		//Mensaje
		if ($target===null) $finalName = '';  //si venía nulo, es porque no tiene objetivo y no pongo nada
        elseif ($this->_finalTarget == $this->_caster) $finalName = ' sobre sí mismo';
        else $finalName = ' sobre '.Yii::app()->usertools->getAlias($this->_finalTarget);

        if ($this->_result == 'fail')
            $this->_resultMessage = ':'.$skill->keyword.': Ha pifiado al intentar ejecutar la habilidad '.$skill->name.$finalName.'.';
        else if ($this->_result == 'normal')
            $this->_resultMessage = ':'.$skill->keyword.': Ha ejecutado la habilidad '.$skill->name.$finalName.'.';
        else if ($this->_result == 'critic')
            $this->_resultMessage = ':'.$skill->keyword.': Ha hecho un crítico ejecutando la habilidad '.$skill->name.$finalName.'.';

		return true;
    }
	
	/************* SKILLS ************/
	// Crea un modificador de "hidratado"
	private function hidratar($skill, $user, $target) 
	{
	    //si ya tengo hidratar, lo que hago es actualizar sus datos, ya que solo puede haber uno (busco por keyword porque puede estar creado por diferentes fuentes)
        $modificador = Modifier::model()->find(array('condition'=>'target_final_id=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->id, ':keyword'=>$skill->modifier_keyword)));

        if ($modificador == null)
            $modificador = new Modifier;

	    $modificador->caster_id = $user->id;
	    $modificador->target_final_id = $target->id;
	    $modificador->skill_id = $skill->id;
	    $modificador->keyword = $skill->modifier_keyword;
	    $modificador->duration = $skill->duration;
	    $modificador->duration_type = $skill->duration_type;
	    $modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

	    if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.').');

		return true;
	}

    // Crea un modificador de "desecado"
    private function desecar($skill, $user, $target)
    {
        //si ya tengo desecar, lo que hago es actualizar sus datos, ya que solo puede haber uno (busco por keyword porque puede estar creado por diferentes fuentes)
        $modificador = Modifier::model()->find(array('condition'=>'target_final_id=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->id, ':keyword'=>$skill->modifier_keyword)));

        if ($modificador == null)
            $modificador = new Modifier;

        $modificador->caster_id = $user->id;
        $modificador->target_final_id = $target->id;
        $modificador->skill_id = $skill->id;
        $modificador->keyword = $skill->modifier_keyword;
        $modificador->duration = $skill->duration;
        $modificador->duration_type = $skill->duration_type;
        $modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

        if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.').');

        return true;
    }
	
	//Crea un modificador de "disimulando"
	private function disimular($skill, $user, $target)
	{
		//Si ya tengo disimular lo que haré será sumar 1 uso
		$modificador = Modifier::model()->find(array('condition'=>'target_final_id=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->id, ':keyword'=>$skill->modifier_keyword)));

        if ($modificador == null) {
            $modificador = new Modifier;
			$modificador->duration = $skill->duration;
		} else
			$modificador->duration += $skill->duration;
		
		$modificador->caster_id = $user->id;
	    $modificador->target_final_id = $target->id;
	    $modificador->skill_id = $skill->id;
	    $modificador->keyword = $skill->modifier_keyword;	    
	    $modificador->duration_type = $skill->duration_type;
		$modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

	    if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.').');

		return true;
	}
	
	//Caza gungubos y me pone como Cazador si estaba como Criador
	private function cazarGungubos($skill, $user)
	{
		$cantidad = 100;
	
		//Cambio al usuario a Cazador si era criador
		if ($user->status == Yii::app()->params->statusCriador) {
			$user->status = Yii::app()->params->statusCazador;
		
			if (!$user->save())
				throw new CHttpException(400, 'Error al guardar el estado del usuario ('.$user->id.') a Cazador.');
		}
			
		$event = Yii::app()->event->model;
		if($user->side == 'kafhe') {
			$event->gungubos_kafhe += $cantidad;
		} else if($user->side == 'achikhoria') {
			$event->gungubos_achikhoria += $cantidad;
		}
		
		if (!$event->save())
            throw new CHttpException(400, 'Error al sumar gungubos al bando '.$user->side.' del evento ('.$event->id.').');
		
		return true;
	}
	
	//Relanza el evento
	public function escaquearse($skill, $user)
	{
		//Lanzo de nuevo el evento
		$event = Yii::app()->event->model;
		if ($event === null)
			throw new CHttpException(400, 'Error al cargar el evento Escaqueandose.');
		
		//Elijo al llamador
		$battleResult = Yii::app()->event->selectCaller();
		$event->caller_id = $battleResult['userId'];
		$event->caller_side = $battleResult['side'];
		
		//Guardo el evento
		if (!$event->save())
			throw new CHttpException(400, 'Error al guardar el estado del evento '.$event->id.' a '.$event->status.'.');

		//Aviso al llamador
		$caller = User::model()->findByPk($event->caller_id);
		$sent = Yii::app()->mail->sendEmail(array(
		    'to'=>$caller->email,
		    'subject'=>'¡A llamar!',
		    'body'=>'El Gran Omelettus dictamina que te toca llamar.'
		    ));
		if ($sent !== true)
		    Yii::log($sent, 'error', 'Email escaqueo');
            //throw new CHttpException(400, $sent);
		
		return true;
	}
	
	// Mata 100 gungubos de un bando aleatorio
	private function gungubicidio($skill, $user) 
	{
		$cantidad = 100;
		
		//Elijo un bando aleatorio
		$rand = mt_rand(0,1);
		if ($rand==0) $bando = 'kafhe';
		else $bando = 'achikhoria';
		
		//Mato 100 gungubos de ese bando :O
		$event = Yii::app()->event->model;
		
		if ($bando == 'kafhe') {
			$event->gungubos_kafhe = max(0, ($event->gungubos_kafhe-$cantidad)); //Evito que sea negativo el valor
		} elseif ($bando == 'achikhoria') {
			$event->gungubos_achikhoria = max(0, ($event->gungubos_achikhoria-$cantidad)); //Evito que sea negativo el valor
		} 
		
		if(!$event->save())
			throw new CHttpException(400, 'Error al restar gungubos al bando '.$bando.' del evento '.$event->id.'.');
			
		return true;
	}
	
	
	
	
	/************** FUNCIONES AUXILIARES *************/
	public function paySkillCosts($skill, $user, $executionResult) 
	{
	    //Compruebo si tengo tueste
	    if ($skill->cost_tueste !== null  &&  $skill->cost_tueste > $user->ptos_tueste) {
            $this->_error = 'No tienes suficiente Tueste.';
            return false;
	    }

	    //Compruebo si tengo retueste
        if ($skill->cost_retueste !== null  &&  $skill->cost_retueste > $user->ptos_retueste) {
            $this->_error = 'No tienes suficiente Retueste.';
            return false;
        }

	    //Compruebo si tengo tostólares
        if ($skill->cost_tostolares !== null  &&  $skill->cost_tostolares > $user->tostolares) {
            $this->_error = 'No tienes suficientes tostólares.';
            return false;
        }

	    //Compruebo si tengo ptos relanzamiento
        if ($skill->cost_relanzamiento !== null  &&  $skill->cost_relanzamiento > $user->ptos_relanzamiento) {
            $this->_error = 'No tienes suficiente puntos de relanzamiento.';
            return false;
        }

        //Si ha sido crítico, cuesta menos
        $criticModificator = array('tueste'=>1, 'retueste'=>1, 'tostolares'=>1, 'relanzamiento'=>1);
        if ($executionResult == 'critic') {
            $criticModificator = array('tueste'=>0.5, 'retueste'=>0.75, 'tostolares'=>0.5, 'relanzamiento'=>1);
        }

        //Pago el tueste
        if ($skill->cost_tueste !== null)
            $user->ptos_tueste = $user->ptos_tueste - round($skill->cost_tueste * $criticModificator['tueste']);

	    //Pago el restueste
        if ($skill->cost_retueste !== null)
            $user->ptos_retueste = $user->ptos_retueste - round($skill->cost_retueste * $criticModificator['retueste']);

	    //Pago los tostólares
        if ($skill->cost_tostolares !== null)
            $user->tostolares = $user->tostolares - round($skill->cost_tostolares * $criticModificator['tostolares']);

	    //Pago los puntos de relanzamiento
        if ($skill->cost_relanzamiento !== null)
            $user->ptos_relanzamiento = $user->ptos_relanzamiento - round($skill->cost_relanzamiento * $criticModificator['relanzamiento']);

        //Salvo todo
	    if (!$user->save())
            throw new CHttpException(400, 'Error al actualizar el usuario ('.$user->id.') tras ejecutar una habilidad ('.$skill->id.').');

	    return true;
	}

	public function criticValue($skill, $user) {
		$critic = $skill->critic;
		return $critic;
	}
	
	public function failValue($skill, $user) {
		$fail = $skill->fail;
		return $fail;
	}
	
	///TODO tratar los bandos
	private function calculateFinalTarget($skill, $user, $target, $side) {
		if ($target === null) {
			$this->_finalTarget = $this->_caster;
			return $user; //Si no hay objetivo, es que el objetivo es uno mismo
		} else {		
			$finalTarget = $target;
			$this->_finalTarget = $finalTarget->id;
		}

		return $finalTarget;
	}
	
	
	
	/******** GETTERS **********/
	public function getCaster() { return $this->_caster; }
	public function getOriginalTarget() { return $this->_originalTarget; }
	public function getFinalTarget() { return $this->_finalTarget; }
	public function getKeyword() { return $this->_keyword; }
	public function getResult() { return $this->_result; }
	public function getResultMessage() { return $this->_resultMessage; }
	public function getError() { return $this->_error; }

	
}