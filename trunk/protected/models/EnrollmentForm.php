<?php

/**
 * EnrollmentForm class.
 * EnrollmentForm is the data structure for keeping
 * enrollment form data. It is used by the 'index' action of 'EnrollmentController'.
 */
class EnrollmentForm extends CFormModel
{
	//public $userId;
	//public $eventId;
	public $mealId;
	public $drinkId;
	public $ito;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(			
			array('mealId, drinkId', 'required', 'on'=>'create, update'), //no hace falta aquí el on, pero lo dejo como ejemplo
                        array('mealId', 'exist', 'attributeName'=>'id', 'className'=>'Meal'),
                        array('drinkId', 'exist', 'attributeName'=>'id', 'className'=>'Drink'),
                        array('ito', 'boolean'),
                        array('ito', 'esIto'),
		);
	}
        
        public function esIto($attribute,$params)
        {
            if ($this->ito && $this->mealId!=null && $this->drinkId!=null) 
            {
                //Cojo la comida y bebida de BBDD
                $meal = Meal::model()->findByPk($this->mealId);
                $drink = Drink::model()->findByPk($this->drinkId);

                //Compruebo que ambos son candidatos a ser ito
                if($meal->ito!=1)
                    $this->addError('mealId','La comida seleccionada no puede ser un ITO.');
                else if($drink->ito!=1)
                    $this->addError('drinkId','La bebida seleccionada no puede ser un ITO.');
            }
        }

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'mealId'=>'Comida',
                    'drinkId'=>'Bebida',
                    'ito'=>'Desayuno ITO',
		);
	}
}