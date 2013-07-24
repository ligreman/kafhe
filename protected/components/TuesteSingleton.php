<?php

/**
 * TuesteSingleton para operaciones relacionadas con el tueste
 */
class TuesteSingleton extends CApplicationComponent
{	    
    public function getTuesteRegenerado($user)
    {
		//Compruebo si ha pasado el tiempo suficiente para regenerar al usuario
		if (time() < ($user->last_regen_timestamp + intval(Yii::app()->config->getParam('tiempoRegeneracionTueste')) ) ) 
			return false; //no ha pasado el tiempo suficiente
		
		//Calculo el tueste que regenera en función de su rango
		$porcentajePorRango = ($user->rank-1) * 10; //10% por cada rango
		$tuesteExtraPorRango = round(Yii::app()->config->getParam('tuesteRegeneradoIntervalo') * $porcentajePorRango / 100);
		$tuesteRegenerado = intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) + $tuesteExtraPorRango;
		
		//Aplico los talentos, modificadores y bonificadores correspondientes
		
		//Devuelvo el tueste regenerado
		return $tuesteRegenerado;
    }

	
}