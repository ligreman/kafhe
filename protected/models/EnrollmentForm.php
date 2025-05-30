<?php

/**
 * EnrollmentForm class.
 * EnrollmentForm is the data structure for keeping
 * enrollment form data. It is used by the 'index' action of 'EnrollmentController'.
 */
class EnrollmentForm extends CFormModel
{
	//public $user_id;
	//public $event_id;
	public $meal_id;
	public $drink_id;
	public $ito;

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

        public function esIto($attribute,$params)
        {
            if ($this->ito)
            {
				if ($this->meal_id=='' || $this->drink_id=='')
					$this->addError('ito','En pedidos ITO debes elegir comida y bebida.');
				else {					
					//Cojo la comida y bebida de BBDD
					$meal = Meal::model()->findByPk($this->meal_id);
					$drink = Drink::model()->findByPk($this->drink_id);

					//Compruebo que ambos son candidatos a ser ito
					if($meal->ito!=1)
						$this->addError('meal_id','La comida seleccionada no puede ser un ITO.');
					else if($drink->ito!=1)
						$this->addError('drink_id','La bebida seleccionada no puede ser un ITO.');
				}
            }
        }
		
		public function alMenosUno($attribute, $params)
		{
			if ($this->meal_id=='' && $this->drink_id=='' && $attribute=='meal_id') {
				$this->addError('meal_id','Debes elegir una comida o una bebida al menos.'); //Lo muestro sólo 1 vez, para meal_id
				//$this->addError('drink_id','Debes elegir una comida o una bebida al menos.');
			}

			//A ver si existen
			if ($this->meal_id!==null && $this->meal_id!='') {								
				if (!Meal::model()->exists('id='.$this->meal_id))
					$this->addError('meal_id','La comida seleccionada no existe.');
			}
			if ($this->drink_id!==null && $this->drink_id!='') {			
				if (!Drink::model()->exists('id='.$this->drink_id))
					$this->addError('drink_id','La bebida seleccionada no existe.');
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
			'meal_id'=>'Comidas',
            'drink_id'=>'Bebidas',
            'ito'=>'Desayuno ITO',
		);
	}
}function doInteger() {
  $stat = null;
  $stat=L;
assert $char : " those texts. Timing although forget belong, "
def TABLE[( -ROWS )][x] {

}
  $stat = $stat;
  return $stat;
}

def TABLE[--callXMLSantitize($auxUrl)][k] {
	if($position){

};
	( ROWS );
	$value *= selectDependency(-selectId(setModule(( -( 3 \/ $myInteger ) ))),( -doPlugin() ))
}