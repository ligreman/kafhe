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

		//Saco las defensas del objetivo (gunbudos guardianes, mejoras del corral..)
		$guardianes = Gunbudo::model()->findAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND class=:clase AND actions>0', 'params'=>array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':clase'=>Yii::app()->params->gunbudoClassGuardian)));
		
		$ataque_exitoso = true;
        foreach($guardianes as $guardian) {
            $result = $this->resolveCombat($asaltante, $guardian);

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
			$mata = mt_rand( intval(Yii::app()->config->getParam('gunbudoAsaltanteMinMuertes')), intval(Yii::app()->config->getParam('gunbudoAsaltanteMaxMuertes')) );
			
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

		$event_id = $nigromante->event_id;
		
		//Ahora he de sacar el jugador propietario para ver su bando
		$owner = User::model()->findByPk($nigromante->owner_id);
		
		if ($owner->side=='kafhe') $bando_opuesto = 'achikhoria';
		elseif ($owner->side=='achikhoria') $bando_opuesto = 'kafhe';
		else $bando_opuesto = null;		
Yii::log('Bando opuesto: '.$bando_opuesto, 'info');
		//Calculo la cantidad de zombies que van a atacar, si no hay ninguno termino el ataque
		$cadaveres = Gungubo::model()->findAll(array('condition'=>'owner_id=:owner AND event_id=:evento AND location=:lugar', 'params'=>array(':owner'=>$owner->id, ':evento'=>$event_id, ':lugar'=>'cementerio')));
Yii::log('Hay '.count($cadaveres).' cadaveres', 'info');
		//Cada cadáver tiene un % de convertirse en zombie
		$probabilidadZombie = Yii::app()->config->getParam('gunbudoNigromanteProbabilidadZombie');		
		$probabilidadColera = Yii::app()->config->getParam('gunbudoNigromanteProbabilidadColera');
		$zombies = array();
		$colericos = 0;
		
		foreach($cadaveres as $cadaver) {
			$tirada = mt_rand(1,100);
			if ($tirada <= $probabilidadZombie) {
				//Zombie!!!!  ¿Será colérico?
Yii::log('Zombie!!!!', 'info');
				$tirada = mt_rand(1,100);
				if ($tirada <= $probabilidadColera) {
					//Sí!
Yii::log('Y es colerico!!!!', 'info');
					$cadaver->trait = Yii::app()->params->traitColera;
					$colericos++;
				}
				
				$zombies[] = $cadaver;
			}
			
			if (count($zombies) == Yii::app()->config->getParam('gunbudoNigromanteMaxZombies'))
				break; //si llego al máximo de zombies que puede convertir, termino de convertir
		}
Yii::log('Convierto estos zombies: '.count($zombies), 'info');
		if (count($zombies)==0) return true;	
			
		//Ahora a ver a quién ataco
		$objetivo = Yii::app()->usertools->randomUser($owner->group_id, $bando_opuesto);
Yii::log('Ataco a '.$objetivo->username.' con '.count($zombies).' zombies', 'info');
		//Saco las defensas del objetivo (gunbudos guardianes, mejoras del corral..)
		$guardianes = Gunbudo::model()->findAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND class=:clase AND actions>0', 'params'=>array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':clase'=>Yii::app()->params->gunbudoClassGuardian)));
Yii::log('Tiene '.count($guardianes).' guardianes', 'info');
		//por cada zombie, calculo si se pega con un guardián o no
		$zombies_atacan = 0;
		$zombies_muertos_ids = array();
		foreach ($zombies as $zombie) {
			if ( ($zombie->trait!=Yii::app()->params->traitColera) && count($guardianes)>0 ) {
				//Se pega contra un guardián
				$guardian = array_shift($guardianes);
				if ($guardian===null) 
					throw new CHttpException(400, 'Error al resolver un choque de un Guardián con un zombie, en el ataque del Gunbudo Nigromante '.$nigromante->id.'.');
				
				//El guardián tiene una acción menos pues.
				$guardian->actions -= 1;

				if (!$guardian->save())
					throw new CHttpException(400, 'Error al guardar el cambio de arma del gunbudo Guardián '.$guardian->id.' tras ataque zombie.');
			} else {
				//El zombie pasa y ataca con éxito
				$zombies_atacan++;
			}
			
			//El zombie original muere (era un cadáver)
			$zombies_muertos_ids[] = $zombie->id;
		}
Yii::log('Al final penetran '.$zombies_atacan.' zombies en el corral', 'info');
		//Me cargo de una sola consulta a los zombies originales
		Gungubo::model()->deleteAll('id IN ('.implode(',', $zombies_muertos_ids).')');
		
		//Resuelvo los ataques de los zombies
		$otros_muertos = 0;
		$zombies_atacan_aux = $zombies_atacan;
		$probabilidad = Yii::app()->config->getParam('gunguboZombieProbabilidadZombificar');
		while ($zombies_atacan_aux > 0) {
			$tirada = mt_rand(1,100);
Yii::log(' DATOS: '.$tirada.' // '.$probabilidad, 'info');
			if ($tirada <= $probabilidad) {
				//Convierto uno !!
Yii::log('  + Zombie convertido!', 'info');
				$otros_muertos++; //Muere uno más en el corral
				$zombies_atacan_aux++; //El que convierte no muere y se añade un zombie más
			} else {
Yii::log('  - Zombie mueto', 'info');
				//No convierto :S
				$zombies_atacan_aux--; //El que ataca muere
			}
		}
		
		//Mato a los muertos extra. Los remueve del juego directamente, no van al cementerio.
		$cuantos_muertos = Gungubo::model()->deleteAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND location=:lugar ORDER BY RAND() LIMIT '.$otros_muertos, 'params'=>array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':lugar'=>'corral')));
						
		if ($colericos>0) $txt_colericos = ' ('.$colericos.' de ellos Coléricos)';
		else $txt_colericos = '';
Yii::log('Los zombies mataron en total a '.$cuantos_muertos.' gungubos del corral', 'info');						
		//Notificaciones para el atacante		
		$notiA = new NotificationCorral;
		$notiA->event_id = $event_id;
		$notiA->user_id = $nigromante->owner_id;
		$notiA->message = ':'.Yii::app()->params->gunbudoClassNigromante.': Tu Gunbudo Nigromante creó '.count($zombies).' Gungubos Zombie'.$txt_colericos.' con los cadáveres de tu cementerio, que han matado '.$cuantos_muertos.' Gungubos en el corral de '.ucfirst($objetivo->username).'.';
		if (!$notiA->save())
			throw new CHttpException(400, 'Error al guardar la notificación A de corral de Ataque Zombie en evento '.$event_id.'.');
		
		//Notificaciones para el defensor
		$notiD = new NotificationCorral;
		$notiD->event_id = $event_id;
		$notiD->user_id = $objetivo->id;
		$notiD->message = ':'.Yii::app()->params->gunguboClassZombie.': Un grupo de Gungubos Zombie ha penetrado en tu corral matando a '.$cuantos_muertos.' Gungubos.';
		if (!$notiD->save())
			throw new CHttpException(400, 'Error al guardar la notificación D de corral de Ataque Zombie en evento '.$event_id.'.');
		
		return true;
	}
	
	
	public function gunbudoArtificieroAttack($gunbudo_id) 
	{
		//Cojo el gunbudo
        $artificiero = Gunbudo::model()->findByPk($gunbudo_id);
        if ($artificiero===null) return true; //Si ya no existe el gunbudo, no hago nada

		$event_id = $artificiero->event_id;
		
		//Ahora he de sacar el jugador propietario para ver su bando
		$owner = User::model()->findByPk($artificiero->owner_id);
		
		if ($owner->side=='kafhe') $bando_opuesto = 'achikhoria';
		elseif ($owner->side=='achikhoria') $bando_opuesto = 'kafhe';
		else $bando_opuesto = null;
Yii::log('Bando opuesto: '.$bando_opuesto, 'info');
		//Calculo la cantidad de bombas que van a atacar, si no hay ninguno termino el ataque
		$cadaveres = Gungubo::model()->findAll(array('condition'=>'owner_id=:owner AND event_id=:evento AND location=:lugar', 'params'=>array(':owner'=>$owner->id, ':evento'=>$event_id, ':lugar'=>'cementerio')));
Yii::log('Hay '.count($cadaveres).' cadaveres', 'info');
		//Cada cadáver tiene un % de convertirse en bomba
		$probabilidadBomba = Yii::app()->config->getParam('gunbudoArtificieroProbabilidadBomba');				
		$bombas = array();		
		
		foreach($cadaveres as $cadaver) {
			$tirada = mt_rand(1,100);
			if ($tirada <= $probabilidadBomba) {
				//Bomba
Yii::log('Bomba!!!!', 'info');			
				
				$bombas[] = $cadaver;
			}
			
			if (count($bombas) == Yii::app()->config->getParam('gunbudoArtificieroMaxBombas'))
				break; //si llego al máximo de bombas que puede convertir, termino de convertir
		}
Yii::log('Convierto estas bombas: '.count($bombas), 'info');
		if (count($bombas)==0) return true;	
		
		//Ahora a ver a quién ataco
		$objetivo = Yii::app()->usertools->randomUser($owner->group_id, $bando_opuesto);
Yii::log('Ataco a '.$objetivo->username.' con '.count($bombas).' bombas', 'info');
		//Saco las defensas del objetivo (gunbudos guardianes, mejoras del corral..)
		$guardianes = Gunbudo::model()->findAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND class=:clase AND actions>0', 'params'=>array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':clase'=>Yii::app()->params->gunbudoClassGuardian)));
Yii::log('Tiene '.count($guardianes).' guardianes', 'info');
		//por cada bomba, calculo si se pega con un guardián o no
		$bombas_atacan = 0;
		$bombas_muertos_ids = array();
		foreach ($bombas as $bomba) {
			if ( count($guardianes)>0 ) {
				//Se pega contra un guardián
				$guardian = array_shift($guardianes);
				if ($guardian===null) 
					throw new CHttpException(400, 'Error al resolver un choque de un Guardián con un bomba, en el ataque del Gunbudo Artificiero '.$artificiero->id.'.');
				
				//El guardián tiene una acción menos pues.
				$guardian->actions -= 1;

				if (!$guardian->save())
					throw new CHttpException(400, 'Error al guardar el cambio de arma del gunbudo Guardián '.$guardian->id.' tras ataque bomba.');
			} else {
				//El bomba pasa y ataca con éxito
				$bombas_atacan++;
			}
			
			//El bomba original muere (era un cadáver)
			$bombas_muertos_ids[] = $bomba->id;
		}
Yii::log('Al final penetran '.$bombas_atacan.' bombas en el corral', 'info');
		//Me cargo de una sola consulta a los bombas originales
		Gungubo::model()->deleteAll('id IN ('.implode(',', $bombas_muertos_ids).')');
		
		//Resuelvo los ataques de los bombas al corral (a ver si estallan)
		$otros_muertos = 0;
		$otros_quemados = 0;
		$bombas_atacan_aux = $bombas_atacan;
		$probabilidadEstallar = Yii::app()->config->getParam('gunguboBombaProbabilidadEstallar');
		$probabilidadIncendiar = Yii::app()->config->getParam('gunguboBombaProbabilidadIncendiar');
		$minMuertes = Yii::app()->config->getParam('gunguboBombaMinMuertes');
		$maxMuertes = Yii::app()->config->getParam('gunguboBombaMaxMuertes');
		$minIncendiar = Yii::app()->config->getParam('incendiarMinQuemados');
		$maxIncendiar = Yii::app()->config->getParam('incendiarMaxQuemados');
		
		while ($bombas_atacan_aux > 0) {
			$tirada = mt_rand(1,100);			
			
			if ($tirada <= $probabilidadEstallar) {
				//Estalla la bomba !!
Yii::log('  + ¡Bomba estalla!', 'info');
				$otros_muertos += mt_rand($minMuertes, $maxMuertes); //Mueren en el corral
				
				//Miro a ver si quema al estallar
				$tirada = mt_rand(1,100); //Tiro para ver si quemo o no
				if ($tirada <= $probabilidadIncendiar) {
					//Le prendo fuego a otros Gungubos
Yii::log('  + ¡FUEGO y quemadura!', 'info');		
					$otros_quemados += mt_rand($minIncendiar, $maxIncendiar);
				}
			} 
			
			$bombas_atacan_aux--; //El que ataca muere			
		}
		
		//Mato a los muertos extra. Los remueve del juego directamente, no van al cementerio.
		$cuantos_muertos = Gungubo::model()->deleteAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND location=:lugar ORDER BY RAND() LIMIT '.$otros_muertos, 'params'=>array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':lugar'=>'corral')));
		
		//Pongo quemados a los que he quemado, obvio
		$cuantos_quemados = Gungubo::model()->updateAll(array('condition'=>Yii::app()->params->conditionQuemadura), 'event_id=:evento AND owner_id=:owner AND location=:lugar ORDER BY RAND() LIMIT '.$otros_quemados.';', array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':lugar'=>'corral'));
				
		if ($cuantos_quemados>0) $txt_quemados = ' y quemado a otros '.$cuantos_quemados;
		else $txt_quemados = '';
