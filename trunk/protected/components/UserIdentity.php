<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	private $_id;	

	public function authenticate()
	{
		/*$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;*/
		
		$user = User::model()->findByAttributes(array('username'=>$this->username));

		/*Yii::log($user->password, 'error', 'BBDD');
		Yii::log($this->password, 'error', 'Form');
		Yii::log(crypt($this->password, $user->password), 'error', 'hash');*/

		if ($user === NULL)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif ($user->password !== crypt($this->password, $user->password)) //en $user->password esta el hash
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else {
		    $this->_id = $user->id;

			//Otros campos a los que acceder. OJO no se actualizan dinámicamente (se cargan al identificarse, o de forma manual programandolo), poner sólo campos estáticos
		    $this->setState('username', $user->username);
            $this->setState('alias', $user->alias);
			$this->setState('email', $user->email);
		    $this->setState('group_id', $user->group_id);
			//$this->setState('side', $user->side);
			//$this->setState('status', $user->status);

			$this->errorCode=self::ERROR_NONE;
        }
		return !$this->errorCode;
	}

	public function getId()
    {
        return $this->_id;
    }
	
	
	//El side y status etc... se cogen de modules/rights/components/RWebUser.php
	/*public function getSide()
	{
		$user = User::model()->findByPk($this->_id);
		return $user->side;
	}
	
	public function getStatus()
	{
		$user = User::model()->findByPk($this->_id);
		return $user->status;
	}*/


	/* PARA CREAR CONTRASEÑA DEL USUARIO En UserController añadir

    /**
     * Generate a random salt in the crypt(3) standard Blowfish format.
     *
     * @param int $cost Cost parameter from 4 to 31.
     *
     * @throws Exception on invalid cost parameter.
     * @return string A Blowfish hash salt for use in PHP's crypt()
     *
    private static function blowfishSalt($cost = 13)
    {
        if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
            throw new Exception("cost parameter must be between 4 and 31");
        }

        $rand = array();

        for ($i = 0; $i < 8; $i += 1) {
            $rand[] = pack('S', mt_rand(0, 0xffff));
        }

        $rand[] = substr(microtime(), 2, 6);
        $rand = sha1(implode('', $rand), true);
        $salt = '$2a$' . sprintf('%02d', $cost) . '$';
        $salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));

        return $salt;
    }


	//En actionCreate
        $model->attributes=$_POST['User'];
        $model->password = crypt($model->password, self::blowfishSalt());
        if($model->save())


    //En actionUpdate
	    $model=$this->loadModel($id);
		$previousPassword = $model->password;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];

			if ($previousPassword != $model->password)
                $model->password = crypt($model->password, self::blowfishSalt());

			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
	*/
}