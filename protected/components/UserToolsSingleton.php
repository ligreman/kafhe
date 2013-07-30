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
	
	//Esta función la coge automáticamente
    public function getUsers()
    {
        if (!$this->_users)
        {
            if (!isset(Yii::app()->user->group_id))
                return null;

            $criteria = New CDbCriteria;

            //Si es admin tendrá grupo null y cogeré todos los usuarios
            if (Yii::app()->user->group_id != null) {
                $criteria->condition = 'group_id=:groupId';
                $criteria->params = array(':groupId'=>Yii::app()->user->group_id);
            }

            $this->_users = User::model()->findAll($criteria);
        }

        return $this->_users;
    }



    ///IDEA Al expirar hidratar a lo mejor podía dar el tueste extra regenerado entre el momento de expiración y el lastRegenerationTime para ser justos.
	
	//Compruebo si han expirado modificadores de todo el mundo
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
			}
		}		
	}
	
	//Reduce los usos de un modificador del jugador
	public function reduceModifierUses($mod_keyword, $userId=null)
	{
		if ($userId == null) {
			if (isset(Yii::app()->user->id))
				$userId = Yii::app()->user->id;
			else
				return false;
		}
		
		$mod = Modifier::model()->find(array('condition'=>'target_final_id=:target AND keyword=:keyword', 'params'=>array(':target'=>$userId, ':keyword'=>$mod_keyword)));
		
		if ($mod==null  ||  $mod->duration_type!='usos') return false;
		
		if ($mod->duration <= 0)
			return false;
		
		$mod->duration--;
		
		if ($mod->duration <= 0)
			return $mod->delete();
		else
			return $mod->save();
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

	//Compruebo un modificador en tiempo real
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
}