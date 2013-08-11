<?php

/**
 * TuesteSingleton para operaciones relacionadas con el tueste
 */
class TuesteSingleton extends CApplicationComponent
{
    // $checkTime si es true hace comprobación de la última vez que se regeneró tueste, si es false devuelve el tueste que se regenera por tick
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
		
		//Tueste extra por modificadores y talentos. Miro los mods que me afectan a la regenración
		$porcentajePorModificadores = 0;
		$hasHidratado = false;

        //Si es el usuario activo me ahorro una consulta a BBDD
		if (isset(Yii::app()->currentUser) && isset(Yii::app()->currentUser->id) && $user->id == Yii::app()->currentUser->id) {
			if(Yii::app()->usertools->inModifiers(Yii::app()->params->modifierHidratado))
				$hasHidratado = true;
		} else {
			$mods = Modifier::model()->findAll(array('condition'=>'target_final_id=:target', 'params'=>array(':target'=>$user->id)));
			if($mods!==null  &&  Yii::app()->usertools->inModifiers(Yii::app()->params->modifierHidratado, $mods))
				$hasHidratado = true;
		}
		
		if($hasHidratado) $porcentajePorModificadores = 25; //25% más rápido por estar hidratado			
		$tuesteExtraPorModificadores = round(intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) * $porcentajePorModificadores / 100);


		//Devuelvo el tueste regenerado
        $tuesteRegenerado = intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) + $tuesteExtraPorRango + $tuesteExtraPorModificadores;
		return $tuesteRegenerado;
    }

	
}