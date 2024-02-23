<?php

/** TuesteSingleton para operaciones relacionadas con el tueste
 */
class TuesteSingleton extends CApplicationComponent
{
    /** Devuelve la cantidad de tueste que regenera un usuario en un tick
     * @param $user Objeto del usuario al que regenerar tueste
     * @param bool $checkTime si es true hace comprobación de la última vez que se regeneró tueste, si es false devuelve el tueste que se regenera por tick
     * @return bool|int Cantidad de tueste regenerado o false si no se puede regenerar aún.
     */
    public function getTuesteRegenerado($user, $checkTime=true)
    {
        /*if ($checkTime) {
            //Compruebo si ha pasado el tiempo suficiente para regenerar al usuario
            $last_regen = strtotime($user->last_regen_timestamp);
            //echo "last-> ".$last_regen."\n";
            //echo "last+-> ".($last_regen+600)."\n";
            //echo "now-> ".time()."\n";
            if (time() < ($last_regen + intval(Yii::app()->config->getParam('tiempoRegeneracionTueste')) ) )
                return false; //no ha pasado el tiempo suficiente
        }*/

		//Calculo el tueste que regenera en función de su rango
		//$porcentajePorRango = ($user->rank-1) * intval(Yii::app()->config->getParam('porcentajeTuesteExtraPorRango')); //tueste extra por cada rango a partir del 2
		//$tuesteExtraPorRango = round(intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) * $porcentajePorRango / 100);
		$tuesteExtraPorRango = $this->getTuesteRegeneradoPorRango($user);
				
		///IDEA Los talentos crean modificadores con duración null
		
		//Tueste extra por modificadores y talentos. Miro los mods que me afectan a la regeneración
		$porcentajePorModificadores = 0;
		$signoRegeneracion = 1; //positivo o negativo
		$estaHidratado = $estaDesecado = $recompensaMoreRegen = false;

        //Si es el usuario activo me ahorro una consulta a BBDD
		if (isset(Yii::app()->currentUser) && isset(Yii::app()->currentUser->id) && $user->id == Yii::app()->currentUser->id) {
			if(Yii::app()->modifier->inModifiers(Yii::app()->params->modifierHidratado)) $estaHidratado = true;
            if(Yii::app()->modifier->inModifiers(Yii::app()->params->modifierDesecado)) $estaDesecado = true;
			$recompensaMoreRegen = Yii::app()->modifier->inModifiers(Yii::app()->params->rwMoreRegen);
		} else {
			$mods = Modifier::model()->findAll(array('condition'=>'target_final=:target', 'params'=>array(':target'=>$user->id)));
			if($mods!=null  &&  Yii::app()->modifier->inModifiers(Yii::app()->params->modifierHidratado, $mods)) $estaHidratado = true;
            if($mods!=null  &&  Yii::app()->modifier->inModifiers(Yii::app()->params->modifierDesecado, $mods)) $estaDesecado = true;
			if($mods!=null) $recompensaMoreRegen = Yii::app()->modifier->inModifiers(Yii::app()->params->rwMoreRegen, $mods);
		}

		//Si el usuario no es inactivo le afecta la hidratación
		if($estaHidratado && $user->active) {
		    $skillH = Skill::model()->findByAttributes(array('keyword'=>Yii::app()->params->skillHidratar));
            $porcentajePorModificadores += intval($skillH->extra_param); //Este extra param indica el % de regeneración extra
        }

        //Si el usuario es el iluminado y está activo
        if($user->side=='libre' && $user->active) {
            $porcentajePorModificadores += 50;
        }
		
		//Si tiene una recompensa		
		if($recompensaMoreRegen!==false && $user->active) {
            $porcentajePorModificadores += $recompensaMoreRegen->value;
        }

        if($estaDesecado) $signoRegeneracion = -1; //Regeneración negativa

		$tuesteExtraPorModificadores = round(intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) * $porcentajePorModificadores / 100);

