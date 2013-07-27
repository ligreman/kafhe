<?php

/**
 * SkillSingleton para operaciones relacionadas con las habilidades
 * Podré acceder a resultMessage así: Yii::app()->skill->resultMessage; ya que es un CApplicationComponent.
 * Si no, tendría que acceder con Yii::app()->skill->getResultMessage();
 */
class SkillSingleton extends CApplicationComponent
{	
	private $_casterName = '';
	private $_originalTargetName = '';
	private $_finalTargetName = '';
	private $_result = '';
	private $_resultMessage = '';
	private $_error = '';

    public function executeSkill($skill, $user, $target)
    {
		$this->_casterName = Yii::app()->usertools->getAlias($user->id);
		if ($target === null) $this->_originalTargetName = 'sí mismo';
		else $this->_originalTargetName = Yii::app()->usertools->getAlias($target->id);
		
		//Compruebo si es crítico o pifia
		//son porcentajes. PIFIA: de 1 a (1+(fail-1)) || CRÍTICO: de (100-(critic-1)) a 100
		$critic = 100 - ($this->criticValue($skill, $user)-1);
		$fail = 1 + ($this->failValue($skill, $user)-1);
		$tirada = mt_rand(1,100);
		
		//Resultado de la ejecución
		if ($tirada <= $fail) {
			//Pifia
			$this->_result = 'fail';			
		} else {
			//Normal o Crítico
			$this->_result = 'normal';
			
			if ($tirada >= $critic) {
				//Crítico
				$this->_result = 'critic';
			}
			
			//Calculo cuál es el objetivo final, por si hay escudos y demás cosas por ahí
			$finalTarget = $this->calculateFinalTarget($skill, $user, $target);			
			
			//TODO restar tueste, dinero, etc... lo que cueste la skill
			
			//Ejecuto la skill
			switch ($skill->keyword) {
				case 'hidratar':
					$this->hidratar($skill, $finalTarget);
					break;
			}
			
		}
		
		//Mensaje
		//TODO comprobar si tiene modificador de disimular o de impersonar
		if ($this->_result == 'fail')
			$this->_resultMessage = $this->_casterName.' ha pifiado al intentar ejecutar la habilidad '.$skill->name.' sobre '.$this->_finalTargetName.'.';
		else if ($this->_result == 'normal')
			$this->_resultMessage = $this->_casterName.' ha ejecutado la habilidad '.$skill->name.' sobre '.$this->_finalTargetName.'.';
		else if ($this->_result == 'critic')
			$this->_resultMessage = $this->_casterName.' ha hecho un crítico ejecutando la habilidad '.$skill->name.' sobre '.$this->_finalTargetName.'.';
			
		return true;
    }
	
	/************* SKILLS ************/
	private function hidratar($skill, $user) {
		return true;
	}
	
	
	/************** FUNCIONES AUXILIARES *************/
	public function criticValue($skill, $user) {
		$critic = $skill->critic;
		
		return $critic;
	}
	
	public function failValue($skill, $user) {
		$fail = $skill->fail;
		
		return $fail;
	}
	
	private function calculateFinalTarget($skill, $user, $target) {
		if ($target === null) {
			$this->_finalTargetName = 'sí mismo';
			return $user; //Si no hay objetivo, es que el objetivo es uno mismo
		}

		//TODO calculo del finalTarget
		$finalTarget = $target;

		$this->_finalTargetName = Yii::app()->usertools->getAlias($finalTarget->id);
		return $finalTarget;
	}
	
	
	
	/******** GETTERS **********/
	public function getCasterName() {
		return $this->_casterName;
	}
	public function getOriginalTargetName() {
		return $this->_originalTargetName;
	}
	public function getFinalTargetName() {
		return $this->_finalTargetName;
	}
	public function getResult() {
		return $this->_result;
	}
	public function getResultMessage() {
		return $this->_resultMessage;
	}
	public function getError() {
		return $this->_error;
	}

	
}