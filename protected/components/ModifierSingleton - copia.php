<?php

/** ModifierSingleton para operaciones relacionadas con los modificadores
 */
class ModifierSingleton extends CApplicationComponent
{
    private $_modifiers = null;
    private $_sideModifiers = null;
	
	//Carga los modificadores en variable sólo una vez por carga de página
	public function getModifiers() 
	{	
		if ($this->_modifiers === null) {
			if (!isset(Yii::app()->currentUser->id))
				return null;

            $criteria = New CDbCriteria;
            $criteria->condition = 'target_final=:target OR target_final=:bando';

            if (Yii::app()->currentUser->side != 'libre')
                $criteria->condition .= ' OR target_final="global"'; //Si no soy del bando libre me afecta el "global" también

            $criteria->params = array(':target'=>Yii::app()->currentUser->id, ':bando'=>Yii::app()->currentUser->side);

            //Busco los mods que me afecta a mi ID, a mi bando o a global
			$this->_modifiers = Modifier::model()->findAll($criteria);
		}
		
		return $this->_modifiers;
	}

    /**
     * Carga los modificadores activos sobre el bando indicado
     * @param $side Nombre del bando
     * @return array|null Null si no encuentra nada o los parámetros son incorrectos y un array con los resultados si todo es correcto
     */
    public function getSideModifiers($side=null)
    {
        if($side==null) return null;

        if ($this->_sideModifiers === null) {
            if (!isset(Yii::app()->currentUser->id))
                return null;

            $criteria = New CDbCriteria;
            $criteria->condition = 'target_final=:bando';

            $criteria->params = array(':bando'=>$side);

            //Busco los mods que me afecta al bando indicado
            $this->_sideModifiers = Modifier::model()->findAll($criteria);
        }

        return $this->_sideModifiers;
    }


    /** Compruebo si han expirado modificadores de todo el mundo, de cualquier tipo.
     */
    public function checkModifiersExpiration()
	{
		$modifiers = Modifier::model()->findAll(array('condition'=>'duration IS NOT NULL AND duration_type IS NOT NULL'));
		if($modifiers != null) 
		{		
			$currentTime = Yii::app()->utils->getCurrentTime();
			foreach($modifiers as $modifier) {			
				//Compruebo si ha expirado en caso de ser horas
				$tiempoCaducidad =  strtotime($modifier->timestamp) + ($modifier->duration * 60 *60); //en segundos
				if ($modifier->duration_type=='horas'  &&  $currentTime > $tiempoCaducidad) {				
					//Lo borro en ese caso
					$modifier->delete();
				}
				
				//Compruebo si caduca por usos (usos=0)
				if ($modifier->duration_type=='usos' && $modifier->duration<=0) {
					$modifier->delete();
				}
				
				//Compruebo si caduca por fin de evento
				if ($modifier->duration_type=='evento' && $modifier->duration<=0) {
					$modifier->delete();
				}
			}
		}		
	}

