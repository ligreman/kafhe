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
  public $repeat_password;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('meal_id, drink_id', 'alMenosUno', 'on'=>'create, update', 'message'=>'Debes elegir {attribute}.'), //no hace falta aquí el on, pero lo dejo como ejemplo
            //array('meal_id', 'exist', 'attributeName'=>'id', 'className'=>'Meal'),
            //array('drink_id', 'exist', 'attributeName'=>'id', 'className'=>'Drink'),
            array('ito', 'boolean'),
            array('ito', 'esIto'),
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
			'meal_id'=>'Comidas',
            'drink_id'=>'Bebidas',
            'ito'=>'Desayuno ITO',
		);
	}
}