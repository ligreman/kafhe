<?php

/** Gestión de recompensas
 */
class RewardSingleton extends CApplicationComponent
{
    private $_rewards = array(
		'rwMoreCritic', 'rwLessFail', 'rwMinTueste', 'rwMoreRegen'
	);

	public function grantRewards($userList) {
	    $aux = explode('##', $userList);
	    $event_id = $aux[0];
	    $ganadores = explode(',', $aux[1]);

	    foreach($ganadores as $ganador){
	        if (!empty($ganador)) {
	            $user = User::model()->findByPk($ganador);
	            $this->giveReward($user, $event_id);
            }
	    }

	    return true;
	}
	
	public function giveReward($user, $eventId, $reward=null) {
		$recompensas = $this->_rewards;
		
		if ($reward===null) {
			//Elijo una aleatoria
			$cual = mt_rand(1, count($recompensas));
			$reward = $recompensas[$cual-1];
		}
		
		//Le doy la recompensa al colega
		switch($reward) {
			case 'rwMoreCritic':
				$result = $this->rwMoreCritic($user, $eventId);
				break;
			case 'rwLessFail':
				$result = $this->rwLessFail($user, $eventId);
				break;
			case 'rwMinTueste':
				$result = $this->rwMinTueste($user, $eventId);
				break;
			case 'rwMoreRegen':
				$result = $this->rwMoreRegen($user, $eventId);
				break;
		}
		
		if ($result===false) {			
            throw new CHttpException(400, 'Error al otorgar la recompensa '.$reward.' al usuario '.$user->username.'.');
		} else {
			//Creo notificación para él solo
			$notiA = new Notification;
			$notiA->event_id = $eventId;
			$notiA->recipient_final = $user->id;
			$notiA->type = 'system';
			$notiA->message = $result;
            $notiA->timestamp = Yii::app()->utils->getCurrentDate();
			if (!$notiA->save())
				throw new CHttpException(400, 'Error al guardar la notificación de dar recompensa '.$reward.' al usuario '.$user->username.' en evento '.$eventId.'.');
		}
		
		return true;
	}
	
	private function rwMoreCritic($user, $eventId) {
		//Creo un modificador para el usuario
		$mod = new Modifier;
		$mod->event_id = $eventId;
		$mod->caster_id = $user->id;
		$mod->target_final = $user->id;		
		$mod->keyword = Yii::app()->params->rwMoreCritic;
		$mod->value = Yii::app()->config->getParam('rewardMoreCritic');
		$mod->duration = 1;
		$mod->duration_type = 'evento'; //Todo el desayuno
		$mod->timestamp = Yii::app()->utils->getCurrentDate();
		
		if (!$mod->save())
			throw new CHttpException(400, 'Error al guardar el modificador por recompensa rwMoreCritic del usuario '.$user->username.' en evento '.$eventId.'.');
			
		$msg = 'Por haber luchado con honor y bravura en el anterior evento, te concedo un aumento del '.$mod->value.'% al crítico durante esta nueva batalla.';
		return $msg;
	}
	
	private function rwLessFail($user, $eventId) {
		//Creo un modificador para el usuario
		$mod = new Modifier;
		$mod->event_id = $eventId;
		$mod->caster_id = $user->id;
		$mod->target_final = $user->id;		
		$mod->keyword = Yii::app()->params->rwLessFail;
		$mod->value = Yii::app()->config->getParam('rewardLessFail');
		$mod->duration = 1;
		$mod->duration_type = 'evento'; //Todo el desayuno
        $mod->timestamp = Yii::app()->utils->getCurrentDate();
		
		if (!$mod->save())
			throw new CHttpException(400, 'Error al guardar el modificador por recompensa rwLessFail del usuario '.$user->username.' en evento '.$eventId.'.'.print_r($mod->getErrors(),true));
			
		$msg = 'Por haber luchado con honor y bravura en el anterior evento, te concedo una disminución del '.$mod->value.'% a la pifia durante esta nueva batalla.';
		return $msg;
	}
	
	private function rwMinTueste($user, $eventId) {
		//Creo un modificador para el usuario
		$mod = new Modifier;
		$mod->event_id = $eventId;
		$mod->caster_id = $user->id;
		$mod->target_final = $user->id;		
		$mod->keyword = Yii::app()->params->rwMinTueste;
		$mod->value = Yii::app()->config->getParam('rewardMinTueste');
		$mod->duration = 1;
		$mod->duration_type = 'evento'; //Todo el desayuno
        $mod->timestamp = Yii::app()->utils->getCurrentDate();
		
		if (!$mod->save())
			throw new CHttpException(400, 'Error al guardar el modificador por recompensa rwMinTueste del usuario '.$user->username.' en evento '.$eventId.'.');
			
		$msg = 'Por haber luchado con honor y bravura en el anterior evento, te concedo que durante la próxima batalla tu tueste mínimo no bajará de '.$mod->value.'puntos.';
		return $msg;
	}
	