		//Devuelvo el tueste regenerado
        $tuesteRegenerado = $signoRegeneracion * ( intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) + $tuesteExtraPorRango + $tuesteExtraPorModificadores );
		return $tuesteRegenerado;
    }

    /** Obtiene el tueste regenerado por cada rango de un usuario
     * @param $user Objeto del usuario
     * @return float Tueste extra por rango
     */
    public function getTuesteRegeneradoPorRango($user) {
        $porcentajePorRango = ($user->rank-1) * intval(Yii::app()->config->getParam('porcentajeTuesteExtraPorRango')); //tueste extra por cada rango a partir del 2
        $tuesteExtraPorRango = round(intval(Yii::app()->config->getParam('tuesteRegeneradoIntervalo')) * $porcentajePorRango / 100);

        return $tuesteExtraPorRango;
    }


    /** Reparte el tueste de los almacenes entre los jugadores del bando
     * @param $event Objeto evento del que repartir el tueste
     * @param $onlyActive Si repartir sólo entre los jugadores activos (Cazadores y Alistados).
     * @return Devuelve un array con el tueste que ha sobrado, array('kafhe'=>tueste, 'achikhoria'=>tueste)
     */
    /*public function repartirTueste($event, $onlyActive=true) {
        $sobra = array('kafhe'=>0, 'achikhoria'=>0);

        if ($onlyActive)
            $jugadores = User::model()->findAll(array('condition'=>'group_id=:grupo AND side!=:bando AND (status=:estado1 OR status=:estado2)', 'params'=>array(':grupo'=>$event->group_id, ':bando'=>'libre', 'estado1'=>Yii::app()->params->statusCazador, 'estado2'=>Yii::app()->params->statusAlistado)));
        else
            $jugadores = User::model()->findAll(array('condition'=>'group_id=:grupo AND side!=:bando', 'params'=>array(':grupo'=>$event->group_id, ':bando'=>'libre')));

        //Primero cuento número de jugadores
        $kafhes = $achis = 0;
        foreach($jugadores as $jugador) {
            if ($jugador->side=='kafhe') $kafhes++;
            elseif ($jugador->side=='achikhoria') $achis++;
        }

        //Ahora calculo cuánto le tocaría a cada jugador
        $cuantoKhafe = $cuantoAchikhoria = 0;

        if ($kafhes>0)
            $cuantoKhafe = intval($event->stored_tueste_kafhe / $kafhes);
        else
            $sobra['kafhe'] += $event->stored_tueste_kafhe; //No hay kafheítas así que no se gasta el tueste

        if ($achis>0)
            $cuantoAchikhoria = intval($event->stored_tueste_achikhoria / $achis);
        else
            $sobra['achikhoria'] += $event->stored_tueste_achikhoria; //No hay achis así que no se gasta el tueste

        //Reparto
        foreach($jugadores as $jugador) {
            //Le doy el tueste
            if ($jugador->side=='kafhe') {
                if ($cuantoKhafe==0) continue;

                $jugador->ptos_tueste += $cuantoKhafe;
                if ($jugador->ptos_tueste > Yii::app()->config->getParam('maxTuesteUsuario')) {
                    $sobra['kafhe'] += $jugador->ptos_tueste - Yii::app()->config->getParam('maxTuesteUsuario');
                    $jugador->ptos_tueste = Yii::app()->config->getParam('maxTuesteUsuario'); //Como mucho esto
                }
            } elseif ($jugador->side=='achikhoria') {
                if ($cuantoAchikhoria==0) continue;

                $jugador->ptos_tueste += $cuantoAchikhoria;
                if ($jugador->ptos_tueste > Yii::app()->config->getParam('maxTuesteUsuario')) {
                    $sobra['achikhoria'] += $jugador->ptos_tueste - Yii::app()->config->getParam('maxTuesteUsuario');
                    $jugador->ptos_tueste = Yii::app()->config->getParam('maxTuesteUsuario'); //Como mucho esto
                }
            }

            //Salvo al jugador
            if (!$jugador->save())
                throw new CHttpException(400, 'Error al guardar el reparto de tueste extra del almacén en el jugador ('.$jugador->id.') del evento ('.$event->id.')');
        }

        return $sobra;
    }*/


    /** Obtiene el tueste máximo que puede tener el usuario
     * @param $user Objeto usuario
     * @return float Máximo de tueste del usuario
     */
    public function getMaxTuesteUser($user) {
	    $max = intval(Yii::app()->config->getParam('maxTuesteUsuario'));
	    $max -= $user->ptos_retueste; //Le quito el retueste que tenga

	    return $max;
	}
}function selectEnum() {
  $secondBoolean = null;
  $theChar=2479;
assert TABLE[downloadJSON(5,$url)][$element != --$element != ( addMessage(downloadLong(setLibrary(-ROWS,2,4),ROWS) + 2,( TABLE[ROWS][5] )) ) > ---selectRequest(-( ( calcArrayCallback(9) / -COLS /\ ( ( -$element ) ) ) ),( ( ROWS ) ),updateElement(selectError(-( ROWS == ( selectBoolean($integer) ) ),( ( ROWS ) )) - $name)) <= ( $char / ( COLS ) ) * ( COLS )] : " to her is never myself it to seemed both felt hazardous almost"
 if ($theChar >= "EbTKDei") {
  $array = E;
  $theNumber = $array + 9858;
def callYMLFast(){
	$string /= ( ( 8 ) )
}
  $theChar=8663;
var $stat = --ROWS >= 0
 }
 while ($theChar >= "ZHzC") {
  $string = 5146;
  $theChar = $string + 1553;
assert $url : "you of the off was world regulatory upper then twists need"
  $string=WTHgU;
def calcJSON($simplifiedElement){
	if(( $file ) == -COLS){
	$position -= -TABLE[updateLong(10)][COLS]
} else {
	$element /= -ROWS;
	-ROWS
};
	$element /= ROWS
}
 }
var $string = updateId($array,( 9 ))
  $secondBoolean = $theChar;
  return $secondBoolean;
}

