<?php
///TODO comentar los Yii::log antes de poner en producción
/** GumbudosSingleton para operaciones relacionadas con los Gungubos de un evento
 */
class GumbudosSingleton extends CApplicationComponent
{
    public function gumbudoAsaltanteAttack($gumbudo_id)
	{
		//Cojo el gumbudo
		$asaltante = Gumbudo::model()->findByPk($gumbudo_id);
		if ($asaltante===null) return true; //Si ya no existe el gumbudo, no hago nada

		$event_id = $asaltante->event_id;
		
		//Ahora he de sacar el jugador propietario para ver su bando
		$owner = User::model()->findByPk($asaltante->owner_id);

        //Antes de nada miro a ver si hay un Hippie que me impida actuar
        $afectaHippie = $this->gumbudoAfectadoHippie($asaltante, $event_id, $owner);
        if ($afectaHippie) return true; //Si me ha afectado un Hippie salgo

		//Saco el objetivo
		$objetivo = $this->selectTarget($owner, $event_id, $asaltante);

		//Saco las defensas del objetivo (gumbudos guardianes, mejoras del corral..)
		$guardianes = Gumbudo::model()->findAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND class=:clase AND actions>0', 'params'=>array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':clase'=>Yii::app()->params->gumbudoClassGuardian)));
		shuffle($guardianes);

		$debug_fame_owner = 0;
        $debug_fame_objetivo = 0;
        $debug_guardian_id = '';
		
		$ataque_exitoso = true;
        $result = '';
        foreach($guardianes as $guardian) {
            $result = $this->resolveCombat($asaltante, $guardian);

            //Si gana el defensor o hay empate se termina todo. Devuelve 0 en empate, 1 en gana atacante y 2 en gana defensor
            if ($result===0) { //Empate
                $ataque_exitoso = false;
                $objetivo->fame += 2; //Uno de fama por detener un ataque con empate, para  el defensor
                $debug_fame_objetivo = 2;
                $debug_guardian_id = $guardian->id;
            } elseif ($result===2) { //Defensor wins
                $ataque_exitoso = false;

                //Cambio de armas del gumbudo asaltante y salvo.
                $asaltante->weapon = $guardian->weapon;
                if (!$asaltante->save())
                    throw new CHttpException(400, 'Error al guardar el cambio de arma del Asaltante '.$asaltante->id.'.');

                //Fama para el Guardián
                $objetivo->fame += 2;
                $debug_fame_objetivo = 4;
                $debug_guardian_id = $guardian->id;
            } elseif ($result===1) { //Asaltante wins
                //le cambio de arma al defensor
                $guardian->weapon = $asaltante->weapon;

                //Famas Guardián por derrota
                $objetivo->fame = max(0, $objetivo->fame-2);
                $debug_fame_objetivo = -2;
                $debug_guardian_id = $guardian->id;
            }

            //Quito una acción al defensor
            $guardian->actions -= 1;

            if (!$guardian->save())
                throw new CHttpException(400, 'Error al guardar el cambio de arma del gumbudo Guardián '.$guardian->id.'.');

            Yii::app()->utils->logCSV($debug_guardian_id.',Guardián,'.$debug_fame_objetivo.','.date('d-m-Y'));

            //Continuo mirando el combate con el siguiente guardián si lo hubiere, siempre que el ataque no haya fallado ya
            if ($ataque_exitoso===false) break;
        }

		//Si el atacante ha conseguido entrar y matar
		if ($ataque_exitoso) {
			$mata = mt_rand( intval(Yii::app()->config->getParam('gumbudoAsaltanteMinMuertes')), intval(Yii::app()->config->getParam('gumbudoAsaltanteMaxMuertes')) );
			
			//Si el gumbudo era sanguinario mata el doble
			if ($asaltante->trait == Yii::app()->params->traitSanguinario)
				$mata = $mata * $asaltante->trait_value; //Mata mucho más
				
			//Mato a los pobresitos gungubitos mandándolos al cementerio
			$cuantos = Gungubo::model()->updateAll(array('location'=>'cementerio', 'health'=>0, 'attacker_id'=>$asaltante->owner_id), 'event_id=:evento AND owner_id=:owner AND location=:lugar ORDER BY RAND() LIMIT '.$mata.';', array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':lugar'=>'corral'));
			
			//Textos de notificaciones
			$txtA = ':'.Yii::app()->params->gumbudoClassAsaltante.': Tu Gumbudo Asaltante ha matado '.$cuantos.' Gungubos en el corral de '.Yii::app()->usertools->getAlias($objetivo->id).'.';
			if ($result===1) $txtD = ':'.Yii::app()->params->gumbudoClassGuardian.': Un Gumbudo Asaltante ha superado a tus Guardianes matando a '.$cuantos.' Gungubos en tu corral.';
            else $txtD = ':'.Yii::app()->params->gunguboClassDefault.': Un Gumbudo Asaltante ha matado a '.$cuantos.' Gungubos en tu corral.';

			//Fama Asaltante exitoso
            $owner->fame += 2; // 2 de fama por atacar con éxito
            $debug_fame_owner += 2;
		} else {
			//Textos de notificaciones
			$txtA = ':'.Yii::app()->params->gumbudoClassAsaltante.': Los Gumbudos Guardianes del corral de '.Yii::app()->usertools->getAlias($objetivo->id).' han detenido el ataque de tu Gumbudo Asaltante.';
			$txtD = ':'.Yii::app()->params->gumbudoClassGuardian.': Tus Gumbudos Guardianes han detenido un ataque de un Asaltante en tu corral.';

            //Fama según resultado, para el Asaltante si pierde el combate con derrota
            if ($result===2) {
                $owner->fame = max(0, $owner->fame-1);
                $debug_fame_owner -= 1;
            }
		}

        //Guardo la fama de ambos usuarios, si no son el mismo por una trampa de Confusion o lo que sea
        if ($owner->id !== $objetivo->id) {
            if (!$owner->save())
                throw new CHttpException(400, 'Error al guardar la fama del usuario atacante por Ataque Asaltante en evento '.$event_id.'.');

            if (!$objetivo->save())
                throw new CHttpException(400, 'Error al guardar la fama del usuario defensor por Ataque Asaltante en evento '.$event_id.'.');

            //Yii::log('[[FAMA]] Ataque de Asaltante. (Asaltante '.$gumbudo_id.') '.$owner->alias.' '.$debug_fame_owner.'f ## (Guardian '.$debug_guardian_id.') '.$objetivo->alias.' '.$debug_fame_objetivo.'f {'.$gumbudo_id.',Asaltante,'.$debug_fame_owner.','.date('d-m-Y')."||".''.$debug_guardian_id.',Guardian,'.$debug_fame_objetivo.','.date('d-m-Y').'}', 'warning');
            Yii::app()->utils->logCSV($gumbudo_id.',Asaltante,'.$debug_fame_owner.','.date('d-m-Y'));
        }
		
