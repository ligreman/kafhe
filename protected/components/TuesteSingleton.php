<?php

/** TuesteSingleton para operaciones relacionadas con el tueste
 */
class TuesteSingleton extends CApplicationComponent
{
    /** Devuelve la cantidad de tueste que regenera un usuario en un tick
     * @param $user Objeto del usuario al que regenerar tueste
     * @param bool $checkTime si es true hace comprobación de la última vez que se regeneró tueste, si es false devuelve el tueste que se regenera por tick
     * @return bool|int Cantidad de tueste regenerado o false si no se puede regenerar aún.
     */
    public function getTuesteRegenerado($user, $checkTime=true)
    {
        if ($checkTime) {
            //Compruebo si ha pasado el tiempo suficiente para regenerar al usuario
            $last_regen = strtotime($user->last_regen_timestamp);
            //echo "last-> ".$last_regen."\n";
            //echo "last+-> ".($last_regen+600)."\n";
            //echo "now-> ".time()."\n";
            if (time() < ($last_regen + intval(Yii::app()->config->getParam('tiempoRegeneracionTueste')) ) )
                return false; //no ha pasado el tiempo suficiente
        }

		//Calculo el tueste que regenera en función de su rango
		//$porcentajePorRango = ($user->rank-1) * intval(Yii::app()->config->getParam('porcentajeTuesteExtraPorRango')); //tueste extra por cada rango a partir del 2
		//$tuesteExtraPorRango = round(intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) * $porcentajePorRango / 100);
		$tuesteExtraPorRango = $this->getTuesteRegeneradoPorRango($user);
				
		///IDEA Los talentos crean modificadores con duración null
		
		//Tueste extra por modificadores y talentos. Miro los mods que me afectan a la regeneración
		$porcentajePorModificadores = 0;
		$signoRegeneracion = 1; //positivo o negativo
		$estaHidratado = $estaDesecado = false;

        //Si es el usuario activo me ahorro una consulta a BBDD
		if (isset(Yii::app()->currentUser) && isset(Yii::app()->currentUser->id) && $user->id == Yii::app()->currentUser->id) {
			if(Yii::app()->modifier->inModifiers(Yii::app()->params->modifierHidratado)) $estaHidratado = true;
            if(Yii::app()->modifier->inModifiers(Yii::app()->params->modifierDesecado)) $estaDesecado = true;
		} else {
			$mods = Modifier::model()->findAll(array('condition'=>'target_final=:target', 'params'=>array(':target'=>$user->id)));
			if($mods!=null  &&  Yii::app()->modifier->inModifiers(Yii::app()->params->modifierHidratado, $mods)) $estaHidratado = true;
            if($mods!=null  &&  Yii::app()->modifier->inModifiers(Yii::app()->params->modifierDesecado, $mods)) $estaDesecado = true;
		}

		//Si el usuario no es cazador o alistado o libertador no le afectará la hidratación
		if($estaHidratado && ($user->status==Yii::app()->params->statusCazador || $user->status==Yii::app()->params->statusAlistado || $user->status==Yii::app()->params->statusLibertador)) {
		    $skillH = Skill::model()->findByAttributes(array('keyword'=>Yii::app()->params->skillHidratar));
            $porcentajePorModificadores = $skillH->extra_param; //Este extra param indica el % de regeneración extra
        } //más rápido por estar hidratado

        if($estaDesecado) $signoRegeneracion = -1; //Regeneración negativa

		$tuesteExtraPorModificadores = round(intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) * $porcentajePorModificadores / 100);

