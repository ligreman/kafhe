<?php

/** GunbudosSingleton para operaciones relacionadas con los gungubos de un evento
 */
class GunbudosSingleton extends CApplicationComponent
{
    public function gunbudoAsaltanteAttack($gunbudo_id) 
	{
		//Cojo el gunbudo
		$asaltante = Gunbudo::model()->findByPk($gunbudo_id);
		if ($asaltante===null) return true; //Si ya no existe el gunbudo, no hago nada

		$event_id = $asaltante->event_id;
		
		//Ahora he de sacar el jugador propietario para ver su bando
		$owner = User::model()->findByPk($asaltante->owner_id);
		
		if ($owner->side=='kafhe')
			$bando_opuesto = 'achikhoria';
		elseif ($owner->side=='achikhoria')
			$bando_opuesto = 'kafhe';
		else
			$bando_opuesto = null;
			
		//Ahora a ver a quién ataco
		$objetivo = Yii::app()->usertools->randomUser($owner->group_id, $bando_opuesto);
$objetivo->id = 2;
		
		//Saco las defensas del objetivo (gunbudos guardianes, mejoras del corral..)
		$guardianes = Gunbudo::model()->findAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND class=:clase AND actions>0', 'params'=>array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':clase'=>Yii::app()->params->gunbudoClassGuardian)));
		
		$ataque_exitoso = true;
        foreach($guardianes as $guardian) {
            $result = $this->resolveCombat($asaltante, $guardian);
            Yii::log('Resultado combate '.$result, 'info');

            //Si gana el defensor o hay empate se termina todo. Devuelve 0 en empate, 1 en gana atacante y 2 en gana defensor
            if ($result===0) { //Empate
                $ataque_exitoso = false;
            } elseif ($result===2) { //Defensor wins
                $ataque_exitoso = false;

                //Cambio de armas del gunbudo asaltante y salvo.
                $asaltante->weapon = $guardian->weapon;
                if (!$asaltante->save())
                    throw new CHttpException(400, 'Error al guardar el cambio de arma del Asaltante '.$asaltante->id.'.');

            } else { //Asaltante wins
                //le cambio de arma al defensor
                $guardian->weapon = $asaltante->weapon;
            }

            //Quito una acción al defensor
            $guardian->actions -= 1;

            if (!$guardian->save())
                throw new CHttpException(400, 'Error al guardar el cambio de arma del gunbudo Guardián '.$guardian->id.'.');

            //Continuo mirando el combate con el siguiente guardián si lo hubiere
        }

		//Si el atacante ha conseguido entrar y matar
		if ($ataque_exitoso) {
			$mata = mt_rand(1,5);
			
			//Si el gunbudo era sanguinario mata el doble
			if ($asaltante->trait == Yii::app()->params->traitSanguinario)
				$mata = $mata * $asaltante->trait_value; //Mata mucho más
				
			//Mato a los pobresitos gungubitos mandándolos al cementerio
			$cuantos = Gungubo::model()->updateAll(array('location'=>'cementerio', 'health'=>0), 'event_id=:evento AND owner_id=:owner AND location=:lugar ORDER BY RAND() LIMIT '.$mata.';', array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':lugar'=>'corral'));
			
			//Textos de notificaciones
			$txtA = ':'.Yii::app()->params->gunbudoClassAsaltante.': Tu Gunbudo Asaltante ha matado '.$cuantos.' gungubos en el corral de '.Yii::app()->usertools->getAlias($objetivo->id).'.';
			$txtD = ':'.Yii::app()->params->gunbudoClassGuardian.': Un Gunbudo Asaltante enemigo ha superado a tus Guardianes matando a '.$cuantos.' gungubos en tu corral.';
		} else {
			//Textos de notificaciones
			$txtA = ':'.Yii::app()->params->gunbudoClassAsaltante.': Los Gunbudos Guardianes del corral de '.Yii::app()->usertools->getAlias($objetivo->id).' han detenido el ataque de tu Gunbudo Asaltante.';
			$txtD = ':'.Yii::app()->params->gunbudoClassGuardian.': Tus Gunbudos Guardianes han detenido un ataque de un Asaltante en tu corral.';
		}
		
		//Notificaciones para el atacante
		$notiA = new NotificationCorral;
		$notiA->event_id = $event_id;
		$notiA->user_id = $asaltante->owner_id;
		$notiA->message = $txtA;
		if (!$notiA->save())
			throw new CHttpException(400, 'Error al guardar la notificación del Asaltante en evento '.$event_id.'.');
		
		//Notificaciones para el defensor
		$notiD = new NotificationCorral;
		$notiD->event_id = $event_id;
		$notiD->user_id = $objetivo->id;
		$notiD->message = $txtD;
		if (!$notiD->save())
			throw new CHttpException(400, 'Error al guardar la notificación del Asaltante en evento '.$event_id.'.');
		
		return true;
    }
	
	public function gunbudoNigromanteAttack($gunbudo_id) 
	{
        //Cojo el gunbudo
        $nigromante = Gunbudo::model()->findByPk($gunbudo_id);
        if ($nigromante===null) return true; //Si ya no existe el gunbudo, no hago nada
	}
	
	/***********************************************************************************************/
	/************* FUNCIONES AUXILIARES *****************/
	
	//Devuelve 0 en empate, 1 en gana atacante y 2 en gana defensor
	private function resolveCombat($atacante, $defensor) 
	{
		if ( ($atacante->weapon==Yii::app()->params->gunbudoWeapon1 && $defensor->weapon==Yii::app()->params->gunbudoWeapon2) ||
			($atacante->weapon==Yii::app()->params->gunbudoWeapon2 && $defensor->weapon==Yii::app()->params->gunbudoWeapon3) ||
			($atacante->weapon==Yii::app()->params->gunbudoWeapon3 && $defensor->weapon==Yii::app()->params->gunbudoWeapon1) )
			return 1;
			
		if ( ($defensor->weapon==Yii::app()->params->gunbudoWeapon1 && $atacante->weapon==Yii::app()->params->gunbudoWeapon2) ||
			($defensor->weapon==Yii::app()->params->gunbudoWeapon2 && $atacante->weapon==Yii::app()->params->gunbudoWeapon3) ||
			($defensor->weapon==Yii::app()->params->gunbudoWeapon3 && $atacante->weapon==Yii::app()->params->gunbudoWeapon1) )
			return 2;
				
		return 0;
	}
}


