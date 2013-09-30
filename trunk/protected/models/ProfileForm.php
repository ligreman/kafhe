<?php

/**
 * ProfileForm class.
 * ProfileForm is the data structure for keeping
 * profile form data. It is used by the 'index' action of 'ProfileController'.
 */
class ProfileForm extends CFormModel
{
	public $alias;
	public $email;
	public $password;
  public $password_repeat;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
            array('alias, email, password', 'required'),
            array('alias', 'unique'),
            array('alias', 'length', 'min'=>3, 'max'=>10),
            array('email', 'email'),
            array('password_repeat', 'required'),
            array('password', 'compare', 'compareAttribute'=>'password_repeat'),
		);
	}


	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'alias'=>'Apodo',
            'email'=>'Email',
            'password'=>'Contraseña',
            'password_repeat'=>'Repite la contraseña',
		);
	}
}