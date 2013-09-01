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
		$porcentajePorRango = ($user->rank-1) * 10; //10% por cada rango a partir del 2
		$tuesteExtraPorRango = round(intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) * $porcentajePorRango / 100);
				
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
		
		if($estaHidratado) $porcentajePorModificadores = 25; //25% más rápido por estar hidratado
        if($estaDesecado) $signoRegeneracion = -1; //Regeneración negativa

		$tuesteExtraPorModificadores = round(intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) * $porcentajePorModificadores / 100);

		//Devuelvo el tueste regenerado
        $tuesteRegenerado = $signoRegeneracion * ( intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) + $tuesteExtraPorRango + $tuesteExtraPorModificadores );
		return $tuesteRegenerado;
    }

	
}