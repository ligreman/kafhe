<?php

/**
 * EventSingleton para el estado de los eventos actuales 
 */
class EventSingleton extends CApplicationComponent
{
	private $_model = null;

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

	public function getId()
    {
        return $this->model->id;
    }
	
    public function getStatus()
    {
        return $this->model->status;
    }
	
	public function getCaller()
	{
		return $this->model->caller_id;
	}
	
	public function getType()
	{
		return $this->model->type;
	}
}