<?php

/** Gestión de recompensas
 */
class RewardSingleton extends CApplicationComponent
{
    private $_rewards = array(
		'rwMoreCritic', 'rwLessFail', 'rwMinTueste', 'rwMoreRegen'
	);
	
	public function giveReward($user, $eventId, $reward=null) {
		$recompensas = $this->_rewards;
		
		if ($reward===null) {
			//Elijo una aleatoria
			$cual = mt_rand(1, count($recompensas));
			$reward = $recompensas[$cual-1];
		}
		
		//Le doy la recompensa al colega
		switch($reward) {
			case 'rwMoreCritic':
				$result = $this->rwMoreCritic($user, $eventId);
				break;
			case 'rwLessFail':
				$result = $this->rwLessFail($user, $eventId);
				break;
			case 'rwMinTueste':
				$result = $this->rwMinTueste($user, $eventId);
				break;
			case 'rwMoreRegen':
				$result = $this->rwMoreRegen($user, $eventId);
				break;
		}
		
		if ($result===false) {			
            throw new CHttpException(400, 'Error al otorgar la recompensa '.$reward.' al usuario '.$user->username.'.');
		} else {
			//Creo notificación para él solo
			$notiA = new Notification;			
			$notiA->recipient_final = $user->id;
			$notiA->type = 'system';
			$notiA->message = $result;
			if (!$notiA->save())
				throw new CHttpException(400, 'Error al guardar la notificación de dar recompensa '.$reward.' al usuario '.$user->username.' en evento '.$eventId.'.');
		}
		
		return true;
	}
	
	private function rwMoreCritic($user, $eventId) {
		//Creo un modificador para el usuario
		$mod = new Modidier;
		$mod->event_id = $eventId;
		$mod->caster_id = $user->id;
		$mod->target_final = $user->id;		
		$mod->keyword = Yii::app()->params->rwMoreCritic;
		$mod->value = intval(Yii::app()->config->getParam('rewardMoreCritic'));
		$mod->duration = 1;
		$mod->duration_type = 'evento'; //Todo el desayuno
		
		if (!$mod->save())
			throw new CHttpException(400, 'Error al guardar el modificador por recompensa rwMoreCritic del usuario '.$user->username.' en evento '.$eventId.'.');
			
		$msg = 'Omelettus te ha concedido un aumento del '.$mod->value.'% al crítico durante esta nueva batalla, como recompensa porque tu bando ganó la batalla anterior.';
		return $msg;
	}
	
	private function rwLessFail($user, $eventId) {
		//Creo un modificador para el usuario
		$mod = new Modidier;
		$mod->event_id = $eventId;
		$mod->caster_id = $user->id;
		$mod->target_final = $user->id;		
		$mod->keyword = Yii::app()->params->rwLessFail;
		$mod->value = intval(Yii::app()->config->getParam('rewardLessFail'));
		$mod->duration = 1;
		$mod->duration_type = 'evento'; //Todo el desayuno
		
		if (!$mod->save())
			throw new CHttpException(400, 'Error al guardar el modificador por recompensa rwLessFail del usuario '.$user->username.' en evento '.$eventId.'.');
			
		$msg = 'Omelettus te ha concedido una disminución del '.$mod->value.'% a la pifia durante esta nueva batalla, como recompensa porque tu bando ganó la batalla anterior.';
		return $msg;
	}
	
	private function rwMinTueste($user, $eventId) {
		//Creo un modificador para el usuario
		$mod = new Modidier;
		$mod->event_id = $eventId;
		$mod->caster_id = $user->id;
		$mod->target_final = $user->id;		
		$mod->keyword = Yii::app()->params->rwMinTueste;
		$mod->value = intval(Yii::app()->config->getParam('rewardMinTueste'));
		$mod->duration = 1;
		$mod->duration_type = 'evento'; //Todo el desayuno
		
		if (!$mod->save())
			throw new CHttpException(400, 'Error al guardar el modificador por recompensa rwMinTueste del usuario '.$user->username.' en evento '.$eventId.'.');
			
		$msg = 'Omelettus te ha concedido que durante la próxima batalla tu tueste mínimo no bajará de '.$mod->value.'puntos, como recompensa porque tu bando ganó la batalla anterior.';
		return $msg;
	}
	
	private function rwMoreRegen($user, $eventId) {
		//Creo un modificador para el usuario
		$mod = new Modidier;
		$mod->event_id = $eventId;
		$mod->caster_id = $user->id;
		$mod->target_final = $user->id;		
		$mod->keyword = Yii::app()->params->rwMoreRegen;
		$mod->value = intval(Yii::app()->config->getParam('rewardMoreRegen'));
		$mod->duration = 1;
		$mod->duration_type = 'evento'; //Todo el desayuno
		
		if (!$mod->save())
			throw new CHttpException(400, 'Error al guardar el modificador por recompensa rwMoreRegen del usuario '.$user->username.' en evento '.$eventId.'.');
			
		$msg = 'Omelettus te ha concedido un aumento del '.$mod->value.'% a tu ritmo de regeneración de tueste durante esta nueva batalla, como recompensa porque tu bando ganó la batalla anterior.';
		return $msg;
	}
}


