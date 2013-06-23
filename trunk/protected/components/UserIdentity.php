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
		elseif ($user->password !== crypt($this->password, $user->password)) //en $user->password est� el hash
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else {
		    $this->_id = $user->id;
		    $this->setState('email', $user->email);
			$this->errorCode=self::ERROR_NONE;
        }
		return !$this->errorCode;
	}

	public function getId()
    {
        return $this->_id;
    }
}