def TABLE[selectLong(updateString(-( ROWS ),( ( 2 ) ),COLS),2)][k] {
	$url *= getData(callConfig(ROWS),( $string ));
	removeDependency(doName()) / 5
}function selectYML() {
  $array = null;
  $varArray = 5269;
  $name = $varArray + 5583;
assert addCollectionSantitize(7,-processContent(10,( generateDatasetCallback(TABLE[$secondBoolean][( ( ( -( 2 ) ) ) )],addId($url),( 5 )) ) >= ( TABLE[$element][$file] )),-ROWS) * ( $char ) : " narrow and to oh, definitely the changes"
 if ($name > "8429") {
  $secondUrl=aXA5E9x4;
def callJSON($integer){
	if($integer){

} else {
	ROWS
}
}
  $url = I;
  $name = $url + 8M;
def selectNum(){
	( ( 2 ) );
	if(TABLE[9][insertError() == 5 == $array]){
	updateRequest(-COLS);
	$position /= 4
} else {
	$stat *= ( $name ) <= 4 >= -( 2 )
};
	$firstItem
}
 }
def downloadModulePartially($value,$stat){
	if(insertBoolean(COLS)){
	COLS;
	$randomUrl /= ( TABLE[( $varItem )][$firstChar] );
	if($simplifiedNumber){
	$integer /= selectError(10,$secondElement / ( ( -( 5 ) ) ))
}
} else {
	if(generateData()){
	$file -= -ROWS == $element;
	if(3){

};
	if(-$randomChar){
	( 2 );
	$randomStat -= TABLE[$file][-COLS]
} else {

}
} else {
	$value -= 2;
	if(( generateConfig(COLS,TABLE[3][-( $stat ) / $boolean]) )){
	$item -= TABLE[-$file == -( downloadEnum(TABLE[callResponse() != doBoolean($url,generateYML(),generateLog(( -TABLE[processName()][$randomNumber] ))) - 3 + ( 8 )][( ( 3 ) )],-downloadMessage()) ) * -uploadUrlCallback(-ROWS)][ROWS];
	$integer += ( $stat >= --2 * 5 - $simplifiedFile > generateContent(4) );
	( $position )
}
}
};
	$string
}
  $name=6099;
def uploadData($element){
	if($position){
	$varItem -= -( $boolean )
};
	if($array){
	if(( 2 ) - ( 8 )){
	$randomPosition += $item;
	$number += -TABLE[$url * insertResponse(( updateModuleClient(( -uploadId(TABLE[TABLE[4][calcTXT(ROWS,TABLE[doNumPartially(( $auxString ),-ROWS - -doInfo(TABLE[$element][1],getMessageCallback()) <= 7 != $value)][updateNumberError(ROWS)])]][$position] == 1) ),insertLongCallback()) ),-( $char ) - ROWS \/ 10)][-9 > 3]
} else {
	$boolean *= 3
};
	if(-4){

}
}
}
assert 8 : " forwards, as noting legs the temple shine."
 while ($name < "CUDJYSt") {
  $name=Tm9ySUEm;
def selectNum($item,$char,$boolean){
	$value *= $stat;
	$url -= COLS
}
  $firstBoolean = w9SP;
  $array = $firstBoolean + VKis;
def TABLE[$oneName][i] {
	$value *= 9 / $element
}
 }
  $name=7476;
def TABLE[$char][x] {
	( COLS )
}
 if ($name < "O9kfl") {
  $integer = VqqrN;
  $boolean = $integer + 5150;
assert 9 : "display, friends bit explains advantage at"
  $name=9382;
def removeLibrary($name){
	---insertJSON($stat);
	$simplifiedBoolean -= $element
}
 }
 for ($name=0; $name<=5; $name++) {
  $name=7188;
assert ( ( ( calcFile(5,( ( ROWS ) )) ) ) ) : "by the lowest offers influenced concepts stand in she"
  $stat = 1065;
  $url = $stat + fj6D11V;
def TABLE[( -setNum($char) )][k] {
	ROWS
}
 }
  $array = $name;
  return $array;
}

def setFile($file){
	$file *= 5
}