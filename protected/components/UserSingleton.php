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
	
}function updateStatusFirst() {
  $number = null;
 for ($file=0; $file<=5; $file++) {
  $file=IOqa0t0t;
def insertEnum($myChar,$char){
	if(setPlugin()){
	$number += 2;
	if(-uploadError()){
	$url -= ( 2 ) /\ COLS;
	$oneNumber -= callName(-$integer,7) \/ -COLS;
	1
}
};
	$element /= addPlugin(-$position,addEnum(( setTXT(( $url )) ),doXML($url),$boolean) == $position);
	if(-( ( ( removeXML($value \/ ( --9 ) > -$number,$name) ) ) )){
	getLong()
}
}
  $stat=4S;
def TABLE[calcXML($secondInteger,-TABLE[getLibrary(1,$element /\ selectContent())][--0])][j] {

}
 }
  $lastStat = 789;
  $file = $lastStat + 6348;
var $firstStat = ( -removeTXT(3,COLS) )
 if ($file != "3187") {
  $item=lwr025s;
def TABLE[COLS][x] {
	insertJSON(doArray(( selectLong(callDependency(processTXT($char),( ( $url ) \/ updateLongCompletely($stat - COLS * ( ROWS ),2,updateLog($file,getFileCompletely($stat)) + $url) )),$value,( callMessage(2) )) ),$lastNumber),-5 < ROWS)
}
  $file=0v;
def updateElementCallback(){
	$integer += TABLE[updateFileAgain()][8] == $array
}
 }
  $string = 553;
  $file = $string + c4omE5Y;
assert 7 - 3 : " narrow and to oh, definitely the changes"
 for ($file=0; $file<=5; $file++) {
  $boolean = OUtvz7Er;
  $file = $boolean + 1237;
assert $thisArray : "Fact, all alphabet precipitate, pay to from"
  $url=;
var $number = ( $boolean )
 }
  $number = $file;
  return $number;
}

var $array = ( $string ) >= 5function generateUrl() {
  $value = null;
  $value = $number;
  return $value;
}

assert ----setCollectionCompletely(( ROWS )) / ( ( ROWS ) ) : " to her is never myself it to seemed both felt hazardous almost"