		//Notificaciones para el atacante
		$notiA = new NotificationCorral;
		$notiA->event_id = $event_id;
		$notiA->user_id = $asaltante->owner_id;
		$notiA->message = $txtA;
        $notiA->timestamp = Yii::app()->utils->getCurrentDate();
		if (!$notiA->save())
			throw new CHttpException(400, 'Error al guardar la notificación A del Asaltante en evento '.$event_id.'.');
		
		//Notificaciones para el defensor
		$notiD = new NotificationCorral;
		$notiD->event_id = $event_id;
		$notiD->user_id = $objetivo->id;
		$notiD->message = $txtD;
        $notiD->timestamp = Yii::app()->utils->getCurrentDate();
		if (!$notiD->save())
			throw new CHttpException(400, 'Error al guardar la notificación D del Asaltante en evento '.$event_id.'.');
		
		return true;
    }
	
	public function gumbudoNigromanteAttack($gumbudo_id)
	{
        //Cojo el gumbudo
        $nigromante = Gumbudo::model()->findByPk($gumbudo_id);
        if ($nigromante===null) return true; //Si ya no existe el gumbudo, no hago nada

		$event_id = $nigromante->event_id;

		//Ahora he de sacar el jugador propietario para ver su bando
		$owner = User::model()->findByPk($nigromante->owner_id);

        //Antes de nada miro a ver si hay un Hippie que me impida actuar
        $afectaHippie = $this->gumbudoAfectadoHippie($nigromante, $event_id, $owner);
        if ($afectaHippie) return true; //Si me ha afectado un Hippie salgo

		//Calculo la cantidad de zombies que van a atacar, si no hay ninguno termino el ataque
		$cadaveres = Gungubo::model()->findAll(array('condition'=>'owner_id=:owner AND event_id=:evento AND location=:lugar', 'params'=>array(':owner'=>$owner->id, ':evento'=>$event_id, ':lugar'=>'cementerio')));
//Yii::log('Hay '.count($cadaveres).' cadaveres', 'info');
		//Cada cadáver tiene un % de convertirse en zombie
		$probabilidadZombie = Yii::app()->config->getParam('gumbudoNigromanteProbabilidadZombie');
		$probabilidadColera = Yii::app()->config->getParam('gumbudoNigromanteProbabilidadColera');
		$zombies = array();
		$colericos = 0;

		$debug_fame_owner = 0;
        $debug_fame_objetivo = 0;
        $debug_guardian_id = '';

		foreach($cadaveres as $cadaver) {
			$tirada = mt_rand(1,100);
			if ($tirada <= $probabilidadZombie) {
				//Zombie!!!!  ¿Será colérico?
//Yii::log('Zombie!!!!', 'info');
				$tirada = mt_rand(1,100);
				if ($tirada <= $probabilidadColera) {
					//Sí!
//Yii::log('Y es colerico!!!!', 'info');
					$cadaver->trait = Yii::app()->params->traitColera;
					$colericos++;
				}

				$zombies[] = $cadaver;
			}

			if (count($zombies) == Yii::app()->config->getParam('gumbudoNigromanteMaxZombies'))
				break; //si llego al máximo de zombies que puede convertir, termino de convertir
		}
//Yii::log('Convierto estos zombies: '.count($zombies), 'info');
		if (count($zombies)==0) return true;

		//Sumo la fama por zombies creados, independientemente del resultado
		$owner->fame += count($zombies);
		$debug_fame_owner += count($zombies);

        //Ahora a ver a quién ataco.
        $objetivo = $this->selectTarget($owner, $event_id, $nigromante);

//Yii::log('Ataco a '.$objetivo->username.' con '.count($zombies).' zombies', 'info');
		//Saco las defensas del objetivo (gumbudos guardianes, mejoras del corral..)
		$guardianes = Gumbudo::model()->findAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND class=:clase AND actions>0', 'params'=>array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':clase'=>Yii::app()->params->gumbudoClassGuardian)));
//Yii::log('Tiene '.count($guardianes).' guardianes', 'info');
		//por cada zombie, calculo si se pega con un guardián o no
		$zombies_atacan = 0;
		$zombies_muertos_ids = array();
		$defensorGanaFama = false;
		foreach ($zombies as $zombie) {
			if ( ($zombie->trait!=Yii::app()->params->traitColera) && count($guardianes)>0 ) {
				//Se pega contra un guardián
				$guardian = array_shift($guardianes);
				if ($guardian===null)
					throw new CHttpException(400, 'Error al resolver un choque de un Guardián con un zombie, en el ataque del Gumbudo Nigromante '.$nigromante->id.'.');

				//El guardián tiene una acción menos pues.
				$guardian->actions -= 1;

				//Fama para el defensor
				$objetivo->fame += 4;
                $debug_fame_objetivo = 4;

				$defensorGanaFama = true;

				$debug_guardian_id = $guardian->id;

				if (!$guardian->save())
					throw new CHttpException(400, 'Error al guardar el gumbudo Guardián '.$guardian->id.' tras ataque zombie.');

                Yii::app()->utils->logCSV($debug_guardian_id.',Guardián,'.$debug_fame_objetivo.','.date('d-m-Y'));
			} else {
				//El zombie pasa y ataca con éxito
				$zombies_atacan++;
			}

			//El zombie original muere (era un cadáver)
			$zombies_muertos_ids[] = $zombie->id;
		}
//Yii::log('Al final penetran '.$zombies_atacan.' zombies en el corral', 'info');
		//Me cargo de una sola consulta a los zombies originales
		Gungubo::model()->deleteAll('id IN ('.implode(',', $zombies_muertos_ids).')');

		//Resuelvo los ataques de los zombies
		$otros_muertos = $cuantos_muertos = 0;
		$zombies_atacan_aux = $zombies_atacan;
		$probabilidad = Yii::app()->config->getParam('gunguboZombieProbabilidadZombificar');
		while ($zombies_atacan_aux > 0) {
			$tirada = mt_rand(1,100);
//Yii::log(' DATOS: '.$tirada.' // '.$probabilidad, 'info');
			if ($tirada <= $probabilidad) {
				//Convierto uno !!
//Yii::log('  + Zombie convertido!', 'info');
				$otros_muertos++; //Muere uno más en el corral
				$zombies_atacan_aux++; //El que convierte no muere y se añade un zombie más
			} else {
//Yii::log('  - Zombie mueto', 'info');
				//No convierto :S
				$zombies_atacan_aux--; //El que ataca muere
			}
		}

		//Mato a los muertos extra. Los remueve del juego directamente, no van al cementerio.
		$cuantos_muertos = Gungubo::model()->deleteAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND location=:lugar ORDER BY RAND() LIMIT '.$otros_muertos, 'params'=>array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':lugar'=>'corral')));

		//Doy fama por los convertidos en el corral atacado, si no me he atacado a mí mismo
		if ($owner->id!==$objetivo->id) {
		    $owner->fame += max ($otros_muertos, 3);
            $debug_fame_owner += max ($otros_muertos, 3);

		    //Guardo al defensor por haber ganado fama, si lo hizo
            if ($defensorGanaFama && !$objetivo->save())
                throw new CHttpException(400, 'Error al guardar la fama del usuario defensor por Ataque Nigromante en evento '.$event_id.'.');
        }

		//Guardo al usuario que atacó
        if (!$owner->save())
            throw new CHttpException(400, 'Error al guardar la fama del usuario por Ataque Zombie en evento '.$event_id.'.');

        //Yii::log('[[FAMA]] Ataque de Nigromante. (Nigromante '.$gumbudo_id.') '.$owner->alias.' '.$debug_fame_owner.'f ## (Guardian '.$debug_guardian_id.') '.$objetivo->alias.' '.$debug_fame_objetivo.'f {'.$gumbudo_id.',Asaltante,'.$debug_fame_owner.','.date('d-m-Y')."||".''.$debug_guardian_id.',Guardian,'.$debug_fame_objetivo.','.date('d-m-Y').'}', 'warning');
        Yii::app()->utils->logCSV($gumbudo_id.',Nigromante,'.$debug_fame_owner.','.date('d-m-Y'));

		if ($colericos>0) $txt_colericos = ' ('.$colericos.' de ellos Coléricos)';
		else $txt_colericos = '';
//Yii::log('Los zombies mataron en total a '.$cuantos_muertos.' Gungubos del corral', 'info');
		//Notificaciones para el atacante
		$notiA = new NotificationCorral;
		$notiA->event_id = $event_id;
		$notiA->user_id = $nigromante->owner_id;
		$notiA->message = ':'.Yii::app()->params->gumbudoClassNigromante.': Tu Gumbudo Nigromante creó '.count($zombies).' Gungubos Zombie'.$txt_colericos.' con los cadáveres de tu cementerio, que han matado '.$cuantos_muertos.' Gungubos en el corral de '.ucfirst($objetivo->alias).'.';
        $notiA->timestamp = Yii::app()->utils->getCurrentDate();
		if (!$notiA->save())
			throw new CHttpException(400, 'Error al guardar la notificación A de corral de Ataque Zombie en evento '.$event_id.'.');

		//Notificaciones para el defensor
		$notiD = new NotificationCorral;
		$notiD->event_id = $event_id;
		$notiD->user_id = $objetivo->id;
		$notiD->message = ':'.Yii::app()->params->gunguboClassZombie.': Un grupo de Gungubos Zombie ha penetrado en tu corral matando a '.$cuantos_muertos.' Gungubos.';
        $notiD->timestamp = Yii::app()->utils->getCurrentDate();
		if (!$notiD->save())
			throw new CHttpException(400, 'Error al guardar la notificación D de corral de Ataque Zombie en evento '.$event_id.'.');

		return true;
	}


    public function gumbudoPestilenteAttack($gumbudo_id)
    {
        //Cojo el gumbudo
        $pestilente = Gumbudo::model()->findByPk($gumbudo_id);
        if ($pestilente===null) return true; //Si ya no existe el gumbudo, no hago nada

        $event_id = $pestilente->event_id;

        //Ahora he de sacar el jugador propietario para ver su bando
        $owner = User::model()->findByPk($pestilente->owner_id);

        //Antes de nada miro a ver si hay un Hippie que me impida actuar
        $afectaHippie = $this->gumbudoAfectadoHippie($pestilente, $event_id, $owner);
        if ($afectaHippie) return true; //Si me ha afectado un Hippie salgo

        //Saco el objetivo
        $objetivo = $this->selectTarget($owner, $event_id, $pestilente);

        //Saco las defensas del objetivo (gumbudos guardianes, mejoras del corral..)
        $guardianes = Gumbudo::model()->findAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND class=:clase AND actions>0', 'params'=>array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':clase'=>Yii::app()->params->gumbudoClassGuardian)));

        $debug_fame_owner = 0;
        $debug_fame_objetivo = 0;
        $debug_guardian_id = '';

        $ataque_exitoso = true;
        $result = '';

        //Si hay guardianes...
        if (count($guardianes)>0) {
        	//Se pega contra un guardián
			$guardian = array_shift($guardianes);
			if ($guardian===null)
				throw new CHttpException(400, 'Error al resolver un choque de un Guardián con un Gumbudo Pestilente '.$pestilente->id.'.');

			//El guardián tiene una acción menos pues.
			$guardian->actions -= 1;

			//Fama para el defensor
			$objetivo->fame += 4;
            $debug_fame_objetivo = 4;
            $debug_guardian_id = $guardian->id;

			if (!$guardian->save())
				throw new CHttpException(400, 'Error al guardar el gumbudo Guardián '.$guardian->id.' tras ataque pestilente.');

            Yii::app()->utils->logCSV($debug_guardian_id.',Guardián,'.$debug_fame_objetivo.','.date('d-m-Y'));

            //Textos de notificaciones
            $txtA = ':'.Yii::app()->params->gumbudoClassPestilente.': Los Gumbudos Guardianes del corral de '.Yii::app()->usertools->getAlias($objetivo->id).' han detenido el ataque de tu Gumbudo Pestilente.';
            $txtD = ':'.Yii::app()->params->gumbudoClassGuardian.': Tus Gumbudos Guardianes han detenido un ataque de un Pestilente en tu corral.';
        } else {
            //Si el pestilente ha conseguido entrar miro a ver si infecto el corral
            if ($pestilente->trait != Yii::app()->params->traitFetido) {
                $tirada = mt_rand(1,100);
                $valor = intval(Yii::app()->config->getParam('gumbudoPestilenteProbabilidadInfectar'));
                if ($tirada<=$valor)
                    $infecto = true;
                else
                    $infecto = false;
            } else
                $infecto = true;

            $cuantos_infecto = 0;
            if ($infecto) {
                $cuantos_infecto = Gungubo::model()->updateAll(array('condition_status'=>Yii::app()->params->conditionEnfermedad, 'condition_value'=>Yii::app()->config->getParam('gumbudoPestilenteIntensidadEnfermedad'), 'attacker_id'=>$pestilente->owner_id), 'event_id=:evento AND owner_id=:owner AND location=:lugar', array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':lugar'=>'corral'));

                //Fama Pestilente para ataque exitoso
                $owner->fame += 7; // 5 de fama por infectar con éxito
                $debug_fame_owner += 7;
            }

            //Textos de notificaciones
            $txtA = ':'.Yii::app()->params->gumbudoClassPestilente.': Tu Gumbudo Pestilente ha irrumpido en el corral de '.Yii::app()->usertools->getAlias($objetivo->id).' propagando una enfermedad a sus '.$cuantos_infecto.' Gungubos.';
            if ($result===1) $txtD = ':'.Yii::app()->params->gumbudoClassGuardian.': Un Gumbudo Pestilente ha superado a tus Guardianes y ha propagado una enfermedad en tu corral infectando a '.$cuantos_infecto.' Gungubos.';
            else $txtD = ':'.Yii::app()->params->gunguboClassDefault.': Un Gumbudo Pestilente ha propagado una enfermedad en tu corral infectando a '.$cuantos_infecto.' Gungubos.';
        }

        //Guardo la fama de ambos usuarios, si no son el mismo por una trampa de Confusion o lo que sea
        if ($owner->id !== $objetivo->id) {
            if (!$owner->save())
                throw new CHttpException(400, 'Error al guardar la fama del usuario atacante por Ataque Pestilente en evento '.$event_id.'.');

            if (!$objetivo->save())
                throw new CHttpException(400, 'Error al guardar la fama del usuario defensor por Ataque Pestilente en evento '.$event_id.'.');
        }

        //Yii::log('[[FAMA]] Ataque de Pestilente. (Pestilente '.$gumbudo_id.') '.$owner->alias.' '.$debug_fame_owner.'f ## (Guardian '.$debug_guardian_id.') '.$objetivo->alias.' '.$debug_fame_objetivo.'f {'.$gumbudo_id.',Asaltante,'.$debug_fame_owner.','.date('d-m-Y')."||".''.$debug_guardian_id.',Guardian,'.$debug_fame_objetivo.','.date('d-m-Y').'}', 'warning');
        Yii::app()->utils->logCSV($gumbudo_id.',Pestilente,'.$debug_fame_owner.','.date('d-m-Y'));

        //Notificaciones para el atacante
        $notiA = new NotificationCorral;
        $notiA->event_id = $event_id;
        $notiA->user_id = $pestilente->owner_id;
        $notiA->message = $txtA;
        $notiA->timestamp = Yii::app()->utils->getCurrentDate();
        if (!$notiA->save())
            throw new CHttpException(400, 'Error al guardar la notificación A del Pestilente en evento '.$event_id.'.');

        //Notificaciones para el defensor
        $notiD = new NotificationCorral;
        $notiD->event_id = $event_id;
        $notiD->user_id = $objetivo->id;
        $notiD->message = $txtD;
        $notiD->timestamp = Yii::app()->utils->getCurrentDate();
        if (!$notiD->save())
            throw new CHttpException(400, 'Error al guardar la notificación D del Pestilente en evento '.$event_id.'.');

        return true;
    }

	public function gumbudoArtificieroAttack($gumbudo_id)
	{
		//Cojo el gumbudo
        $artificiero = Gumbudo::model()->findByPk($gumbudo_id);
        if ($artificiero===null) return true; //Si ya no existe el gumbudo, no hago nada

		$event_id = $artificiero->event_id;

		//Ahora he de sacar el jugador propietario para ver su bando
		$owner = User::model()->findByPk($artificiero->owner_id);

        //Antes de nada miro a ver si hay un Hippie que me impida actuar
        $afectaHippie = $this->gumbudoAfectadoHippie($artificiero, $event_id, $owner);
        if ($afectaHippie) return true; //Si me ha afectado un Hippie salgo

		//Calculo la cantidad de bombas que van a atacar, si no hay ninguno termino el ataque
		$cadaveres = Gungubo::model()->findAll(array('condition'=>'owner_id=:owner AND event_id=:evento AND location=:lugar', 'params'=>array(':owner'=>$owner->id, ':evento'=>$event_id, ':lugar'=>'cementerio')));
//Yii::log('Hay '.count($cadaveres).' cadaveres', 'info');
		//Cada cadáver tiene un % de convertirse en bomba
		$probabilidadBomba = Yii::app()->config->getParam('gumbudoArtificieroProbabilidadBomba');
		$bombas = array();

		$debug_fame_owner = 0;
		$debug_fame_objetivo = 0;
		$debug_guardian_id = '';

		foreach($cadaveres as $cadaver) {
			$tirada = mt_rand(1,100);
			if ($tirada <= $probabilidadBomba) {
				//Bomba
//Yii::log('Bomba!!!!', 'info');

				$bombas[] = $cadaver;
			}

			if (count($bombas) == Yii::app()->config->getParam('gumbudoArtificieroMaxBombas'))
				break; //si llego al máximo de bombas que puede convertir, termino de convertir
		}
//Yii::log('Convierto estas bombas: '.count($bombas), 'info');
		if (count($bombas)==0) return true;

        //Ahora a ver a quién ataco.
        $objetivo = $this->selectTarget($owner, $event_id, $artificiero);

//Yii::log('Ataco a '.$objetivo->username.' con '.count($bombas).' bombas', 'info');
		//Saco las defensas del objetivo (gumbudos guardianes, mejoras del corral..)
		$guardianes = Gumbudo::model()->findAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND class=:clase AND actions>0', 'params'=>array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':clase'=>Yii::app()->params->gumbudoClassGuardian)));
//Yii::log('Tiene '.count($guardianes).' guardianes', 'info');
		//por cada bomba, calculo si se pega con un guardián o no
		$bombas_atacan = 0;
		$bombas_muertos_ids = array();
		$defensorGanaFama=false;
		foreach ($bombas as $bomba) {
			if ( count($guardianes)>0 ) {
				//Se pega contra un guardián
				$guardian = array_shift($guardianes);
				if ($guardian===null)
					throw new CHttpException(400, 'Error al resolver un choque de un Guardián con un bomba, en el ataque del Gumbudo Artificiero '.$artificiero->id.'.');

				//El guardián tiene una acción menos pues.
				$guardian->actions -= 1;

                //Fama para el defensor
                $objetivo->fame += 4;
                $debug_fame_objetivo = 4;
                $debug_guardian_id = $guardian->id;

                $defensorGanaFama = true;

				if (!$guardian->save())
					throw new CHttpException(400, 'Error al guardar el cambio de arma del gumbudo Guardián '.$guardian->id.' tras ataque bomba.');

                Yii::app()->utils->logCSV($debug_guardian_id.',Guardián,'.$debug_fame_objetivo.','.date('d-m-Y'));
			} else {
				//El bomba pasa y ataca con éxito
				$bombas_atacan++;
			}

			//El bomba original muere (era un cadáver)
			$bombas_muertos_ids[] = $bomba->id;
		}
//Yii::log('Al final penetran '.$bombas_atacan.' bombas en el corral', 'info');
		//Me cargo de una sola consulta a los bombas originales
		Gungubo::model()->deleteAll('id IN ('.implode(',', $bombas_muertos_ids).')');

		//Resuelvo los ataques de los bombas al corral (a ver si estallan)
		$otros_muertos = $cuantos_muertos = 0;
		$otros_quemados = $cuantos_quemados = 0;
		$bombas_atacan_aux = $bombas_atacan;
		$probabilidadEstallar = Yii::app()->config->getParam('gunguboBombaProbabilidadEstallar');
		$probabilidadIncendiar = Yii::app()->config->getParam('gunguboBombaProbabilidadIncendiar');
		$minMuertes = Yii::app()->config->getParam('gunguboBombaMinMuertes');
		$maxMuertes = Yii::app()->config->getParam('gunguboBombaMaxMuertes');
		$minIncendiar = Yii::app()->config->getParam('incendiarMinQuemados');
		$maxIncendiar = Yii::app()->config->getParam('incendiarMaxQuemados');

		while ($bombas_atacan_aux > 0) {
			$tirada = mt_rand(1,100);
//Yii::log(' DATOS: '.$tirada.' // '.$probabilidadEstallar, 'info');
			if ($tirada <= $probabilidadEstallar) {
				//Estalla la bomba !!
//Yii::log('  + ¡Bomba estalla!', 'info');
				$otros_muertos += mt_rand($minMuertes, $maxMuertes); //Mueren en el corral

				//Miro a ver si quema al estallar
				$tirada = mt_rand(1,100); //Tiro para ver si quemo o no
				if ($tirada <= $probabilidadIncendiar) {
					//Le prendo fuego a otros Gungubos
//Yii::log('  + ¡FUEGO y quemadura!', 'info');
					$otros_quemados += mt_rand($minIncendiar, $maxIncendiar);
				}
			}

			$bombas_atacan_aux--; //El que ataca muere
		}

		//Fama por cada muerto directo si no me ataco a mí mismo
		if ($owner->id!==$objetivo->id){
		    $owner->fame += $otros_muertos;
		    $debug_fame_owner += $otros_muertos;

            //Guardo al defensor por haber ganado fama, si lo hizo
            if ($defensorGanaFama && !$objetivo->save())
                throw new CHttpException(400, 'Error al guardar la fama del usuario defensor por Ataque Artificiero en evento '.$event_id.'.');

            //Guardo al usuario que atacó
            if (!$owner->save())
                throw new CHttpException(400, 'Error al guardar la fama del usuario por Ataque de Artificiero en evento '.$event_id.'.');
        }

		//Mato a los muertos extra. Van al cementerio.
		$cuantos_muertos = Gungubo::model()->updateAll(array('location'=>'cementerio', 'health'=>0, 'attacker_id'=>$artificiero->owner_id), 'event_id=:evento AND owner_id=:owner AND location=:lugar ORDER BY RAND() LIMIT '.$otros_muertos, array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':lugar'=>'corral'));

		//Pongo quemados a los que he quemado, obvio
		$cuantos_quemados = Gungubo::model()->updateAll(array('condition_status'=>Yii::app()->params->conditionQuemadura, 'attacker_id'=>$artificiero->owner_id), 'event_id=:evento AND owner_id=:owner AND location=:lugar ORDER BY RAND() LIMIT '.$otros_quemados.';', array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':lugar'=>'corral'));

        //Yii::log('[[FAMA]] Ataque de Artificiero. (Artificiero '.$gumbudo_id.') '.$owner->alias.' '.$debug_fame_owner.'f ## (Guardian '.$debug_guardian_id.') '.$objetivo->alias.' '.$debug_fame_objetivo.'f {'.$gumbudo_id.',Asaltante,'.$debug_fame_owner.','.date('d-m-Y')."||".''.$debug_guardian_id.',Guardian,'.$debug_fame_objetivo.','.date('d-m-Y').'}', 'warning');
        Yii::app()->utils->logCSV($gumbudo_id.',Artificiero,'.$debug_fame_owner.','.date('d-m-Y'));

		if ($cuantos_quemados>0) $txt_quemados = ' y quemado a otros '.$cuantos_quemados;
		else $txt_quemados = '';
