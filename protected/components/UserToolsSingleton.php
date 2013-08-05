<?php

/**
 * Utilizado para obtener los nombres de los usuarios de un grupo (no bando, ojo) y otra información de los mismos
 */
class UserToolsSingleton extends CApplicationComponent
{
	private $_users = null;
	private $_modifiers = null;

	/*public function setModel($id)
    {
        $this->_model = Event::model()->findByPk($id);
    }*/

    //Cojo el alias de sesión si ya está cargado, porque no es algo que cambie
    public function getAlias($userId)
    {
		if (!$this->_users) {
			//Yii::log('user: '.$userId, 'error', 'No existe, los cargo');
			$this->getUsers();
		}
			//return null;

		//Yii::log('user: '.$userId, 'error', 'Cojo nombre');
        foreach($this->_users as $user) {
			if ($user->id == $userId)
				return $user->alias;
		}
    }
	
	//Esta función la coge automáticamente. Coge usuarios del grupo actual
    public function getUsers()
    {
        if (!$this->_users)
        {
            /*if (!isset(Yii::app()->user->group_id))
                return null;*/

            $criteria = New CDbCriteria;

            //Si es admin tendrá grupo null y cogeré todos los usuarios
            if (Yii::app()->user->group_id !== null) {
                $criteria->condition = 'group_id=:groupId';
                $criteria->params = array(':groupId'=>Yii::app()->user->group_id);
            }

            $this->_users = User::model()->findAll($criteria);
        }

        return $this->_users;
    }

    ///TODO poner comentarios a las funciones, que el PHPStorm te lo saca todo facilmente

    /**
     * @param null $groupId: grupo dentro del que buscar, si es null se coge el activo
     * @param null $exclude: array de id de usuario a excluir
     * @return CActiveRecord. Usuario encontrado o null si no hay resultados.
     */
    public function randomUser($groupId=null, $exclude=null)
    {
        $criteria = New CDbCriteria;

        if ($groupId == null) $groupId = Yii::app()->user->group_id;

        if ($exclude !== null)
            $criteria->condition = 'id NOT IN ('.implode(',', $exclude).') ';

            //$excluidos = ' WHERE id NOT IN ('.implode(',', $exclude).') ';

        //$sql = 'SELECT id FROM user '.$excluidos.' ORDER BY RAND() LIMIT 1';

        $criteria->order = 'BY RAND()';
        $criteria->limit = '1';

        $user = User::model()->find($criteria);
        return $user;
    }


    ///IDEA Al expirar hidratar a lo mejor podía dar el tueste extra regenerado entre el momento de expiración y el lastRegenerationTime para ser justos.
	
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
			if (isset(Yii::app()->user->id))
				$userId = Yii::app()->user->id;
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
			if (isset(Yii::app()->user->gropu_id))
				$groupId = Yii::app()->user->gropu_id;
			else
				return false;
		}
		
		$mods = Modifier::model()->findAll(array('condition'=>'duration_type=:type AND group_id=:group', 'params'=>array(':type'=>'evento', ':group'=>$groupId)));
		
		if ($mods === null)
			return false;
		else {
			foreach($mods as $mod) {
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
			if (!isset(Yii::app()->user->id))
				return null;
				
			$this->_modifiers = Modifier::model()->findAll(array('condition'=>'target_final_id=:target', 'params'=>array(':target'=>Yii::app()->user->id)));
		}		
		
		return $this->_modifiers;
	}
	
	/*public function updateModifiers()
	{
		if (!isset(Yii::app()->user->id))
				return null;
				
		$this->_modifiers = Modifier::model()->findAll(array('condition'=>'target_final_id=:target', 'params'=>array(':target'=>Yii::app()->user->id)));
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
	
}