		//Devuelvo el tueste regenerado
        $tuesteRegenerado = $signoRegeneracion * ( intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) + $tuesteExtraPorRango + $tuesteExtraPorModificadores );
		return $tuesteRegenerado;
    }

    /** Obtiene el tueste regenerado por cada rango de un usuario
     * @param $user Objeto del usuario
     * @return float Tueste extra por rango
     */
    public function getTuesteRegeneradoPorRango($user) {
        $porcentajePorRango = ($user->rank-1) * intval(Yii::app()->config->getParam('porcentajeTuesteExtraPorRango')); //tueste extra por cada rango a partir del 2
        $tuesteExtraPorRango = round(intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) * $porcentajePorRango / 100);

        return $tuesteExtraPorRango;
    }


    /** Reparte el tueste de los almacenes entre los jugadores del bando
     * @param $event Objeto evento del que repartir el tueste
     * @param $onlyActive Si repartir sólo entre los jugadores activos (Cazadores y Alistados).
     * @return Devuelve un array con el tueste que ha sobrado, array('kafhe'=>tueste, 'achikhoria'=>tueste)
     */
    public function repartirTueste($event, $onlyActive=true) {
        $sobra = array('kafhe'=>0, 'achikhoria'=>0);

        if ($onlyActive)
            $jugadores = User::model()->findAll(array('condition'=>'group_id=:grupo AND side!=:bando AND (status=:estado1 OR status=:estado2)', 'params'=>array(':grupo'=>$event->group_id, ':bando'=>'libre', 'estado1'=>Yii::app()->params->statusCazador, 'estado2'=>Yii::app()->params->statusAlistado)));
        else
            $jugadores = User::model()->findAll(array('condition'=>'group_id=:grupo AND side!=:bando', 'params'=>array(':grupo'=>$event->group_id, ':bando'=>'libre')));

        //Primero cuento número de jugadores
        $kafhes = $achis = 0;
        foreach($jugadores as $jugador) {
            if ($jugador->side=='kafhe') $kafhes++;
            elseif ($jugador->side=='achikhoria') $achis++;
        }

        //Ahora calculo cuánto le tocaría a cada jugador
        $cuantoKhafe = $cuantoAchikhoria = 0;

        if ($kafhes>0)
            $cuantoKhafe = intval($event->stored_tueste_kafhe / $kafhes);
        else
            $sobra['kafhe'] += $event->stored_tueste_kafhe; //No hay kafheítas así que no se gasta el tueste

        if ($achis>0)
            $cuantoAchikhoria = intval($event->stored_tueste_achikhoria / $achis);
        else
            $sobra['achikhoria'] += $event->stored_tueste_achikhoria; //No hay achis así que no se gasta el tueste

        //Reparto
        foreach($jugadores as $jugador) {
            //Le doy el tueste
            if ($jugador->side=='kafhe') {
                if ($cuantoKhafe==0) continue;

                $jugador->ptos_tueste += $cuantoKhafe;
                if ($jugador->ptos_tueste > Yii::app()->config->getParam('maxTuesteUsuario')) {
                    $sobra['kafhe'] += $jugador->ptos_tueste - Yii::app()->config->getParam('maxTuesteUsuario');
                    $jugador->ptos_tueste = Yii::app()->config->getParam('maxTuesteUsuario'); //Como mucho esto
                }
            } elseif ($jugador->side=='achikhoria') {
                if ($cuantoAchikhoria==0) continue;

                $jugador->ptos_tueste += $cuantoAchikhoria;
                if ($jugador->ptos_tueste > Yii::app()->config->getParam('maxTuesteUsuario')) {
                    $sobra['achikhoria'] += $jugador->ptos_tueste - Yii::app()->config->getParam('maxTuesteUsuario');
                    $jugador->ptos_tueste = Yii::app()->config->getParam('maxTuesteUsuario'); //Como mucho esto
                }
            }

            //Salvo al jugador
            if (!$jugador->save())
                throw new CHttpException(400, 'Error al guardar el reparto de tueste extra del almacén en el jugador ('.$jugador->id.') del evento ('.$event->id.')');
        }

        return $sobra;
    }


    /** Obtiene el tueste máximo que puede tener el usuario
     * @param $user Objeto usuario
     * @return float Máximo de tueste del usuario
     */
    public function getMaxTuesteUser($user) {
	    $max = intval(Yii::app()->config->getParam('maxTuesteUsuario'));
	    $max -= $user->ptos_retueste; //Le quito el retueste que tenga

	    return $max;
	}
}