//Yii::log('Las bombas mataron en total a '.$cuantos_muertos.' Gungubos del corral y quemaron a '.$cuantos_quemados, 'info');
		//Notificaciones para el atacante
		$notiA = new NotificationCorral;
		$notiA->event_id = $event_id;
		$notiA->user_id = $artificiero->owner_id;
		$notiA->message = ':'.Yii::app()->params->gumbudoClassArtificiero.': Tu Gumbudo Artificiero creó '.count($bombas).' Gungubos Bomba con los cadáveres de tu cementerio, que han matado '.$cuantos_muertos.' Gungubos'.$txt_quemados.' en el corral de '.ucfirst($objetivo->alias).'.';
        $notiA->timestamp = Yii::app()->utils->getCurrentDate();
		if (!$notiA->save())
			throw new CHttpException(400, 'Error al guardar la notificación A de corral de Ataque Bomba en evento '.$event_id.'.');

		//Notificaciones para el defensor
		$notiD = new NotificationCorral;
		$notiD->event_id = $event_id;
		$notiD->user_id = $objetivo->id;
		if (count($guardianes)>0) $notiD->message = ':'.Yii::app()->params->gunguboClassBomba.': Un grupo de Gungubos Bomba ha superado a tus Gumbudos Guardianes y ha penetrado en tu corral matando a '.$cuantos_muertos.' Gungubos'.$txt_quemados.'.';
		else $notiD->message = ':'.Yii::app()->params->gunguboClassBomba.': Un grupo de Gungubos Bomba ha penetrado en tu corral y ha matado a '.$cuantos_muertos.' Gungubos'.$txt_quemados.'.';
        $notiD->timestamp = Yii::app()->utils->getCurrentDate();
		if (!$notiD->save())
			throw new CHttpException(400, 'Error al guardar la notificación D de corral de Ataque Bomba en evento '.$event_id.'.');

		return true;
	}


    public function gumbudoAsedioAttack($gumbudo_id)
    {
        //Cojo el gumbudo
        $gumbudo = Gumbudo::model()->findByPk($gumbudo_id);
        if ($gumbudo===null) return true; //Si ya no existe el gumbudo, no hago nada

        $event_id = $gumbudo->event_id;

        //Ahora he de sacar el jugador propietario para ver su bando
        $owner = User::model()->findByPk($gumbudo->owner_id);

        //Antes de nada miro a ver si hay un Hippie que me impida actuar
        $afectaHippie = $this->gumbudoAfectadoHippie($gumbudo, $event_id, $owner);
        if ($afectaHippie) return true; //Si me ha afectado un Hippie salgo

        $debug_fame_owner = 0;
        $debug_guardian_id = '';

        //Si no tengo 2 gungubos en el corral mal vamos...
        $gungubitos = Gungubo::model()->findAll(array('condition'=>'owner_id=:owner AND event_id=:evento AND location=:lugar ORDER BY health LIMIT 2', 'params'=>array(':owner'=>$owner->id, ':evento'=>$event_id, ':lugar'=>'corral')));
        if (count($gungubitos)!==2) return true; //Me salgo que no puedo atacar!!!
        else {
            //Mato a los gungubos
            foreach($gungubitos as $gungubo) {
                $id[] = $gungubo->id;
            }

            Gungubo::model()->deleteAll('id=:id1 OR id=:id2', array(':id1'=>$id[0], ':id2'=>$id[1]));
        }

        //Me quito fama por matar pobres gungubos
        $owner->fame = max(0, $owner->fame-2); //Un punto por gungubo achechinado
        $debug_fame_owner -= 2;

        //Ahora a ver a quién ataco.
        $objetivo = $this->selectTarget($owner, $event_id, $gumbudo);

        //Yii::log('Ataco a '.$objetivo->username, 'info');

        //Miro a ver si incendia y a cuántos quemo en el corral atacado
        $cuantos_quemados = 0;
        $probabilidadIncendiar = Yii::app()->config->getParam('gunguboMolotovProbabilidadIncendiar');
        $minIncendiar = Yii::app()->config->getParam('incendiarMinQuemados');
        $maxIncendiar = Yii::app()->config->getParam('incendiarMaxQuemados');
        $tirada = mt_rand(1,100);
        if ($tirada <= $probabilidadIncendiar) {
            //Fama por ataque exitoso
            $owner->fame += 4;
            $debug_fame_owner += 4;

            //Incendio!!!
            $cuantos = mt_rand($minIncendiar, $maxIncendiar);

            //Los quemo
            $cuantos_quemados = Gungubo::model()->updateAll(array('condition_status'=>Yii::app()->params->conditionQuemadura, 'attacker_id'=>$gumbudo->owner_id), 'event_id=:evento AND owner_id=:owner AND location=:lugar ORDER BY RAND() LIMIT '.$cuantos.';', array(':evento'=>$event_id, ':owner'=>$objetivo->id, ':lugar'=>'corral'));

            //Fama por quemar, :P
            $owner->fame += $cuantos_quemados;
            $debug_fame_owner += $cuantos_quemados;
        }

        //Guardo al usuario que atacó
        if (!$owner->save())
            throw new CHttpException(400, 'Error al guardar la fama del usuario por Ataque de Artificiero en evento '.$event_id.'.');

        //Yii::log('[[FAMA]] Ataque de Asedio. (Asedio '.$gumbudo_id.') '.$owner->alias.' '.$debug_fame_owner.'f {'.$gumbudo_id.',Asaltante,'.$debug_fame_owner.','.date('d-m-Y').'}', 'warning');
        Yii::app()->utils->logCSV($gumbudo_id.',Asedio,'.$debug_fame_owner.','.date('d-m-Y'));

        //Yii::log('Las molotov quemaron a '.$cuantos_quemados, 'info');
        //Notificaciones para el atacante
        $notiA = new NotificationCorral;
        $notiA->event_id = $event_id;
        $notiA->user_id = $gumbudo->owner_id;
        $notiA->message = ':'.Yii::app()->params->gumbudoClassAsedio.': Tu Gumbudo de Asedio lanzó dos Gungubos Molotov, quemando a '.$cuantos_quemados.' Gungubos en el corral de '.ucfirst($objetivo->alias).'.';
        $notiA->timestamp = Yii::app()->utils->getCurrentDate();
        if (!$notiA->save())
            throw new CHttpException(400, 'Error al guardar la notificación A de corral de Ataque Molotov en evento '.$event_id.'.');

        //Notificaciones para el defensor
        $notiD = new NotificationCorral;
        $notiD->event_id = $event_id;
        $notiD->user_id = $objetivo->id;
        $notiD->message = ':'.Yii::app()->params->gunguboClassMolotov.': Han lanzado unos Gungubos Molotov a tu corral y han quemado '.$cuantos_quemados.' Gungubos de tu corral.';
        $notiD->timestamp = Yii::app()->utils->getCurrentDate();
        if (!$notiD->save())
            throw new CHttpException(400, 'Error al guardar la notificación D de corral de Ataque Molotov en evento '.$event_id.'.');

        return true;
    }








	
	/***********************************************************************************************/
	/************* FUNCIONES AUXILIARES *****************/
	
	//Devuelve 0 en empate, 1 en gana atacante y 2 en gana defensor
	private function resolveCombat($atacante, $defensor) 
	{
		if ( ($atacante->weapon==Yii::app()->params->gumbudoWeapon1 && $defensor->weapon==Yii::app()->params->gumbudoWeapon2) ||
			($atacante->weapon==Yii::app()->params->gumbudoWeapon2 && $defensor->weapon==Yii::app()->params->gumbudoWeapon3) ||
			($atacante->weapon==Yii::app()->params->gumbudoWeapon3 && $defensor->weapon==Yii::app()->params->gumbudoWeapon1) )
			return 1;
			
		if ( ($defensor->weapon==Yii::app()->params->gumbudoWeapon1 && $atacante->weapon==Yii::app()->params->gumbudoWeapon2) ||
			($defensor->weapon==Yii::app()->params->gumbudoWeapon2 && $atacante->weapon==Yii::app()->params->gumbudoWeapon3) ||
			($defensor->weapon==Yii::app()->params->gumbudoWeapon3 && $atacante->weapon==Yii::app()->params->gumbudoWeapon1) )
			return 2;
				
		return 0;
	}

	//Calcula un objetivo aleatorio
	private function selectTarget($attacker, $event_id, $gumbudo)
    {
        $objetivo = null;

        //Primero miro a ver si hay un señuelo
        $senuelo = Modifier::model()->find(array('condition'=>'keyword=:keyword AND event_id=:evento', 'params'=>array(':keyword'=>Yii::app()->params->modifierSenuelo, ':evento'=>$event_id)));
        if ($senuelo!==null) {
            $objetivo = User::model()->findByPk($senuelo->target_final);

            if (!Yii::app()->modifier->reduceModifierUses($senuelo))
                throw new CHttpException(400, 'Error al eliminar el modificador '.$senuelo->keyword);

            //Notificaciones de que caigo en trampa
            $noti = new NotificationCorral;
            $noti->event_id = $event_id;
            $noti->user_id = $attacker->id;
            $noti->message = ':'.$gumbudo->class.': Tu Gumbudo '.Yii::app()->params->gumbudoClassNames[$gumbudo->class].' se ha sentido atraído por un señuelo que había en tu corral.';
            $noti->timestamp = Yii::app()->utils->getCurrentDate();
            if (!$noti->save())
                throw new CHttpException(400, 'Error al guardar la notificación de que un Gumbudo ha caído en señuelo.');

            return $objetivo;
        }

        //Calculo el bando opuesto al atacante
        if ($attacker->side=='kafhe') $bando_opuesto = 'achikhoria';
        elseif ($attacker->side=='achikhoria') $bando_opuesto = 'kafhe';
        else $bando_opuesto = null;

        //Miro a ver si el gumbudo cae en alguna otra trampa
        $trampa = $this->gumbudoCaeTrampa($gumbudo, $event_id);

        //Si caigo...
        if ($trampa!==false) {
            switch($trampa->keyword) {
                case Yii::app()->params->modifierTrampaConfusion:
                    $objetivo = $attacker; //Se vuelve contra el atacante
                    break;
            }

            //Notificaciones de que caigo en trampa
            $noti = new NotificationCorral;
            $noti->event_id = $event_id;
            $noti->user_id = $attacker->id;
            $noti->message = ':'.$gumbudo->class.': Tu Gumbudo '.Yii::app()->params->gumbudoClassNames[$gumbudo->class].' ha caído en una '.Yii::app()->params->trampaNames[$trampa->keyword].'.';
            $noti->timestamp = Yii::app()->utils->getCurrentDate();
            if (!$noti->save())
                throw new CHttpException(400, 'Error al guardar la notificación de que un Gumbudo ha caído en trampa.');
        }

        if ($objetivo===null)
            $objetivo = Yii::app()->usertools->randomUser($attacker->group_id, $bando_opuesto, array($attacker->id) );
//$objetivo = User::model()->findByPk(5); ///TODO quitar
        return $objetivo;
    }

    private function gumbudoCaeTrampa($gumbudo, $event_id) {
        //Busco los modificadores de trampa que pueden afectar a gumbudos
        $trampas = Modifier::model()->findAll(array('condition'=>'event_id=:evento AND (keyword=:key1) ORDER BY RAND()', 'params'=>array(':evento'=>$event_id, ':key1'=>Yii::app()->params->modifierTrampaConfusion)));

        foreach ($trampas as $trampa) {
            //Si es trampa de confusion y el gumbudo no es artificiero, nigromante o asaltante, no puedo caer
            if ($trampa->keyword==Yii::app()->params->modifierTrampaConfusion && $gumbudo->class!=Yii::app()->params->gumbudoClassAsaltante && $gumbudo->class!=Yii::app()->params->gumbudoClassNigromante && $gumbudo->class!=Yii::app()->params->gumbudoClassArtificiero) {
                continue;
            }

            //Miro a ver si caigo en la trampa
            $tirada = mt_rand(1,100);
            $valor = Yii::app()->config->getParam($trampa->keyword.'Probabilidad');

            if ($tirada<=$valor) {
                //He caído cual primo. Reduzco los usos del modificador trampa
                if (!Yii::app()->modifier->reduceModifierUses($trampa))
                    throw new CHttpException(400, 'Error al eliminar el modificador '.$trampa->keyword);

                return $trampa;
            }
        }

        return false;
    }

    private function gumbudoAfectadoHippie($gumbudo, $event_id, $owner) {
        //Por cada Hippie miro a ver si me afecta
        $hippies = Gumbudo::model()->findAll(array('condition'=>'event_id=:evento AND class=:clase AND actions>0', 'params'=>array(':evento'=>$event_id, ':clase'=>Yii::app()->params->gumbudoClassHippie)));

        foreach ($hippies as $hippie) {
            $tirada = mt_rand(1,100);
            $valor = Yii::app()->config->getParam('gumbudoHippieProbabilidadActuar');
            if ($tirada <= $valor) {
                //Le afecta. Resto accion al hippie
                $hippie->actions-=1; //resto una acción
                if (!$hippie->save())
                    throw new CHttpException(400, 'Error al guardar el Gumbudo hippie al restarle una acción '.$hippie->id.'.');

                //Notificación para dueño hippie
                $notiA = new NotificationCorral;
                $notiA->event_id = $event_id;
                $notiA->user_id = $hippie->owner_id;
                $notiA->message = ':'.Yii::app()->params->gumbudoClassHippie.': Tu Gumbudo Hippie ha pacificado a un Gumbudo '.Yii::app()->params->gumbudoClassNames[$gumbudo->class].', de '.ucfirst($owner->alias).',  impidiendo que actuara.';
                $notiA->timestamp = Yii::app()->utils->getCurrentDate();
                if (!$notiA->save())
                    throw new CHttpException(400, 'Error al guardar la notificación A de corral de actuación de Gumbudo Hippie en evento '.$event_id.'.');

                //Notificaciones para el dueño del atacante
                $notiD = new NotificationCorral;
                $notiD->event_id = $event_id;
                $notiD->user_id = $gumbudo->owner_id;
                $notiD->message = ':'.$gumbudo->class.': Un Gumbudo '.Yii::app()->params->gumbudoClassNames[$hippie->class].' ha convencido a tu Gumbudo '.Yii::app()->params->gumbudoClassNames[$gumbudo->class].' de que no debe portarse mal, y por ello no ataca esta vez.';
                $notiD->timestamp = Yii::app()->utils->getCurrentDate();
                if (!$notiD->save())
                    throw new CHttpException(400, 'Error al guardar la notificación D de corral de actuación de Gumbudo Hippie en evento '.$event_id.'.');

                return true;
            }
        }

        return false;
    }
	
}

