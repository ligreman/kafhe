<?php

/** ConfigurationSingleton para la configuración de Kafhe
 */
class HistorySkillSingleton extends CApplicationComponent
{
	private $_model = null;

	public function setModel()
    {
        $tamanoHistorico = max( ceil(Yii::app()->currentUser->rank/2) , 1); //Mínimo de 1
        $tamanoHistorico = min($tamanoHistorico, 4); //Máximo de 4

        //Miro el histórico de ejecuciones del jugador
        $this->_model = HistorySkillExecution::model()->findAll(array('condition'=>'caster_id=:caster', 'params'=>array(':caster'=>Yii::app()->currentUser->id), 'order'=>'timestamp DESC', 'limit'=>$tamanoHistorico));
    }

    //Esta función la coge automáticamente
    public function getModel()
    {
        if (!$this->_model)
        {            
            $this->setModel();
        }

        return $this->_model;
    }
   
}