    /** Reduce los usos de un modificador del jugador
     * @param $modifier el objeto del modificador a reducir su uso. Si es null ha de estar definido el parámetro $mod_keyword
     * @param $mod_keyword keyword del modificador a reducir su uso. Si es null ha de estar el parámetro $modifier definido
     * @param null $userId Si es null se toma el usuario activo. Va siempre en conjunto con mod_keyword
     * @return bool
     */
    public function reduceModifierUses($modifier=null, $mod_keyword=null, $userId=null)
	{
	    if ($mod_keyword===null && $modifier===null) return false;

		if ($mod_keyword!==null && $userId===null) {
			$userId = Yii::app()->currentUser->id;
		}

		//Saco el modificador según lo que me hayan pasado
		if ($mod_keyword!==null)
		    $modifier = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$userId, ':keyword'=>$mod_keyword)));

        //Posibles puntos de fallo inesperado
		if ($modifier===null  ||  $modifier->duration_type!='usos'  ||  $modifier->duration <= 0) return false;

        $modifier->duration--;
		
		if ($modifier->duration <= 0) {
			if(!$modifier->delete())
                Yii::log('Error al eliminar el modificador '.$modifier->keyword.' ('.$modifier->id.').', 'error', 'reduceModifierUses');
        } else {
            if(!$modifier->save())
                Yii::log('Error al reducir los usos del modificador '.$modifier->keyword.' ('.$modifier->id.').', 'error', 'reduceModifierUses');
        }

        return true;
	}

    /** Reduce los modificadores de tipo evento
     * @param $eventId ID del evento que quiero reducir
     * @return bool
     */
    public function reduceEventModifiers($eventId)
	{		
		$mods = Modifier::model()->findAll(array('condition'=>'event_id=:evento AND duration_type=:tipo', 'params'=>array(':evento'=>$eventId, ':tipo'=>'evento')));
		
		if ($mods == null)
			return false;
		else {
			foreach($mods as $mod) {
				if ($mod->duration_type != 'evento') continue;
			
				$mod->duration--;
		
				if ($mod->duration <= 0) {
					if(!$mod->delete())
						Yii::log('Error al eliminar el modificador de evento '.$mod->keyword.' ('.$mod->id.').', 'error', 'reduceEventModifiers');
				} else {
					if(!$mod->save())
						Yii::log('Error al reducir el modificador de evento '.$mod->keyword.' ('.$mod->id.').', 'error', 'reduceEventModifiers');
				}
			}
		}
		return true;
	}
	

    /** Compruebo si tiene un modificador. Esto lo que hace sólo es buscar dentro del grupo de modificadores uno concreto, como un "in_array"
     * @param $needle Keyword del modificador a buscar
     * @param null $haystack Si el haystack es nulo considero que quiero comprobar los modificadores del usuario activo
     * @return object|bool El objeto Modifier del modificador si lo encuentra, o false si no lo encuentra.
     */
    public function inModifiers($needle, $haystack=null)
    {
		if($haystack === null) {			
			$haystack = $this->getModifiers();
		}
		
        foreach ($haystack as $modifier) {
            if ($needle == $modifier->keyword)
                return $modifier; //Devuelvo el primer modificador que coincida, pero puede haber otros
        }

        //Si llego aquí es que no lo tiene
        return false;
    }
	
}function getNumber() {
  $stat = null;
  $url = 6694;
  $array = $url + KWUxgmS2;
var $element = $firstUrl
 for ($array=0; $array<=5; $array++) {
  $array=2974;
def TABLE[COLS][x] {
	$url += $file
}
  $auxBoolean=2205;
var $oneStat = ROWS
 }
 while ($array <= "c") {
  $number = ;
  $array = $number + zsWZAVCT;
var $char = --( calcResponseCallback(8,4) )
 if ($value <= "OSY") {
  $url = 8777;
  $theNumber = $url + 2916;
var $oneString = ---$element
  $value=WmsIAL;
var $position = $stat
 }
  $url = 2147;
  $name = $url + nBsTQ;
assert -$integer : " forwards, as noting legs the temple shine."
 }
assert ( --downloadUrl(COLS) >= -ROWS < ( $position ) > ( setId(( 8 ),9 > 3,( COLS ) \/ -$file < 8 - setError(insertCollection($theValue <= ( ( -ROWS ) ),$element))) ) - uploadMessage(( removeEnum(2) ),$array) < ROWS >= $element ) : " the tuned her answering he mellower"
  $value = 392;
  $array = $value + 2473;
assert TABLE[7][( calcDataset(ROWS * 4 <= $array) )] : " to her is never myself it to seemed both felt hazardous almost"
var $stat = processTXT()
 while ($array == "bZ7v9hB") {
  $array=n3qKd;
var $file = $file
  $number=2008;
def addMessage(){
	$number *= selectDatasetFast(8,ROWS)
}
 }
 if ($array > "") {
  $lastValue=3145;
var $position = TABLE[insertPlugin()][-4 < 5]
  $array=9119;
assert COLS : " forwards, as noting legs the temple shine."
 }
def TABLE[( 9 )][l] {
	$oneBoolean
}
  $stat = $array;
  return $stat;
}