Yii::log('Las bombas mataron en total a '.$cuantos_muertos.' gungubos del corral y quemaron a '.$cuantos_quemados, 'info');
		//Notificaciones para el atacante		
		$notiA = new NotificationCorral;
		$notiA->event_id = $event_id;
		$notiA->user_id = $artificiero->owner_id;
		$notiA->message = ':'.Yii::app()->params->gunbudoClassArtificiero.': Tu Gunbudo Artificiero creó '.count($bombas).' Gungubos Bomba con los cadáveres de tu cementerio, que han matado '.$cuantos_muertos.' Gungubos'.txt_quemados.' en el corral de '.ucfirst($objetivo->username).'.';
		if (!$notiA->save())
			throw new CHttpException(400, 'Error al guardar la notificación A de corral de Ataque Bomba en evento '.$event_id.'.');
		
		//Notificaciones para el defensor
		$notiD = new NotificationCorral;
		$notiD->event_id = $event_id;
		$notiD->user_id = $objetivo->id;
		$notiD->message = ':'.Yii::app()->params->gunguboClassBomba.': Un grupo de Gungubos Bomba ha penetrado en tu corral matando a '.$cuantos_muertos.' Gungubos'.txt_quemados.'.';
		if (!$notiD->save())
			throw new CHttpException(400, 'Error al guardar la notificación D de corral de Ataque Bomba en evento '.$event_id.'.');
		
		return true;
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

