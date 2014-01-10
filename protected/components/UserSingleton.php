<?php

/** Utilizado para la información del usuario actual
 */
class UserSingleton extends CApplicationComponent
{
	private $_model = null;
	private $_gungubos = null;
	
	/*public function setModel($id)
    {
        $this->_model = User::model()->findByPk($id);
    }*/
	
	
    //Esta función la coge automáticamente
    public function getModel()
    {
        if (!$this->_model)
        {
            $this->_model = User::model()->findByPk(Yii::app()->user->id); //La cojo el UserIdentify
			$this->_gungubos = intval(Gungubo::model()->count(array('condition'=>'owner_id=:owner AND location=:lugar', 'params'=>array(':owner'=>$this->_model->id, ':lugar'=>'corral'))));
        }
        return $this->_model;
    }

    public function getMaxTueste() {
        return Yii::app()->tueste->getMaxTuesteUser($this->_model);
    }

    public function getId() { return $this->model->id; }
    public function getUsername() { return $this->model->username; }
    public function getAlias() { return $this->model->alias; }
    public function getEmail() { return $this->model->email; }
    public function getBirthdate() { return $this->model->birthdate; }
    public function getRole() { return $this->model->role; }
    public function getGroupId() { return $this->model->group_id; }
    public function getSide() { return $this->model->side; }
    public function getStatus() { return $this->model->status; }
    public function getRank() { return $this->model->rank; }
    public function getPtosTueste() { return $this->model->ptos_tueste; }
    public function getPtosRetueste() { return $this->model->ptos_retueste; }
    public function getPtosRelanzamiento() { return $this->model->ptos_relanzamiento; }
    public function getPtosTalentos() { return $this->model->ptos_talentos; }
    public function getTostolares() { return $this->model->tostolares; }
    public function getExperience() { return $this->model->experience; }
    public function getFame() { return $this->model->fame; }
    public function getSugarcubes() { return $this->model->sugarcubes; }
    public function getDominioTueste() { return $this->model->dominio_tueste; }
    public function getDominioHabilidades() { return $this->model->dominio_habilidades; }
    public function getDominioBando() { return $this->model->dominio_bandos; }
    public function getTimes() { return $this->model->times; }
    public function getCalls() { return $this->model->calls; }
    public function getLastRegenTimestamp() { return $this->model->last_regen_timestamp; }
    public function getLastNotificationRead() { return $this->model->last_notification_read; }
    public function getLastActivity() { return $this->model->last_activity; }
    public function getActive() { return $this->model->active; }
	
	public function getGungubosCorral() { return $this->_gungubos; }
	
}