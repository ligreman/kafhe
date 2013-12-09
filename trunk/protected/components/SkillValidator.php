<?php

/**
 * SkillValidator para la validación de todo lo relacionado con ejecutar habilidades
 * Ha de instanciarse (new SkillValidator)
 */
class SkillValidator
{	
	private $_lastError = '';

    /** Comprueba si puedo o no ejecutar una habilidad
     * @param $skill Objeto de la habilidad ejecutándose.
     * @param null $target ID objetivo seleccionado.
     * @param null $side_target Bando objetivo seleccionado.
     * @param null $extra_param Parámetros extra
     * @param bool $is_executing Indica si estoy intentando ejecutar o no la habilidad. Si es true se comprueba el objetivo.
     * @return int
     *      0 - Fallo. No hay un objetivo válido seleccionado.
     *      1 - Correcto. Puedes ejecutar la habilidad.
     *      2 - Fallo. No tienes suficiente tueste, retueste, tostólares, gungubos, o lágrimas para pagar el coste de la habilidad.
     *      3 - Fallo. No tienes el estado adecuado para ejecutar la habilidad.
     *      4 - Fallo. No estás en el bando correcto para ejecutar la habilidad.
     *      5 - Fallo. No tienes el rango exigido para ejecutar la habilidad.
     *      6 - Fallo. El evento no se encuentra en el estado requerido o no eres el llamador.
     *      7 - Fallo. No tienes el talento requerido para ejecutar esta habilidad.
     *      8 - Fallo. Hay modificadores que te impiden ejecutar la habilidad.
     *      9 - Fallo. Faltan parámetros extra.
     */
    public function canExecute($skill, $target=null, $side_target=null, $extra_param=null, $is_executing=false)
	{
	    $this->_lastError = '';
		$user = Yii::app()->currentUser->model;

		//¿Requiere un estado concreto del usuario?
		if ($skill->require_user_status!==null && !$this->checkUserStatus($skill, $user))
			return 3;
		
		//¿Requiere un bando concreto del usuario?
		if ($skill->require_user_side!==null && !$this->checkUserSide($skill, $user))
			return 4;
		
		//¿Requiere un rango mínimo para el usuario?
		if ($skill->require_user_min_rank!==null && !$this->checkUserRank($skill, $user))
			return 5;

        //¿Requiere un rango máximo para el usuario?
        if ($skill->require_user_max_rank!==null && !$this->checkUserRank($skill, $user))
            return 5;
		
		//¿Requiere que yo sea el llamador actualmente?
		if ($skill->require_caller && !$this->checkCaller($skill, $user))
			return 6;
			
		//¿Hay una batalla iniciada (event.status=2)?
		if ($skill->require_event_status!==null && !$this->checkEventStatus($skill))
		    return 6;

		//¿Requiere un talento concreto?
		if ($skill->require_talent_id!==null && !$this->checkTalent($skill, $user))
		    return 7;



        //¿Tengo tueste suficiente?
        if ($skill->cost_tueste!==null && !$this->checkTueste($skill, $user))
            return 2;

        //¿Tengo retueste suficiente?
        if ($skill->cost_retueste!==null && !$this->checkRetueste($skill, $user))
            return 2;

        //¿Tengo puntos de relanzamiento suficientes?
        if ($skill->cost_relanzamiento!==null && !$this->checkPuntosRelanzamiento($skill, $user))
            return 2;

        //¿Tengo tostolares suficientes?
        if ($skill->cost_tostolares!==null && !$this->checkTostolares($skill, $user))
            return 2;

        //¿Tengo gungubos suficientes?
		if ($skill->cost_gungubos!==null && !$this->checkGungubos($skill, $user))
			return 2;

        //¿Tengo algún modificador que me impida ejecutar esta habilidad?
        if (!$this->checkModifiers($user))
            return 8;



        //Comprobaciones sólo si estoy intentando ejecutar una habilidad
		if ($is_executing) {
			//¿Requiere elegir usuario objetivo?
			if ($skill->require_target_user && !$this->checkTargetUser($skill, $user, $target))
				return 0;
				
			//¿Requiere elegir bando objetivo? Si el require_target_user es null pero no el require_target_side
			if (!$skill->require_target_user && $skill->require_target_side!==null && !$this->checkSideTarget($skill, $user, $side_target))
				return 0;

			//Es una habilidad especial que requiere parámetros extra
			    if ($skill->keyword==Yii::app()->params->skillGunbudoAsaltante && !$this->checkGunbudoAsaltanteWeapons($skill, $extra_param))
			        return 9;

		}
		
		//Si todo ha ido bien
		return 1;
	}
	
	public function canCooperate() {
	}
	
	public function getLastError()
	{
		return $this->_lastError;
	}
	
	/************************************** CHECKS ******************************************/
	/****************************************************************************************/
	
	private function checkTueste($skill, $user) {
	    $costeTueste = Yii::app()->skill->calculateCostTueste($skill);

		if ($skill->cost_tueste == null) return true;
		else if ($costeTueste <= $user->ptos_tueste) return true;
		else {
			$this->_lastError = 'No tienes suficiente Tueste.';
			return false;
		}
	}

    private function checkRetueste($skill, $user) {
		if ($skill->cost_retueste == null) return true;
		else if ($skill->cost_retueste <= $user->ptos_retueste) return true;
		else {
			$this->_lastError = 'No tienes suficiente ReTueste.';
			return false;
		}
	}

    private function checkPuntosRelanzamiento($skill, $user) {
		if ($skill->cost_relanzamiento == null) return true;
		else if ($skill->cost_relanzamiento <= $user->ptos_relanzamiento) return true;
		else {
			$this->_lastError = 'No tienes suficientes Puntos de Relanzamiento.';
			return false;
		}
	}

