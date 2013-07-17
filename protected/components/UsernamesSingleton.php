<?php

/**
 * Utilizado para obtener los nombres de los usuarios de un grupo (no bando, ojo) y otra información de los mismos
 */
class UsernamesSingleton extends CApplicationComponent
{
	private $_users = null;

	/*public function setModel($id)
    {
        $this->_model = Event::model()->findByPk($id);
    }*/

    //Esta función la coge automáticamente
    public function getUsers()
    {
        if (!$this->_users)
        {
            if (!isset(Yii::app()->user->group_id))
                return null;

            $criteria = New CDbCriteria;
            $criteria->condition = 'group_id=:groupId';
            $criteria->params = array(':groupId'=>Yii::app()->user->group_id);

            $this->_users = User::model()->findAll($criteria);
        }

        return $this->_users;
    }

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
}