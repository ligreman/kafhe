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
		
		///TODO Gungubos extra por modificadores y talentos de usuarios de este evento y cada bando.		
        //Si es el usuario activo me ahorro una consulta a BBDD
		/*if (isset(Yii::app()->user) && isset(Yii::app()->user->id)) {
			$users = Yii::app()->usertools->users;
		} else {
			$users = User::model()->findAll(array('condition'=>'group_id=:grupo', 'params'=>array(':grupo'=>$event->group_id)));
		}*/
		//¿Mejor hacerlo con un event_modifiers? Tener modificadores para eventos, separado de los de usuarios.
		
		//Devuelvo los gungubos criados        
		return $gungubos;
    }

	
}


