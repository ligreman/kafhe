<?php

/**
 * ModifierSingleton para operaciones relacionadas con los modificadores
 */
class ModifierSingleton extends CApplicationComponent
{
    private $_modifiers = null;
	
	//Carga los modificadores en variable sólo una vez por carga de página
	public function getModifiers() 
	{	
		if (!$this->_modifiers) {
			if (!isset(Yii::app()->currentUser->id))
				return null;

            $criteria = New CDbCriteria;
            $criteria->condition = 'target_final=:target OR target_final=:bando';

            if (Yii::app()->user->side != 'libre')
                $criteria->condition .= ' OR target_final="global"'; //Si no soy del bando libre me afecta el "global" también

            $criteria->params = array(':target'=>Yii::app()->currentUser->id, ':bando'=>Yii::app()->currentUser->side);

            //Busco los mods que me afecta a mi ID, a mi bando o a global
			$this->_modifiers = Modifier::model()->findAll($criteria);
		}
		
		return $this->_modifiers;
	}
	
	
	//Compruebo si han expirado modificadores de todo el mundo, de cualquier tipo.
	public function checkModifiersExpiration()
	{
		$modifiers = Modifier::model()->findAll(array('condition'=>'duration IS NOT NULL AND duration_type IS NOT NULL'));
		if($modifiers !== null) 
		{		
			$currentTime = time();
			foreach($modifiers as $modifier) {			
				//Compruebo si ha expirado en caso de ser horas
				$tiempoCaducidad =  strtotime($modifier->timestamp) + ($modifier->duration * 60 *60); //en segundos
				if ($modifier->duration_type=='horas'  &&  $currentTime > $tiempoCaducidad) {				
					//Lo borro en ese caso
					$modifier->delete();
				}
				
				//Compruebo si caduca por usos (usos=0)
				if ($modifier->duration_type=='usos' && $modifier->duration<=0) {
					$modifier->delete();
				}
				
				//Compruebo si caduca por fin de evento
				if ($modifier->duration_type=='evento' && $modifier->duration<=0) {
					$modifier->delete();
				}
			}
		}		
	}

    /** Reduce los usos de un modificador del jugador
     * @param $modifier: el objeto del modificador a reducir su uso. Si es null ha de estar definido el parámetro $mod_keyword
     * @param $mod_keyword: keyword del modificador a reducir su uso. Si es null ha de estar el parámetro $modifier definido
     * @param null $userId: Si es null se toma el usuario activo. Va siempre en conjunto con mod_keyword
     * @return bool
     */
    public function reduceModifierUses($modifier=null, $mod_keyword=null, $userId=null)
	{
	    if ($mod_keyword===null && $modifier===null) return false;

		if ($userId === null) {
			if (isset(Yii::app()->currentUser->id))
				$userId = Yii::app()->currentUser->id;
			else
				return false;
		}

		//Saco el modificador según lo que me hayan pasado
		if ($mod_keyword!==null)
		    $modifier = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$userId, ':keyword'=>$mod_keyword)));

        //Posibles puntos de fallo inesperado
		if ($modifier===null  ||  $modifier->duration_type!='usos'  ||  $modifier->duration <= 0) return false;

        $modifier->duration--;
		
		if ($modifier->duration <= 0) {
			if(!$modifier->delete())
                Yii::log('Error al eliminar el modificador '.$modifier->keyword.' ('.$modifier->id.').', 'error', 'reduceModifierUses');
        } else {
            if(!$modifier->save())
                Yii::log('Error al reducir los usos del modificador '.$modifier->keyword.' ('.$modifier->id.').', 'error', 'reduceModifierUses');
        }

        return true;
	}

    /** Reduce los modificadores de tipo evento
     * @param $eventId: ID del evento que quiero reducir
     * @return bool
     */
    public function reduceEventModifiers($eventId)
	{		
		$mods = Modifier::model()->findAll(array('condition'=>'event_id=:evento AND duration_type=:tipo', 'params'=>array(':evento'=>$eventId, 'tipo'=>'evento')));
		
		if ($mods === null)
			return false;
		else {
			foreach($mods as $mod) {
				if ($mod->duration_type != 'evento') continue;
			
				$mod->duration--;
		
				if ($mod->duration <= 0) {
					if(!$mod->delete())
						Yii::log('Error al eliminar el modificador de evento '.$mod->keyword.' ('.$mod->id.').', 'error', 'reduceEventModifiers');
				} else {
					if(!$mod->save())
						Yii::log('Error al reducir el modificador de evento '.$mod->keyword.' ('.$mod->id.').', 'error', 'reduceEventModifiers');
				}
			}
		}
		return true;
	}
	

    /* Compruebo si tiene un modificador. Esto lo que hace sólo es buscar dentro del grupo de modificadores uno concreto, como un "in_array"
	 * Si el haystack es nulo considero que quiero comprobar los modificadores del usuario activo
	 */
    public function inModifiers($needle, $haystack=null)
    {
		if($haystack == null) {			
			$haystack = $this->getModifiers();
		}
		
        foreach ($haystack as $modifier) {
            if ($needle == $modifier->keyword)
                return $modifier; //Devuelvo el primer modificador que coincida, pero puede haber otros
        }

        //Si llego aquí es que no lo tiene
        return false;
    }
	
}