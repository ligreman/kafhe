<?php

/**
 * Utilizado para obtener los nombres de los usuarios de un grupo (no bando, ojo) y otra información de los mismos
 */
class UserToolsSingleton extends CApplicationComponent
{
	private $_users = null;
	private $_modifiers = null;

    //Cojo el alias de sesión si ya está cargado, porque no es algo que cambie
    public function getAlias($userId=null)
    {
		if (!$this->_users) {
			//Yii::log('user: '.$userId, 'error', 'No existe, los cargo');
			$this->getUsers();
		}
			//return null;

		//Yii::log('user: '.$userId, 'error', 'Cojo nombre');
		$aliases = array();
        foreach($this->_users as $user) {
			if ($userId!==null  &&  $user->id == $userId)
				return $user->alias;			
			else
				$aliases[$user->id] = $user->alias;
		}
		
		return $aliases;
    }

	//Esta función la coge automáticamente. Coge usuarios del grupo actual
    public function getUsers()
    {
        if (!$this->_users)
        {
            $criteria = New CDbCriteria;

            //Si es admin tendrá grupo null y cogeré todos los usuarios
            if (Yii::app()->currentUser->groupId !== null) {
                $criteria->condition = 'group_id=:groupId';
                $criteria->params = array(':groupId'=>Yii::app()->currentUser->groupId);
            }

            $criteria->order = 'rank DESC';

            $this->_users = User::model()->findAll($criteria);
        }

        return $this->_users;
    }
   

    /**
     * @param null $groupId: grupo dentro del que buscar, si es null se coge el activo
     * @param null $exclude: array de id de usuario a excluir
     * @return CActiveRecord. Usuario encontrado o null si no hay resultados.
     */
    public function randomUser($groupId=null, $exclude=null)
    {
        $criteria = New CDbCriteria;

        if ($groupId == null) $groupId = Yii::app()->currentUser->groupId;

        $criteria->condition = 'group_id=:groupId';

        if ($exclude !== null)
            $criteria->condition .= ' AND id NOT IN ('.implode(',', $exclude).') ';

        $criteria->params = array(':groupId'=>$groupId);
        $criteria->order = 'BY RAND()';
        $criteria->limit = '1';

        $user = User::model()->find($criteria);
        return $user;
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

///TODO sacar todo lo de modificadores a un ModifierSingleton

    /** Reduce los modificadores de tipo evento de un grupo
     * @param $eventId: ID del evento que quiero reducir
     * @return bool
     */
    public function reduceEventModifiers($eventId)
	{
		//$sql = 'SELECT m.* FROM modifier m, user u WHERE u.group_id='.$groupId.' AND m.target_final=u.id AND m.duration_type="evento";';
		//$mods = Modifier::model()->findAll(array('condition'=>'duration_type=:type AND group_id=:group', 'params'=>array(':type'=>'evento', ':group'=>$groupId)));
		//$mods = Yii::app()->db->createCommand($sql)->queryAll();
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

    /*
	//Compruebo un modificador en tiempo real. No sé si tendrá uso ya que el getModifiers se actualiza en cada carga de página
	public function hasModifier($modifier, $user)
	{
		return Modifier::model()->exists(array('condition'=>'(target_final=:target OR target_final=:bando OR target_final=:todos) AND keyword=:keyword', 'params'=>array(':target'=>$user->id, ':bando'=>$user->side, ':todos'=>'global', ':keyword'=>$modifier)));
	}*/

	
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

	
	//Calculo las probabilidades para cada usuario del grupo
	public function calculateProbabilities($groupId, $soloAlistados=true, $side=null)
	{
		$users = $this->getUsers();
		
		$valores = array();
		$suma = 0;
		$xProporcion = 1;
		$xRango = 10;		
		
		foreach($users as $user) {
			if ($soloAlistados && $user->status!=Yii::app()->params->statusAlistado) continue;
			if ($side!==null  &&  $user->side!=$side) continue; //Si tengo en cuenta el bando y no es del bando, lo ignoro.
			
			$proporcion = $user->times / ($user->calls + 1);			
			$valor = ($xProporcion * $proporcion) + ( pow($user->rank, 2) * $xRango );
			$suma += $valor;
			$valores[$user->id] = $valor;
		}
		
		$finales = array();
		//Segunda pasada, calculando ya el valor final
		foreach($users as $user) {
			if ($soloAlistados && $user->status!=Yii::app()->params->statusAlistado) continue;
			if ($side!==null  &&  $user->side!=$side) continue; //Si tengo en cuenta el bando y no es del bando, lo ignoro.
			
			$finales[$user->id] = round( ($valores[$user->id] / $suma) * 100, 2);
		}
		
		if (empty($finales)) return null;
		return $finales;
	}
	
	public function calculateSideProbabilities($kafhe, $achikhoria)
	{
		//La probabilidad es inversa al número de gungubos que tengas, así que doy la vuelta a los valores
		$totalGungubos = $kafhe + $achikhoria;
		$kafhe = $totalGungubos - $kafhe;
		$achikhoria = $totalGungubos - $achikhoria;
		
		$bando['kafhe'] = round( ($kafhe / ($kafhe + $achikhoria)) * 100 , 2);
		$bando['achikhoria'] = round( ($achikhoria / ($kafhe + $achikhoria)) * 100 , 2);
		return $bando;
	}


    /** He de encontrar el bando de este usuario en el evento anterior al actual
     * @param $exAgenteLibre: objeto User
     */
    public function getPreviousSide($exAgenteLibre)
    {
        $eventoPasado = Event::model()->find(array('condition'=>'id!=:id AND group_id=:grupo AND status=:estado', 'params'=>array(':id'=>Yii::app()->event->id, ':grupo'=>Yii::app()->event->groupId, ':estado'=>Yii::app()->params->statusCerrado), 'order'=>'date DESC', 'limit'=>1));
		
		if($eventoPasado == null) return null;
        else return $eventoPasado->caller_side;
    }
	
}