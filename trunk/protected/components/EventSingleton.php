<?php

/**
 * EventSingleton para el estado de los eventos actuales 
 */
class EventSingleton extends CApplicationComponent
{
	private $_model = null;
	
	
	public function selectCaller()
	{
		if (!isset(Yii::app()->user->group_id))
            return null;
				
		$this->getModel(); //Por si acaso
			
		//Cojo los usuarios alistados del grupo de este evento
		$users = User::model()->findAll(array('condition'=>'group_id=:group AND status=:status', 'params'=>array(':group'=>$this->getGroup(), ':status'=>Yii::app()->params->statusAlistado)));
				
		//Probabilidades de cada bando
		$bandos = Yii::app()->usertools->calculateSideProbabilities($this->getGungubosKafhe(), $this->getGungubosAchikhoria());
		
		//Elijo bando "perdedor"		
		$kafhePerc = $bandos['kafhe'] * 100;
		$randomSide = mt_rand(1, 10000);
		if ( 1<=$randomSide && $randomSide<=$kafhePerc ) $bandoPerdedor = 'kafhe';
		else  $bandoPerdedor = 'achikhoria';
		
		//Preparo un array con las probabilidades de cada uno de los usuarios del bando perdedor
		$probabilidades = Yii::app()->usertools->calculateProbabilities(Yii::app()->user->group_id, true, $bandoPerdedor);
		if ($probabilidades === null) return false;
		
		//Elijo llamador "ganador" dentro de ese bando
		$randomCaller = mt_rand(1, 10000);
		$anterior = 0;	
		$caller = null;
	
		foreach($probabilidades as $user=>$valor) {
			$valor = $valor * 100; //tiene 2 decimales así que lo convierto a entero
			if ($valor == 0) continue;
			
			if ( (($anterior+1) <= $randomCaller) && ($randomCaller <= ($anterior+$valor)) ) {
				$caller = $user;
			}
			
			$anterior += $valor;
		}
		
		if ($caller === null) return false;		
		
		return array('side'=>$bandoPerdedor, 'userId'=>$caller);
	}
	
	
	/** GETTERS Y SETTERS GENERALES **/

	public function setModel($id)
    {
        $this->_model = Event::model()->findByPk($id);
    }

    //Esta función la coge automáticamente
    public function getModel()
    {
        if (!$this->_model)
        {
            $type = 'desayuno'; //Si no hay un modelo cargado, cargo el modelo de desayuno por defecto
            //Yii::log('Modelo Event', 'info', 'aa.yy.zz');

            if (!isset(Yii::app()->user->group_id))
                return null;

            //Aquí se podría mirar la sesión también para tomar de allí el evento actualmente cargado. Yii::app()->session['var'] = 'value';
            if (isset($_GET['event_type'])) {
                //tipo indicado en el GET
                $type=htmlentities($_GET['event_type']);
            }

            //Cargo el último evento por fecha, del tipo seleccionado
            $criteria = New CDbCriteria;
            $criteria->condition = 'group_id=:groupId AND type=:type';
            $criteria->params = array(':groupId'=>Yii::app()->user->group_id, ':type'=>$type);
            $criteria->order = 'date DESC';
            $criteria->limit = '1';

            $this->_model = Event::model()->find($criteria);
        }

        return $this->_model;
    }

	public function getId() { return $this->model->id; }	
	public function getGroup() { return $this->model->group_id; }	
    public function getStatus() { return $this->model->status; }	
	public function getCaller() { return $this->model->caller_id; }	
	public function getType() { return $this->model->type; }
	public function getGungubosKafhe() { return $this->model->gungubos_kafhe; }
	public function getGungubosAchikhoria() { return $this->model->gungubos_achikhoria; }
}