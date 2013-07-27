<?php

/**
 * SkillValidator para la validación de todo lo relacionado con ejecutar habilidades
 * Ha de instanciarse (new SkillValidator)
 */
class SkillValidator
{	
	private $_lastError = '';
	
	/**
	* $is_executing: indica si la función se está llamando desde una ejecución de una habilidad o sólo es para comprobar si se podría ejecutar la misma (para la lista de habilidades)
	*/
	public function canExecute($skill, $user, $target=null, $is_executing=false)	
	{			
		//¿Tengo tueste suficiente?
		if ($skill->cost_tueste!=null && !$this->checkTueste($skill, $user))			
			return false;		
		
		//¿Tengo retueste suficiente?
		if ($skill->cost_retueste!=null && !$this->checkRetueste($skill, $user))			
			return false;		
		
		//¿Tengo puntos de relanzamiento suficientes?
		if ($skill->cost_relanzamiento!=null && !$this->checkPuntosRelanzamiento($skill, $user))			
			return false;		
		
		//¿Tengo tostolares suficientes?
		if ($skill->cost_tostolares!=null && !$this->checkTostolares($skill, $user))
			return false;		
			
		//¿Requiere un estado concreto del usuario?
		if ($skill->require_user_status!=null && !$this->checkUserStatus($skill, $user))
			return false;	
		
		//¿Requiere un bando concreto del usuario?
		if ($skill->require_user_side!=null && !$this->checkUserSide($skill, $user))
			return false;	
		
		//¿Requiere un rango mínimo para el usuario?
		if ($skill->require_user_min_rank!=null && !$this->checkUserRank($skill, $user))
			return false;
		
		//¿Requiere que yo sea el llamador actualmente?
		if ($skill->require_caller && !$this->checkCaller($skill, $user))
			return false;
		
		//¿Tengo algún modificador que me impida ejecutar esta habilidad?
		if (!$this->checkModifiers($user))
			return false;
		
		
		//Comprobaciones sólo si estoy intentando ejecutar una habilidad
		if ($is_executing) {
			//¿Requiere elegir objetivo?
			if ($skill->require_target && !$this->checkTarget($skill, $user, $target))
				return false;
		}
		
		//Si todo ha ido bien
		return true;
	}
	
	public function canCooperate() {
	}
	
	public function getLastError()
	{
		return $this->_lastError;
	}
	
	/************************************** CHECKS ******************************************/
	/****************************************************************************************/
	
	public function checkTueste($skill, $user) {
		if ($skill->cost_tueste == null) return true;
		else if ($skill->cost_tueste <= $user->ptos_tueste) return true;
		else {
			$this->_lastError = 'No tienes suficiente tueste.'.'Coste: '.$skill->cost_tueste.' // Tueste: '.$user->ptos_tueste.' -- '.$skill->name;
			return false;
		}
	}
	
	public function checkRetueste($skill, $user) {
		if ($skill->cost_retueste == null) return true;
		else if ($skill->cost_retueste <= $user->ptos_retueste) return true;
		else {
			$this->_lastError = 'No tienes suficiente ReTueste.';
			return false;
		}
	}
	
	public function checkPuntosRelanzamiento($skill, $user) {
		if ($skill->cost_relanzamiento == null) return true;
		else if ($skill->cost_relanzamiento <= $user->ptos_relanzamiento) return true;
		else {
			$this->_lastError = 'No tienes suficientes Puntos de Relanzamiento.';
			return false;
		}
	}
	
	public function checkTostolares($skill, $user) {
		if ($skill->cost_tostolares == null) return true;
		else if ($skill->cost_tostolares <= $user->tostolares) return true;
		else {
			$this->_lastError = 'No tienes suficientes Tostólares.';
			return false;
		}
	}
	
	public function checkUserStatus($skill, $user) {
		if ($skill->require_user_status == null) return true;
		else if ($skill->require_user_status == $user->status) return true;
		else {
			$this->_lastError = 'No tienes el estado requerido por la habilidad (alistado, no alistado, etc).';
			return false;
		}
	}
	
	public function checkUserSide($skill, $user) {
		if ($skill->require_user_side == null) return true;
		else if ($skill->require_user_side == $user->side) return true;
		else {
			$this->_lastError = 'No estás en el bando requerido por la habilidad.';
			return false;
		}
	}
	
	public function checkUserRank($skill, $user) {
		if ($skill->require_user_min_rank == null) return true;
		else if ($skill->require_user_min_rank <= $user->rank) return true;
		else {
			$this->_lastError = 'No tienes el rango necesario para ejecutar esta habilidad.';
			return false;
		}
	}
	
	public function checkTalent($skill, $user) {
		/*if ($skill->talent_id_required == null) return true;
		else if ( TalentUser::model()->exists('user_id=:userId AND talent_id=:talentId', array(':userId'=>$user->id, ':talentId'=>$skill->talent_id_required)) )
			return true;
		else {
			$this->_lastError = 'No tienes el Talento requerido para ejecutar esta habilidad.';
			return false;
		}*/
	}
	
	public function checkModifiers($user) {
		return true;
	}
	
	public function checkCaller($skill, $user) {
		if (!$skill->require_caller) return true;
		else if (isset(Yii::app()->event->model)) {
			if (Yii::app()->event->caller!=null && Yii::app()->event->caller==$user->id) return true;
			else {
				$this->_lastError = 'No eres el actual llamador del evento.';
				return false;
			}
		} else {
			$this->_lastError = 'Error: no hay ningún evento iniciado.';
			return false;
		}
	}
	
	//Comprueba el objetivo y su bando si fuera necesario
	public function checkTarget($skill, $user, $target) {
		if (!$skill->require_target) return true;
		else {			
			//Si no hay objetivo
			if ($target == null) {
				$this->_lastError = 'No se ha seleccionado un objetivo para la habilidad.';
				return false;
			}
			
			//Compruebo que sea objetivo del mismo grupo que el usuario
			if ($user->group_id != $target->group_id) {
				$this->_lastError = 'El objetivo seleccionado no es válido.';
				return false;
			}
			
			//Compruebo que si requería que el objetivo sea de un bando, lo sea
			if ($skill->require_target_side!=null && $skill->require_target_side!=$target->side) {
				$this->_lastError = 'El objetivo seleccionado no pertenece al bando requerido por la habilidad.';
				return false;
			}
		}
	}
	
}