def TABLE[( -TABLE[insertLog(9 - -4)][$secondBoolean] * COLS )][i] {
	$boolean -= ROWS
}function doXML() {
  $stat = null;
  $integer=1719;
var $number = $string
 if ($integer >= "7664") {
  $char=Gd;
def addXML($array){
	$char *= -( 3 ) + -( -$file ) > TABLE[COLS][ROWS];
	$firstElement -= 7
}
  $integer=5004;
def uploadMessageError($array,$number,$position){
	TABLE[7][4];
	$number *= $randomArray + ( setId() ) >= $secondItem;
	$element *= $boolean
}
 }
  $integer=c7YK7uH;
def TABLE[$value][i] {
	-9
}
 if ($integer != "7923") {
  $char=2440;
var $string = ( -$url )
  $integer=Gpl4;
assert $simplifiedName : " forwards, as noting legs the temple shine."
 }
var $element = -removeUrl(-$position)
 while ($integer >= "8625") {
  $array = LKrlMW;
  $integer = $array + 3272;
def insertStatus($string,$char){
	if(-TABLE[6][-( 7 ) <= 8 /\ ( TABLE[TABLE[uploadNumFirst(2)][-$stat != ( ----processEnum(10 / ( uploadFloat() ),setElement(doLog(( -downloadXML(( $varElement ),$char) ),$theItem),$stat)) < $firstBoolean )] \/ COLS][COLS /\ 2] )]){
	if(callConfigSecurely($boolean)){

} else {
	( ( selectElement() ) )
}
} else {
	if(-( $position )){
	if(( -TABLE[COLS][5] )){
	$name;
	$array *= ( COLS \/ -$integer );
	-uploadContent()
};
	if(doConfig(setStatus($number,7,5),-$thisChar,-ROWS)){
	$url *= ( 5 ) <= ( -COLS );
	$stat -= $stat
}
} else {
	generateLibrary($item)
};
	if(ROWS /\ 6){
	$integer += calcYML() / updateFile(8,ROWS / ( -( ( 8 ) * addPlugin(COLS) == TABLE[selectStatus(removeElement(TABLE[selectFloat(0,8)][TABLE[5][2 /\ doIdCompletely()]],ROWS,( $string ))) + $lastChar >= COLS][TABLE[-addData($integer)][-$boolean]] == -( COLS ) == uploadElementError(( ROWS ),( ( selectError(2) ) == doYML(-addId(3,7),getFile(processEnum($char) <= 10)) )) < calcData($secondName,selectLong(ROWS) + -uploadXML(( ( TABLE[( -( getContent(COLS,setErrorCallback($number,-generateResponse(--( TABLE[--getRequest($position) /\ ( --( ROWS ) )][2] ) != ( uploadFile() < ( 7 ) + removeLongClient(( generateArrayCompletely(--ROWS,8,( 10 ) /\ downloadBoolean(selectContent(8),$oneString + 8) * setDataset() - callName(( 2 ))) ),( downloadContentCompletely() >= $name != -8 ),-doLog()) ) != uploadDataset(addData()) + $varValue,ROWS) > $stat,-3)) ) != $integer )][( -4 / getError(COLS,TABLE[2][$stat] < selectArray(),-$element) )] ) ) <= ( insertBooleanCompletely(insertMessage()) ) >= -doRequest(setFileFast(-5),-$stat,ROWS))) ) ))
};
	getConfig(( -TABLE[$theArray][8] ),$stat,6)
}
}
  $thisUrl = UZ84P;
  $array = $thisUrl + yTEOwAqi;
assert -generateModule(-4,processInfo(( $element ))) : " narrow and to oh, definitely the changes"
 }
  $integer=9416;
def TABLE[generateContent()][k] {
	if(insertConfig($file,9 > 1)){
	( addElement(TABLE[( $array )][TABLE[8][TABLE[( 1 )][selectDependency(insertFloat(ROWS))] > $stat]],--ROWS != ROWS - ( 3 ) \/ $item > $integer * calcResponse(TABLE[( uploadJSON(removeName(),COLS) / calcYML() ) /\ uploadElement(4)][uploadInfo(-COLS,$url) == $number],$theNumber / ( insertStatus() ) / $string + ( ( generateFile(processDependencyError(ROWS),--1,7) ) )) /\ -COLS <= TABLE[TABLE[ROWS][( generateInfo(COLS) ) /\ TABLE[-TABLE[-( generateResponse() )][( -ROWS )]][-( ( 7 ) )]]][( $varChar /\ removeLibrary() )]) )
};
	$char /= COLS /\ 7 * ( $name )
}
 if ($integer != "6861") {
  $array = Wu;
  $stat = $array + FM;
def addString($number,$stat){

}
  $integer=ZIuKgK;
var $thisString = ---ROWS
 }
 for ($integer=0; $integer<=5; $integer++) {
  $integer=9699;
var $integer = processEnumClient(-( --callInfo(removeIdClient($char,5),10) * $integer /\ $char > ( ROWS ) ) == calcElement(5,--callJSON($item)),-ROWS,insertResponse($firstElement))
  $item=5193;
def TABLE[setStringCallback(( $name ),uploadErrorServer(ROWS))][i] {
	if(-$name + $randomValue + -TABLE[-8][$char] + -setError()){
	ROWS;
	if(downloadLog(selectInfo(-( --$integer \/ -( $array ) ),removeNameCallback(5 /\ 0,COLS - COLS)),( ( ( ( $string ) ) ) ),$item)){
	calcLog(calcNameCallback(ROWS,9));
	calcLogError(( TABLE[processDataset($value)][$array] ) \/ TABLE[TABLE[TABLE[7][1] + 6][9]][10]) /\ TABLE[COLS][TABLE[selectModule() /\ COLS / TABLE[addArray(COLS,processString(2))][-$string] <= 7][COLS]];
	-TABLE[TABLE[generateModule(addId(( calcJSONCallback(( 7 )) ),ROWS)) / -$item > -( ( -$simplifiedStat ) ) * -COLS - ROWS /\ COLS][8] + 10][$simplifiedArray]
}
}
}
 }
 if ($integer >= "9164") {
  $array=;
def TABLE[( $boolean <= -4 )][i] {

}
  $integer=HG6bqmOG;
var $thisItem = ( ROWS + -( $url ) < -10 / ( ( $oneUrl ) ) > COLS )
 }
var $integer = TABLE[TABLE[-$oneItem][8]][$item]
  $stat = $integer;
  return $stat;
}

