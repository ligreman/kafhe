<?php

/**
 * Utilizado para obtener los nombres de los usuarios de un grupo (no bando, ojo) y otra información de los mismos
 */
class UserToolsSingleton extends CApplicationComponent
{
	private $_users = null;
	private $_modifiers = null;
	private $_statuses = null;

	/*public function setModel($id)
    {
        $this->_model = Event::model()->findByPk($id);
    }*/

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
	
	public function getStatusName($status_id)
	{
		if (!$this->_statuses) {
			$estados = array(
				Yii::app()->params->statusCriador => 'Criador',
				Yii::app()->params->statusCazador => 'Cazador',
				Yii::app()->params->statusAlistado => 'Alistado',
				Yii::app()->params->statusBaja => 'Baja',
				Yii::app()->params->statusDesertor => 'Desertor',
				Yii::app()->params->statusLibre => 'Agente libre',
			);
			$this->_statuses = $estados;
		}
		
		return $this->_statuses[$status_id];
	}
	
	//Esta función la coge automáticamente. Coge usuarios del grupo actual
    public function getUsers()
    {
        if (!$this->_users)
        {
            /*if (!isset(Yii::app()->currentUser->groupId))
                return null;*/

            $criteria = New CDbCriteria;

            //Si es admin tendrá grupo null y cogeré todos los usuarios
            if (Yii::app()->currentUser->groupId !== null) {
                $criteria->condition = 'group_id=:groupId';
                $criteria->params = array(':groupId'=>Yii::app()->currentUser->groupId);
                $criteria->order = 'rank DESC';
            }

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

        if ($exclude !== null)
            $criteria->condition = 'id NOT IN ('.implode(',', $exclude).') ';

            //$excluidos = ' WHERE id NOT IN ('.implode(',', $exclude).') ';

        //$sql = 'SELECT id FROM user '.$excluidos.' ORDER BY RAND() LIMIT 1';

        $criteria->order = 'BY RAND()';
        $criteria->limit = '1';

        $user = User::model()->find($criteria);
        return $user;
    }
	
	//Compruebo si han expirado modificadores de todo el mundo
	public function checkModifiersExpiration($finEvento=false)
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
				if ($finEvento && $modifier->duration_type=='evento' && $modifier->duration<=0) {
					$modifier->delete();
				}
			}
		}		
	}

    /** Reduce los usos de un modificador del jugador
     * @param $mod_keyword
     * @param null $userId
     * @return bool
     */
    public function reduceModifierUses($mod_keyword, $userId=null)
	{
		if ($userId === null) {
			if (isset(Yii::app()->currentUser->id))
				$userId = Yii::app()->currentUser->id;
			else
				return false;
		}
		
		$mod = Modifier::model()->find(array('condition'=>'target_final_id=:target AND keyword=:keyword', 'params'=>array(':target'=>$userId, ':keyword'=>$mod_keyword)));
		
		if ($mod===null  ||  $mod->duration_type!='usos') return false;
		
		if ($mod->duration <= 0)
			return false;
		
		$mod->duration--;
		
		if ($mod->duration <= 0)
			return $mod->delete();
		else
			return $mod->save();
	}
	
	public function reduceEventModifiers($groupId=null)
	{
		if ($groupId === null) {
			if (isset(Yii::app()->currentUser->groupId))
				$groupId = Yii::app()->currentUser->groupId;
			else
				return false;
		}	
					
		$sql = 'SELECT m.* FROM modifier m, user u WHERE u.group_id='.$groupId.' AND m.target_final_id=u.id AND m.duration_type="desayuno";';
		//$mods = Modifier::model()->findAll(array('condition'=>'duration_type=:type AND group_id=:group', 'params'=>array(':type'=>'evento', ':group'=>$groupId)));
		$mods = Yii::app()->db->createCommand($sql)->queryAll();
		
		if ($mods === null)
			return false;
		else {
			foreach($mods as $mod) {
				if ($mod->duration_type != 'desayuno') continue;
			
				$mod->duration--;
		
				if ($mod->duration <= 0) {
					if(!$mod->delete())
						Yii::log('Error al eliminar el modificador de evento '.$mod->id.'.', 'error', 'reduceEventModifiers');
				} else {
					if(!$mod->save())
						Yii::log('Error al reducir el modificador de evento '.$mod->id.'.', 'error', 'reduceEventModifiers');
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
                return true; //Devuelvo que sí al primer modificador que coincida, pero puede haber otros
        }

        //Si llego aquí es que no lo tiene
        return false;
    }

	//Compruebo un modificador en tiempo real. No sé si tendrá uso ya que el getModifiers se actualiza en cada carga de página
	public function hasModifier($modifier, $userId)
	{
		return Modifier::model()->exists(array('condition'=>'target_final_id=:target AND keyword=:keyword', 'params'=>array(':target'=>$userId, ':keyword'=>$modifier)));		
	}

	
	//Carga los modificadores en variable sólo una vez por carga de página
	public function getModifiers() 
	{	
		if (!$this->_modifiers) {
			if (!isset(Yii::app()->currentUser->id))
				return null;

			$this->_modifiers = Modifier::model()->findAll(array('condition'=>'target_final_id=:target', 'params'=>array(':target'=>Yii::app()->currentUser->id)));
		}
		
		return $this->_modifiers;
	}
	
	/*public function updateModifiers()
	{
		if (!isset(Yii::app()->currentUser->id))
				return null;
				
		$this->_modifiers = Modifier::model()->findAll(array('condition'=>'target_final_id=:target', 'params'=>array(':target'=>Yii::app()->currentUser->id)));
	}*/
	
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