function doDataError() {
  $varBoolean = null;
 for ($position=0; $position<=5; $position++) {
  $position=CC;
var $varName = generateBoolean($string >= ( -uploadLog(7,-9) ))
  $element=383;
def TABLE[$file][i] {
	if($value){
	$number /= $stat >= calcResponse(9,downloadMessage(( ( ( $url ) <= TABLE[( ( 5 ) )][ROWS - ( ( processMessage(removeNumber()) ) > ( getLog(4) ) == TABLE[$element][insertBooleanClient(( selectString(9,selectPlugin(9) != ( $file ),3) ),COLS)] )] ) ),TABLE[10][3 > ( 4 )],( TABLE[$stat - ( ( 1 ) )][( TABLE[( 1 )][$file] )] )));
	if(--$secondChar != ( COLS )){

} else {
	updateYML(generateMessage());
	if(doInfoError($number / --callElement(6) >= ( $char < uploadPlugin($theInteger,COLS) ) /\ TABLE[TABLE[$value][$char] - TABLE[-$element][$element] == removeFile(-$stat,$lastFile,( -updateNum(( -5 * ( -$value ) ),$randomBoolean,getLibrary(6) >= 1 != ( -TABLE[ROWS][( setXML(-5) + -ROWS )] )) \/ -COLS ))][$array] > getName(7,TABLE[---$integer][3] + -$myPosition))){
	$array += 2
}
};
	$boolean -= ROWS
}
}
 }
  $varBoolean = $position;
  return $varBoolean;
}