    private function checkTostolares($skill, $user) {
		if ($skill->cost_tostolares == null) return true;
		else if ($skill->cost_tostolares <= $user->tostolares) return true;
		else {
			$this->_lastError = 'No tienes suficientes Tostólares.';
			return false;
		}
	}
	
	private function checkGungubos($skill, $user) {
		if ($skill->cost_gungubos == null) return true;		
		else if ($skill->cost_gungubos <= Yii::app()->currentUser->gungubosCorral) return true;
		else {
			$this->_lastError = 'No tienes suficientes Gungubos en tu corral.';
			return false;
		}
	}

    private function checkUserStatus($skill, $user) {
		if ($skill->require_user_status == null) return true;
		
		$estados = explode(',', $skill->require_user_status);
		
		if (in_array($user->status, $estados)) return true;
		else {
			$this->_lastError = 'No tienes el estado requerido por la habilidad (alistado, no alistado, etc).';
			return false;
		}
	}

    private function checkUserSide($skill, $user) {
		if ($skill->require_user_side == null) return true;
		
		$sides = explode(',', $skill->require_user_side);
		
		if (in_array($user->side, $sides)) return true;
		else {
			$this->_lastError = 'No estás en el bando requerido por la habilidad.';
			return false;
		}
	}

    private function checkUserRank($skill, $user) {
		if ($skill->require_user_min_rank == null  &&  $skill->require_user_max_rank == null) return true;
		else if ($skill->require_user_min_rank !== null && $skill->require_user_min_rank > $user->rank) {
            $this->_lastError = 'Todavía no has alcanzado el rango necesario para ejecutar esta habilidad.';
            return false;
        } else if ($skill->require_user_max_rank !== null && $skill->require_user_max_rank < $user->rank) {
            $this->_lastError = 'Tu rango es demasiado alto para ejecutar esta habilidad.';
            return false;
        } else
            return true;
	}

    private function checkTalent($skill, $user) {
		/*if ($skill->talent_id_required == null) return true;
		else if ( TalentUser::model()->exists('user_id=:userId AND talent_id=:talentId', array(':userId'=>$user->id, ':talentId'=>$skill->talent_id_required)) )
			return true;
		else {
			$this->_lastError = 'No tienes el Talento requerido para ejecutar esta habilidad.';
			return false;
		}*/
		return true;
	}

    private function checkEventStatus($skill) {
        $event = Yii::app()->event->model;

        if ($skill->require_event_status == null) return true;
        else if (isset($event)) {
            $statuses = explode(',', $skill->require_event_status);

            if (in_array($event->status, $statuses)) return true;
            else {
                $this->_lastError = 'No puedes ejecutar la habilidad en este momento.';
                return false;
            }
        } else {
            $this->_lastError = 'Error: no hay ningún evento iniciado.';
            return false;
        }
    }
	
	//Compruebo si algún mod no me deja ejecutar esta habilidad
    private function checkModifiers($user) {
		return true;
	}

    private function checkCaller($skill, $user) {
		if (!$skill->require_caller) return true;
		else if (isset(Yii::app()->event->model)) {
			if (Yii::app()->event->callerId!=null && Yii::app()->event->callerId==$user->id) return true;
			else {
				$this->_lastError = 'No eres el actual llamador del evento.';
				return false;
			}
		} else {
			$this->_lastError = 'Error: no hay ningún evento iniciado.';
			return false;
		}
	}
	
	//Comprueba el objetivo y su bando si fuera necesario. Sólo para objetivos usuario (no si se hizo objetivo un bando)
    private function checkTargetUser($skill, $user, $target) {
		if (!$skill->require_target_user)
			return true;
		else {
			//Si no hay objetivo
			if ($target==null) { // || !is_object($target)) {
				$this->_lastError = 'No se ha seleccionado un objetivo válido para la habilidad.';
				return false;
			}
			
			//Compruebo que sea objetivo del mismo grupo que el usuario
			if ($user->group_id != $target->group_id) {
				$this->_lastError = 'El objetivo seleccionado no es válido.';
				return false;
			}
			
			//Compruebo que si además requería que el objetivo sea de un bando concreto, lo sea
			if ($skill->require_target_side!==null) {
				$sides = explode(',', $skill->require_target_side); //Bandos que requiere la skill

				if (!in_array($target->side, $sides)) {
					$this->_lastError = 'El objetivo seleccionado no pertenece al bando requerido por la habilidad.';
					return false;
				}
			}
			
			return true;
		}
	}

	private function checkGunbudoAsaltanteWeapons($skill, $extra_param) {
        if ($skill->keyword!=Yii::app()->params->skillGunbudoAsaltante)
            return true;
        else {
        Yii::log("Arma: ".$extra_param);
            if ($extra_param!=Yii::app()->params->gunbudoWeapon1 && $extra_param!=Yii::app()->params->gunbudoWeapon2 && $extra_param!=Yii::app()->params->gunbudoWeapon3) {
                $this->_lastError = 'No has seleccionado un arma válida para el Gunbudo Asaltante.';
                return false;
            } else
                return true;
        }
	}
	
	//Compruebo si el bando seleccionado es correcto, si se requería un bando concreto
    private function checkSideTarget($skill, $user, $side_target) {
		if (!$skill->require_target_user && $skill->require_target_side!==null) {
			$sides = explode(',', $skill->require_target_side); //Bando/s que requiere la skill
			
			if (!in_array($side_target, $sides)) {
				$this->_lastError = 'No se ha seleccionado un bando objetivo válido para la habilidad.';
				return false;
			} else
				return true;		
		} else
			return true;
	}	
}