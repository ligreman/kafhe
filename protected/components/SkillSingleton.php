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
	private $_resultMessage = ''; //Mensaje para el muro de notificaciones (incluye el publicMessage)
	private $_publicMessage = ''; //Texto extra para el mensaje del muro de notificaciones
	private $_privateMessage = ''; //Texto extra para el mensaje Flash
	private $_error = '';

    /** Ejecuta una habilidad.
     * @param $skill Objeto Skill de la habilidad.
     * @param $target Objeto User del objetivo seleccionado, o null si no se seleccionó ninguno
     * @param $side Nombre del bando seleccionado, o null si no se seleccionó ninguno
     * @return bool True si se ejecuta correctamente, false si hay fallos.
     */
    public function executeSkill($skill, $target, $side)
    {
        $this->_error = '';
		$this->_keyword = $skill->keyword;
        $this->_publicMessage = '';
		$this->_privateMessage = '';

        //Saco los nombres de los que intervienen
		$this->_caster = Yii::app()->currentUser->id;
		if ($target === null) $this->_originalTarget = Yii::app()->currentUser->id;
		else $this->_originalTarget = $target->id;

		//compruebo caducidad de modificadores		
		Yii::app()->modifier->checkModifiersExpiration();

        //Calculo cuál es el objetivo final, por si hay escudos y demás cosas por ahí
        $finalTarget = $this->calculateFinalTarget($skill, $target, $side);

        //Compruebo si caigo en una Trampa y no estoy ejecutando una habilidad de relanzamiento
        if($this->caigoTrampa() && $skill->category!='relanzamiento') {
            //Hago que pifie
            $tirada = $fail = 0;

            //Mensajes
            $this->_publicMessage = 'Ha caído en una trampa y eso ha provocado su pifia.';
            $this->_privateMessage = 'Has caído en una trampa y eso ha provocado que pifies.';
        } else {
            //Compruebo si es crítico o pifia. Son porcentajes. PIFIA: de 1 a (1+(fail-1)) || CRÍTICO: de (100-(critic-1)) a 100
            $critic = 100 - ($this->criticValue($skill)-1);
            $fail = 1 + ($this->failValue($skill)-1);
            $tirada = mt_rand(1,100);
        }
		
		//Resultado de la ejecución
		if ($tirada <= $fail) {
			$this->_result = 'fail'; //Pifia

            //Pago el coste igualmente
            if ($this->paySkillCosts($skill, $this->_result) === false)
                return false;
        } else {
			//Normal o Crítico
			$this->_result = 'normal';
			
			if ($tirada >= $critic)
				$this->_result = 'critic'; //Crítico
			
			//Pago el coste. Lo pongo aquí y duplicado para pagar antes de ejecutar la habilidad.
			if ($this->paySkillCosts($skill, $this->_result) === false)
			    return false;
			
			//Ejecuto la skill
			switch ($skill->keyword) {
				case Yii::app()->params->skillHidratar: $this->hidratar($skill, $finalTarget); break;
                case Yii::app()->params->skillDesecar: $this->desecar($skill, $finalTarget); break;
				case Yii::app()->params->skillDisimular: $this->disimular($skill, $finalTarget); break;
				case Yii::app()->params->skillCazarGungubos: $this->cazarGungubos(); break;
				case Yii::app()->params->skillEscaquearse: $this->escaquearse(); break;
				case Yii::app()->params->skillGungubicidio: $this->gungubicidio(); break;
                case Yii::app()->params->skillTrampa: $this->trampa($skill); break;
                case Yii::app()->params->skillMatarGungubos: $this->matarGungubos($skill); break;
			}
			
		}
		
		//Mensaje para el Muro de notificaciones y el Flash de feedbak del usuario
		if ($target===null) {
		    if ($side===null) $finalName = ''; //si venía nulo, es porque no tiene objetivo y no pongo nada
		    elseif ($side=='global') $finalName = ' sobre los jugadores que pertenecen a un bando';
		    else $finalName = ' sobre el bando '.Yi::app()->params->sideNames[$side];
        }
        elseif ($this->_finalTarget == $this->_caster) $finalName = ' sobre sí mismo';
        else $finalName = ' sobre '.Yii::app()->usertools->getAlias($this->_finalTarget);

        if ($this->_result == 'fail') {
            $this->_resultMessage = ':'.$skill->keyword.': Ha pifiado al intentar ejecutar la habilidad '.$skill->name.$finalName.'. '.$this->_publicMessage;
            Yii::app()->user->setFlash(Yii::app()->skill->result, 'Has pifiado al ejecutar '.$skill->name.$finalName.'. '.$this->_privateMessage);
        } else if ($this->_result == 'normal') {
            $this->_resultMessage = ':'.$skill->keyword.': Ha ejecutado la habilidad '.$skill->name.$finalName.'. '.$this->_publicMessage;
            Yii::app()->user->setFlash(Yii::app()->skill->result, 'Has ejecutado '.$skill->name.$finalName.' correctamente. '.$this->_privateMessage);
        } else if ($this->_result == 'critic') {
            $this->_resultMessage = ':'.$skill->keyword.': Ha hecho un crítico ejecutando la habilidad '.$skill->name.$finalName.'. '.$this->_publicMessage;
            Yii::app()->user->setFlash(Yii::app()->skill->result, '¡Has hecho un crítico al ejecutar '.$skill->name.$finalName.'! '.$this->_privateMessage);
        }

		return true;
    }
	
	/************* SKILLS ************/
    /** Crea un modificador de "hidratado"
     * @param $skill Obj de la skill
     * @param $target Obj del target
     * @return bool
     */
    private function hidratar($skill, $target)
	{
	    //Si tengo Desecar, lo que hago es quitármelo de encima en vez de ejecutar hidratar
        $modificador = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->id, ':keyword'=>Yii::app()->params->modifierDesecado)));
        if ($modificador !== null) {
            if (!$modificador->delete())
                throw new CHttpException(400, 'Error al eliminar el modificador ('.Yii::app()->params->modifierDesecado.').');

            $this->_privateMessage = 'Al estar el objetivo previamente '.Yii::app()->params->modifierDesecado.', '.$skill->name.' únicamente ha eliminado esa penalización.';
            return true;
        }

	    //si ya tengo hidratar, lo que hago es actualizar sus datos, ya que solo puede haber uno (busco por keyword porque puede estar creado por diferentes fuentes)
        $modificador = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->id, ':keyword'=>$skill->modifier_keyword)));

        if ($modificador === null)
            $modificador = new Modifier;

	    $modificador->event_id = Yii::app()->event->id;
	    $modificador->caster_id = Yii::app()->currentUser->id;
	    $modificador->target_final = $target->id;
	    $modificador->skill_id = $skill->id;
	    $modificador->keyword = $skill->modifier_keyword;
	    $modificador->duration = $skill->duration;
	    $modificador->duration_type = $skill->duration_type;
	    $modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

	    if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.').');

		return true;
	}

    /** Crea un modificador de "desecado"
     * @param $skill Obj de la skill
     * @param $target Obj del target
     * @return bool
     */
    private function desecar($skill, $target)
    {
        //Si tiene Hidratar, lo que hago es quitarlo en vez de ejecutar desecar
        $modificador = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->id, ':keyword'=>Yii::app()->params->modifierHidratado)));
        if ($modificador !== null) {
            if (!$modificador->delete())
                throw new CHttpException(400, 'Error al eliminar el modificador ('.Yii::app()->params->modifierHidratado.').');

            $this->_privateMessage = 'Al estar el objetivo previamente '.Yii::app()->params->modifierHidratado.', '.$skill->name.' únicamente ha eliminado esa bonificación.';
            return true;
        }

        //si ya tengo desecar, lo que hago es actualizar sus datos, ya que solo puede haber uno (busco por keyword porque puede estar creado por diferentes fuentes)
        $modificador = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->id, ':keyword'=>$skill->modifier_keyword)));

        if ($modificador === null)
            $modificador = new Modifier;

        $modificador->event_id = Yii::app()->event->id;
        $modificador->caster_id = Yii::app()->currentUser->id;
        $modificador->target_final = $target->id;
        $modificador->skill_id = $skill->id;
        $modificador->keyword = $skill->modifier_keyword;
        $modificador->duration = $skill->duration;
        $modificador->duration_type = $skill->duration_type;
        $modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

        if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.').');

        return true;
    }

    /** Crea un modificador de "disimulando"
     * @param $skill Obj de la skill
     * @param $target Obj del target
     * @return bool
     */
	private function disimular($skill, $target)
	{
		//Si ya tengo disimular lo que haré será sumar 1 uso
		$modificador = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->id, ':keyword'=>$skill->modifier_keyword)));

        if ($modificador == null) {
            $modificador = new Modifier;
			$modificador->duration = $skill->duration;
		} else
			$modificador->duration += $skill->duration;

        $modificador->event_id = Yii::app()->event->id;
		$modificador->caster_id = Yii::app()->currentUser->id;
	    $modificador->target_final = $target->id;
	    $modificador->skill_id = $skill->id;
	    $modificador->keyword = $skill->modifier_keyword;	    
	    $modificador->duration_type = $skill->duration_type;
		$modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

	    if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.').');

		return true;
	}

    /** Caza gungubos y me pone como Cazador si estaba como Criador
     * @return bool
     */
	private function cazarGungubos()
	{
		$user = Yii::app()->currentUser->model; //cojo el usuario actual
		$cantidad = 100;
	
		//Cambio al usuario a Cazador si era criador
		if ($user->status == Yii::app()->params->statusCriador) {
			$user->status = Yii::app()->params->statusCazador;
		
			if (!$user->save())
				throw new CHttpException(400, 'Error al guardar el estado del usuario ('.$user->id.') a Cazador.');
		}

		$event = Yii::app()->event->model; //Cojo el evento (desayuno) actual
		if($user->side == 'kafhe') {
			$event->gungubos_kafhe += $cantidad;
		} elseif ($user->side == 'achikhoria') {
			$event->gungubos_achikhoria += $cantidad;
		}
		
		if (!$event->save())
            throw new CHttpException(400, 'Error al sumar gungubos al bando '.$user->side.' del evento ('.$event->id.').');
		
		return true;
	}

    /** Mata gungubos
     * @return bool
     */
    private function matarGungubos()
    {
        $user = Yii::app()->currentUser->model; //cojo el usuario actual
        $cantidad = 100;

        $event = Yii::app()->event->model; //Cojo el evento (desayuno) actual
        if($user->side == 'kafhe') {
            $event->gungubos_achikhoria -= $cantidad;
            if($event->gungubos_achikhoria <0) $event->gungubos_achikhoria = 0;
        } elseif ($user->side == 'achikhoria') {
            $event->gungubos_kafhe -= $cantidad;
            if($event->gungubos_kafhe <0) $event->gungubos_kafhe = 0;
        }

        if (!$event->save())
            throw new CHttpException(400, 'Error al restar gungubos desde el bando '.$user->side.' del evento ('.$event->id.').');

        return true;
    }
	
    /** Relanza el evento
     * @return bool
     */
	public function escaquearse()
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
	
    /** Mata 100 gungubos de un bando aleatorio
     * @return bool
     */
	private function gungubicidio()
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


    /** Crea un modificador de "trampa"
     * @param $skill Obj de la skill
     * @return bool
     */
    private function trampa($skill)
    {
        //si ya tengo trampas puestas, lo que hago es sumar 1 a sus usos
        $modificador = Modifier::model()->find(array('condition'=>'keyword=:keyword', 'params'=>array(':keyword'=>$skill->modifier_keyword)));

        if ($modificador === null) {
            $modificador = new Modifier;
            $modificador->duration = $skill->duration;
        } else
            $modificador->duration++;

        $modificador->duration_type = $skill->duration_type;
        $modificador->event_id = Yii::app()->event->id;
        $modificador->caster_id = Yii::app()->currentUser->id;
        $modificador->target_final = 'global'; //afecta a ambos bandos
        $modificador->skill_id = $skill->id;
        $modificador->keyword = $skill->modifier_keyword;
        $modificador->hidden = $skill->modifier_hidden;
        $modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

        if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.').');

        return true;
    }
	
	
	
	/************** FUNCIONES AUXILIARES *************/
    /** Pago el coste de ejecutar la habilidad
     * @param $skill Obj de la skill
     * @param $executionResult Texto con el resultado de la ejecución, si fue critic, normal...
     */
    private function paySkillCosts($skill, $executionResult)
	{
		$user = Yii::app()->currentUser->model;
	    //No compruebo nada porque se ha comprobado ya antes de llegar a executeSkill

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

    /** Calcula el valor de crítico de la habilidad
     * @param $skill Obj de la skill
     * @return int Valor del crítico
     */
    private function criticValue($skill) {
		$critic = $skill->critic;
		return $critic;
	}

    /** Calcula el valor de pifia de la habilidad
     * @param $skill Obj de la skill
     * @return int Valor de la pifia
     */
	private function failValue($skill) {
		$fail = $skill->fail;
		return $fail;
	}

    /** Calcula el objetivo final de la habilidad
     * @param $skill objeto de la skill ejecutada
     * @param $target objeto del objetivo o NULL si no hay
     * @param $side texto del bando objetivo o NULL si no hay
     * @return object|text Objeto objetivo o el texto del bando
     */
    private function calculateFinalTarget($skill, $target, $side) {
		if ($target===null && $side===null) {
            $finalTarget = Yii::app()->currentUser->model; //Si no hay objetivo, es que el objetivo es uno mismo. 
			$this->_finalTarget = Yii::app()->currentUser->id;
		} elseif ($target===null && $side!==null) {
		    $finalTarget = $side;
            $this->_finalTarget = $side; //texto, kafhe, achikhoria, global
        } elseif ($target!==null) {
			$finalTarget = $target;
			$this->_finalTarget = $finalTarget->id;
		}

		return $finalTarget;
	}

    /** Comprueba si el usuario activo tiene un modificador Trampa afectándole, y reduce el modificador en caso afirmativo
     * @return bool si está o no afectado por una trampa
     */
    private function caigoTrampa() {
	    //Si existe el modificador "trampa" entre los que me afectan
	    $modificadorTrampa = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierTrampa);
	    if ($modificadorTrampa !== false) {
	        //Reduzco los usos del modificador trampa
            if (!Yii::app()->modifier->reduceModifierUses($modificadorTrampa))
                throw new CHttpException(400, 'Error al eliminar el modificador Trampa.');

	        return true;
	    } else return false;
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