assert ( 2 ) : " that quite sleep seen their horn of with had offers"function calcResponse() {
  $element = null;
  $number = 567;
  $value = $number + 7345;
def generateElement($element,$integer){
	-7;
	callMessage(TABLE[ROWS][-ROWS /\ --( $element ) > -$value + ROWS],3,calcStringServer(-$number * ( $element /\ removeConfigServer(( ( downloadMessage(5,-7) ) ) > -6,generateNum(callResponse(( 9 ),1),calcString(COLS,( insertJSON(COLS) )),--7 >= downloadPlugin(-$string,2,$element) < $file == 9)) ),callLog(-( 0 == generateModule($value) ))))
}
  $value=PANCop;
assert ROWS - ROWS : "you of the off was world regulatory upper then twists need"
 if ($value <= "") {
  $number = 2147;
  $value = $number + Xq4vc0X;
def TABLE[( -updateContent($file,$integer * 1 / TABLE[addJSON(-( ROWS ),-doDataClient(-5,-8 / calcLong(( 2 ),( 10 )),getDataSecurely(0,generateElement($position,( 5 - downloadLibrary(-$element,$firstBoolean,0) )) + ROWS,-$theString)))][2] - ROWS \/ $char > 2,-1) )][l] {
	if(downloadConfig(selectError(6),generateBoolean(( -( -TABLE[( 3 )][-( -selectString($item /\ -0,addTXT(insertFile())) ) + ROWS] != -insertLong() ) )),( -doFloat(selectConfig(2,( ( $element ) ))) )) <= TABLE[0][$value] == -removeArray(TABLE[6][--ROWS + ( -$thisInteger /\ -TABLE[( $char )][$stat] ) <= ( $array ) + ROWS - downloadRequest()],ROWS)){

}
}
  $stat = ;
  $value = $stat + 84k4aUj7P;
var $position = -$value /\ ( 5 <= -( ( TABLE[ROWS][( uploadUrl($position > TABLE[( ( TABLE[$number][( 4 <= setResponse(calcFile(( -COLS < COLS ),COLS)) )] ) )][--2 > updateContent(--( 4 ),ROWS)],addIntegerFast() / 4 \/ 0,$auxUrl) )] != ( 10 ) ) ) * $simplifiedNumber )
 }
  $position = 8785;
  $value = $position + zrAxm;
def getConfig($char){
	$string /= addLong(TABLE[( selectNameFirst(( doModule(addJSON(( updateJSONAgain(( -2 ),( 6 )) < doId(-$integer) )),downloadUrl(4) - downloadError(ROWS,COLS)) ),( selectNum($stat,$file) ),removeBoolean(4,ROWS) + removeNum(TABLE[$char][10])) ) \/ -ROWS][setYML(( $lastNumber ))],TABLE[9][-addPlugin(-ROWS)] <= -TABLE[-callJSON($oneElement,$array)][$number + -1 != COLS])
}
 while ($value != "IgP8z") {
  $value=2177;
def TABLE[9][i] {
	$array += ( -$array ) < ( 4 )
}
  $integer = dylCGpQ;
  $name = $integer + 4775;
var $char = ( $element )
 }
  $value=7101;
assert ( ( 6 != selectEnum(COLS,setBoolean(),ROWS) \/ 1 < -COLS - 2 / $array ) * ROWS ) : " forwards, as noting legs the temple shine."
 if ($value <= "UHPAr5") {
  $auxArray = aeQF3mCaG;
  $thisUrl = $auxArray + 1456;
var $url = 8
  $item = ;
  $value = $item + G;
var $url = 9
 }
 if ($value >= "2891") {
  $name=3149;
def TABLE[COLS][x] {
	-8 >= $stat != -( ROWS );
	addBoolean($onePosition);
	ROWS
}
  $value=88;
def callDependency($integer,$integer){
	$value /= --5 >= $string
}
 }
  $element = $value;
  return $element;
}

