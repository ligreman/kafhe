<?php

/** GungubosSingleton para operaciones relacionadas con los gungubos de un evento
 */
class GungubosSingleton extends CApplicationComponent
{
    /** Obtiene los gungubos cazados por intervalo para un evento concreto.
     * @param $event Objeto del evento que mirar.
     * @param bool $checkTime si es true hace comprobación de la última vez que se cazaron gungubos, si es false devuelve los gungubos que se crian por tick
     * @return array Array con dos claves, 'kafhe' y 'achikhoria', cada una de ellas con el número de gungubos generados.
     */
    /*public function getGungubosCazados($event, $checkTime=true)
    {		
        if ($checkTime) {
            //Compruebo si ha pasado el tiempo suficiente para criar en el evento
            $last_born = strtotime($event->last_gungubos_criadores);
            
            if (time() < ($last_born + intval(Yii::app()->config->getParam('tiempoCriaGungubos')) ) )
                return false; //no ha pasado el tiempo suficiente
        }

		//Cantidad base de gungubos cazados
		$gungubos['kafhe'] = $gungubos['achikhoria'] = intval(Yii::app()->config->getParam('gungubosCriadosIntervalo'));

		//Gungubos por criadores de cada bando. Cojo la skill cazar gungubos como referencia
		$skill = Skill::model()->find(array('condition'=>'keyword=:key', 'params'=>array(':key'=>Yii::app()->params->skillCazarGungubos)));
		$costeTueste = $skill->cost_tueste;
		$gungubosSkill = intval($skill->extra_param);
		$gungubosSkill = Yii::app()->skill->randomWithRangeProportion($gungubosSkill, 0.5); //Calculo una proporción igualmente que se hace con la habilidad

		$extraKafhe = round( ($event->stored_tueste_kafhe * $gungubosSkill) / $costeTueste, 0);
        $extraAchikhoria = round( ($event->stored_tueste_achikhoria * $gungubosSkill) / $costeTueste, 0);
        $gungubos['kafhe'] += $extraKafhe;
        $gungubos['achikhoria'] += $extraAchikhoria;


		
		//Devuelvo los gungubos criados        
		return $gungubos;
    }*/


    /*public function getGungubosOteados($event) {
        $libres = $event->gungubos_population;

        if ($libres == 0)
            $aproximacion = 'No consigues ver ningún gungubo libre.';
        if ($libres>0 && $libres<200)
            $aproximacion = 'Te cuesta ver gungubos en libertad.';
        if ($libres>=200 && $libres<500)
            $aproximacion = 'Ves unos cuantos gungubos libres.';
        if ($libres>=500)
            $aproximacion = 'Ves gran cantidad de gungubos en libertad.';

        return array('numero'=>$libres, 'texto'=>$aproximacion);
    }*/
	
}


