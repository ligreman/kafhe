<?php

/**
 * Utilizado para obtener los usuarios de un grupo (no bando, ojo) y otra información de los mismos
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

        if ($groupId === null) $groupId = Yii::app()->currentUser->groupId;

        $criteria->condition = 'group_id=:groupId';

        if ($exclude !== null)
            $criteria->condition .= ' AND id NOT IN ('.implode(',', $exclude).') ';

        $criteria->params = array(':groupId'=>$groupId);
        $criteria->order = 'BY RAND()';
        $criteria->limit = '1';

        $user = User::model()->find($criteria);
        return $user;
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
     */
    public function getPreviousSide()
    {
        $eventoPasado = Event::model()->find(array('condition'=>'id!=:id AND group_id=:grupo AND status=:estado', 'params'=>array(':id'=>Yii::app()->event->id, ':grupo'=>Yii::app()->event->groupId, ':estado'=>Yii::app()->params->statusCerrado), 'order'=>'date DESC', 'limit'=>1));
		
		if($eventoPasado === null) return null;
        else return $eventoPasado->caller_side;
    }

}