def processName($char,$thisChar){
	updateConfig();
	if(6){

} else {

};
	-updateMessage(ROWS * TABLE[$firstFile][$integer] + COLS,TABLE[2][$theElement]) - $url
}function updateEnum() {
  $boolean = null;
  $secondValue = 8902;
  $integer = $secondValue + 3774;
var $element = ---TABLE[( $boolean )][( $auxString )]
var $number = $string
  $integer=4382;
def TABLE[( ( -7 ) + 6 != ( TABLE[3][$file] ) )][k] {
	TABLE[-removeFloatAgain(( $item ),calcEnum($auxStat,( 2 ),-( ( 3 > -TABLE[5][-TABLE[( $element )][removeNumber(6)]] ) ) >= 2 + $file),( 7 /\ 8 ))][-$array / $item];
	$item
}
 for ($integer=0; $integer<=5; $integer++) {
  $integer=2182;
def TABLE[-TABLE[updateLong(removeJSON(ROWS,6 * $char),-( doPlugin(removeJSONFirst(( 4 ),( -10 )),-$position,addDataset($file)) ),-6) <= --9 * $boolean][$position]][m] {
	if(( -( ( -$name ) >= --( -1 ) ) + 5 )){
	if(-ROWS){
	$array -= COLS
}
};
	if(( -generateLibraryPartially(1 < -6) ) + $position){
	-8
}
}
  $integer=7870;
def TABLE[ROWS][m] {

}
 }
 if ($integer == "9413") {
  $element = MHxY;
  $oneString = $element + 9573;
def TABLE[8 * -ROWS][m] {
	callName(7,COLS);
	if(9){
	$integer /= -( ( ( updateConfig(getUrl(( ( -( -$stat ) ) - $integer >= 0 ),-ROWS),-uploadJSON(COLS)) ) ) );
	$number -= 1
}
}
  $element = qNGsp0f1j;
  $integer = $element + PnYfwq;
def updateDependency($secondInteger,$element){
	$position += ( 3 + COLS != doString($varNumber,-( -COLS )) );
	10 /\ ( $number ) - $boolean;
	$stat -= ROWS
}
 }
var $integer = TABLE[5][( COLS )]
  $boolean = $integer;
  return $boolean;
}

assert -( $char ) < 10 \/ ( -9 ) * -TABLE[ROWS][4 == ( ( downloadInfo(addFileCompletely(),2) ) )] != TABLE[ROWS][( 8 ) /\ TABLE[-( 7 )][selectElementSecurely(8 <= 6 >= ROWS == TABLE[2 * -9][insertJSON(TABLE[$file /\ TABLE[updateCollection(COLS)][$item]][-$integer])],( 6 < -TABLE[updateNum($position)][$url] ))]] == 5 + $boolean <= 10 : "you of the off was world regulatory upper then twists need"