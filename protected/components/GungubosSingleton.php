<?php

/**
 * GungubosSingleton para operaciones relacionadas con los gungubos de un evento
 */
class GungubosSingleton extends CApplicationComponent
{
    // $checkTime si es true hace comprobación de la última vez que se criaron gungubos, si es false devuelve los gungubos que se crian por tick
    public function getGungubosCriados($event, $checkTime=true)
    {		
        if ($checkTime) {
            //Compruebo si ha pasado el tiempo suficiente para criar en el evento
            $last_born = strtotime($event->last_gungubos_timestamp);
            
            if (time() < ($last_born + intval(Yii::app()->config->getParam('tiempoCriaGungubos')) ) )
                return false; //no ha pasado el tiempo suficiente
        }

		//Cantidad base de gungubos criados		
		$gungubos['kafhe'] = $gungubos['achikhoria'] = intval(Yii::app()->config->getParam('gungubosCriadosIntervalo'));
				
        //Aquí irán otros modificadores, etc...
		
		//Devuelvo los gungubos criados        
		return $gungubos;
    }

	
}