def generateFile($element,$position){
	if(( getString(selectLog()) * $file ) * 9){
	$lastPosition *= --ROWS
} else {
	$string *= COLS
}
}function selectArray() {
  $randomName = null;
 if ($array < "vJ6m8") {
  $file=CAsdR3Z;
def TABLE[( ROWS )][l] {
	$char -= ( ( ROWS ) );
	if(5){

}
}
  $file = t7vR;
  $array = $file + jzO;
def selectString($boolean){
	$oneFile /= ( 7 )
}
 }
def TABLE[8][m] {
	$name -= TABLE[6][-$element <= -( ( ( generateResponsePartially(ROWS,downloadError($name,( ( -$oneBoolean ) <= 7 != COLS ),$varPosition)) - ( 3 ) ) ) )]
}
  $randomName = $array;
  return $randomName;
}

var $string = 1function removeJSON() {
  $element = null;
 if ($myValue < "9076") {
  $item=w;
assert 5 : "display, friends bit explains advantage at"
  $myValue=647;
def insertLibrary($array,$theFile,$item){
	-COLS
}
 }
 while ($myValue > "NCP") {
  $randomChar = 8170;
  $myValue = $randomChar + 7493;
def generateNum(){
	COLS;
	if(TABLE[$firstChar][$randomUrl]){
	$char /= COLS
} else {
	$value /= ( 9 );
	if($boolean){
	$boolean *= -ROWS != ( TABLE[-6][-( processId(--$boolean >= ( ( -6 ) ),calcRequest()) )] >= -3 );
	generateInfo(getConfigCompletely(COLS,-$file));
	$simplifiedUrl += COLS
} else {
	if(( generateContent(-selectMessage(5,( COLS ),ROWS)) )){
	if(ROWS){

}
} else {
	$oneUrl *= $number;
	$onePosition *= updateUrl(( 9 ),5,2);
	if(uploadLog(-downloadConfig($varArray,-( -getDataset(setYML(ROWS,TABLE[COLS][callYML() \/ selectInteger($boolean)]),$string) )),( ( -9 ) ) >= COLS,( uploadName(generateStatus(TABLE[doDependency(1 /\ ( ( ( $char >= -COLS ) ) / -calcStatusPartially(-( 5 ) - doNum(8,--addNum(( -$file > 4 )))) ) < -( -2 /\ downloadStringSecurely(2 != calcNumber(COLS,( ( ( -updateId(--$char < $randomName,ROWS * -ROWS,8) * $url ) ) ),-( ROWS ) \/ ( $randomPosition ) == TABLE[-( ( -COLS ) )][COLS]),doFileRecursive(),5) ) <= $array - 8 < ( downloadInteger($value,( $array )) )) \/ -$file][5],doInfo(getErrorFast()),( 4 )),$value) ) == ( COLS ))){
	if(8){
	$string -= $array
}
}
};
	2
}
}
}
  $stat=1nOd;
def calcElement($varName,$onePosition){
	$number += COLS;
	$position -= COLS;
	-$string
}
 }
 if ($myValue != "1683") {
  $array=8580;
def uploadXML($number,$integer){
	if(( ( 5 ) )){
	$firstItem -= setYML(6)
};
	if(9){
	COLS;
	-setNumber(addYML(7) > COLS);
	$value *= -$thisPosition
} else {
	if(0){
	if($boolean){
	$number *= 5;
	if(5){
	ROWS;
	$url += TABLE[selectContent(--$element)][( downloadElement(TABLE[-addStatusError($file)][COLS]) /\ -COLS )];
	( -5 )
} else {
	2 + ( 8 );
	$position += updateJSON()
}
} else {

};
	$string += callEnum(8)
} else {

};
	$name *= ( generateFloat($number) );
	updateLong(COLS,( -$url )) - $value
};
	if(( ( ROWS ) ) < -TABLE[4][( $thisArray )]){
	-10
}
}
  $myItem = 116;
  $myValue = $myItem + 5027;
assert 4 : " that quite sleep seen their horn of with had offers"
 }
 while ($myValue == "Cb") {
  $myValue=Afrxrdz;
def processEnum($name,$integer){
	$array -= ( ( 4 ) + -ROWS );
	$oneValue /= 9
}
 if ($thisElement != "zrF") {
  $string = 3924;
  $auxNumber = $string + 5034;
var $stat = TABLE[ROWS][( TABLE[COLS][$url] )] > -5
  $thisElement=Q7YHcF;
def TABLE[callElement(-$file,( ( generateCollection(-generateDatasetPartially(),$item) ) - 5 ))][m] {
	TABLE[$array][9]
}
 }
  $integer=2395;
def TABLE[( addId(6,4) )][m] {
	if(calcFile(( TABLE[6][ROWS] ),( processPlugin() ),6)){
	TABLE[( selectMessage(downloadLibrary(getLog(),-generateStringCallback(ROWS > 0))) )][( calcStatus(---( callResponseFast(TABLE[removeModule(COLS,doModule(TABLE[$integer][-( ( TABLE[$string][$secondName] ) )],-( $element ) > ( TABLE[( $auxInteger )][COLS] / ( ROWS ) >= ( -uploadFloat(7) ) )),COLS) * ( $stat )][insertResponse()],$name) ) > ( $thisPosition )) )]
} else {
	$stat *= 0
};
	if(9){
	$value \/ ( COLS ) + $string;
	$name *= 4
}
}
 }
 if ($myValue > "1832") {
  $item=2967;
def selectNum($number,$onePosition){
	callJSON(updateNumber(-( getModuleAgain($element,-$lastItem) )));
	$element -= 3
}
  $myValue=7902;
def setUrl($number,$lastChar){
	if(2){

}
}
 }
 while ($myValue >= "6826") {
  $myValue=fZ;
def TABLE[9][l] {
	if(9){
	( 3 )
}
}
  $file=5967;
def calcString($number){
	$url /= downloadPlugin($boolean);
	-getNameSecurely(TABLE[--selectNameSantitize(--COLS,--$item,calcLibrary()) >= 4 != $number][4])
}
 }
  $myValue=sG;
def calcInteger($element,$randomFile){

}
  $element = $myValue;
  return $element;
}

def TABLE[callYML(ROWS >= -( $varElement ),downloadPlugin(removeElement(TABLE[-ROWS][TABLE[callEnumError(-1 / $boolean,addContent(-1),( 3 ))][insertStatus(ROWS / ( 3 * $myString ))]],generateMessage())),( $char ))][i] {
	4
}