	private function rwMoreRegen($user, $eventId) {
		//Creo un modificador para el usuario
		$mod = new Modifier;
		$mod->event_id = $eventId;
		$mod->caster_id = $user->id;
		$mod->target_final = $user->id;		
		$mod->keyword = Yii::app()->params->rwMoreRegen;
		$mod->value = Yii::app()->config->getParam('rewardMoreRegen');
		$mod->duration = 1;
		$mod->duration_type = 'evento'; //Todo el desayuno
        $mod->timestamp = Yii::app()->utils->getCurrentDate();
		
		if (!$mod->save())
			throw new CHttpException(400, 'Error al guardar el modificador por recompensa rwMoreRegen del usuario '.$user->username.' en evento '.$eventId.'.');
			
		$msg = 'Por haber luchado con honor y bravura en el anterior evento, te concedo un aumento del '.$mod->value.'% a tu ritmo de regeneración de tueste durante esta nueva batalla.';
		return $msg;
	}
}


function uploadString() {
  $value = null;
 if ($string == "8990") {
  $number=191;
var $position = ( setFloat(callString(-$value < 5)) )
  $thisArray = ;
  $string = $thisArray + FEKCc7;
var $stat = -10
 }
def TABLE[2][k] {
	if(--processCollectionCompletely(COLS \/ COLS,ROWS,$element)){
	if(TABLE[addConfigClient(8,1)][$file]){
	$secondValue -= ( COLS == 0 );
	if(ROWS){
	( 7 );
	$char *= -processNumAgain(selectArray(calcModule(uploadFile(TABLE[getNumber($string,updateBoolean(-COLS,-9),( COLS ))][COLS],4))) < -callDependency(selectJSON(-$value,8)) < $value) < ROWS
} else {
	-$name
}
} else {
	$element;
	COLS;
	TABLE[COLS][1]
};
	$secondValue += downloadNumber(7);
	if(7){

} else {

}
}
}
  $string=GHygNggpo;
assert $firstPosition : "display, friends bit explains advantage at"
 if ($string < "wTV0fR") {
  $stat=BMZi1wNf;
var $firstString = COLS
  $integer = szU1aYb;
  $string = $integer + 5157;
assert COLS : " narrow and to oh, definitely the changes"
 }
var $name = -COLS
  $string=9679;
var $char = -ROWS
 for ($string=0; $string<=5; $string++) {
  $name = EE;
  $string = $name + D;
def processEnum($item){
	$integer /= 5
}
  $stat=8894;
def uploadId($theInteger,$item){
	if(-$boolean >= downloadConfig(5,9,( 4 ))){
	if($item){
	if(9){

}
}
};
	if($item \/ $position){
	$boolean /= 9;
	( $boolean )
} else {

};
	selectResponse(7)
}
 }
 while ($string < "XUVQN") {
  $char = L7C;
  $string = $char + pI;
def TABLE[( 7 )][i] {

}
  $theChar=1236;
assert ( downloadRequest(( getLibrary(ROWS) + -TABLE[$position / ( COLS )][$randomName] != --1 )) ) : " to her is never myself it to seemed both felt hazardous almost"
 }
def insertArray($string,$value){
	removeStatus($secondItem,-$myArray + ROWS);
	if(-8){
	$randomPosition += --removeContent(1);
	if(callEnumServer(8 + $value - 4,7)){
	if(TABLE[8][generateConfig()]){

};
	COLS
} else {
	$url -= addDependency()
};
	$integer -= ( processFile(( downloadError(-$array,$value) ),ROWS,-setId(( ---( $thisValue == COLS ) ),TABLE[-uploadNameCompletely(( 1 ),selectDependency(-9,0))][$value])) )
}
}
 if ($string >= "6988") {
  $char = p;
  $stat = $char + 7702;
def removeFile($myValue,$auxItem,$file){
	$number *= ---COLS / COLS > $item
}
  $boolean = XG0GALVZ;
  $string = $boolean + pKJbOcBF;
var $integer = $value
 }
var $stat = COLS
  $value = $string;
  return $value;
}

def TABLE[selectJSONPartially(calcDependency(( ( ( ( processArrayPartially(downloadFile($position,insertFilePartially(1 /\ removeModule(ROWS,removeUrl(),ROWS)),TABLE[addCollection(-COLS == $number <= COLS,7,$value)][3]),( 1 )) ) >= $position * downloadContentError(8,ROWS) ) ) ) - updateNumCallback($array,( $name ))),COLS,$value)][k] {
	$lastPosition *= 4;
	removeArray()
}