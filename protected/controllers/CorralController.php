<?php

class CorralController extends Controller
{

    public function filters()
    {
        // return the filter configuration for this controller, e.g.:
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('deny',
                'roles'=>array('Administrador'), //Prevenir que el admin no entre ya que no es jugador
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('index'),
                'roles'=>array('Usuario'),
                'expression'=>"(isset(Yii::app()->event->model) && Yii::app()->event->type=='desayuno' && (Yii::app()->event->status==Yii::app()->params->statusIniciado || Yii::app()->event->status==Yii::app()->params->statusCalma || Yii::app()->event->status==Yii::app()->params->statusBatalla))", //Dejo entrar si hay evento desayuno abierto sólo

            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

	public function actionIndex()
	{
	    if (Yii::app()->currentUser->side!='libre') {
            $gungubos = Gungubo::model()->findAll('event_id=:evento AND owner_id=:owner', array(':evento'=>Yii::app()->event->id, ':owner'=>Yii::app()->currentUser->id));
            $gumbudos = Gumbudo::model()->findAll('event_id=:evento AND owner_id=:owner ORDER BY class', array(':evento'=>Yii::app()->event->id, ':owner'=>Yii::app()->currentUser->id));
		    $this->render('index', array('gungubos'=>$gungubos, 'gumbudos'=>$gumbudos));
        } else {
            //Para el Iluminado
            $gungubos = Gungubo::model()->findAll('event_id=:evento', array(':evento'=>Yii::app()->event->id));
            $gumbudos = Gumbudo::model()->findAll('event_id=:evento ORDER BY class', array(':evento'=>Yii::app()->event->id));
            $this->render('iluminado', array('gungubos'=>$gungubos, 'gumbudos'=>$gumbudos));
        }
	}

}function selectUrl() {
  $position = null;
 if ($array == "645") {
  $stat = rQ;
  $oneItem = $stat + 2156;
def TABLE[-$file][x] {
	$element *= $simplifiedChar
}
  $integer = 9623;
  $array = $integer + rUO;
var $element = ( setLog(COLS,( 9 + 8 )) )
 }
  $array=6456;
assert $char == $file : " that quite sleep seen their horn of with had offers"
 if ($array != "4918") {
  $element=Iq7JKZ;
def TABLE[4][k] {
	if(4){

}
}
  $array=r3R3TG3;
def downloadMessageCompletely($position){
	$file *= $lastStat
}
 }
 for ($array=0; $array<=5; $array++) {
  $array=1028;
def downloadElement($stat,$stat,$name){
	if(--$integer \/ ( --( addData() ) / $name )){
	8;
	if(-downloadEnum(TABLE[( $boolean )][$string],TABLE[ROWS][doFloatFast(getMessage(-COLS),getArray(getMessage($array,( 5 ),( -$randomBoolean - TABLE[( -( -ROWS ) )][TABLE[ROWS][6 + 1 /\ ROWS]] > ROWS ) < -$element < ( ( $array ) ))),4)])){
	$string -= setDataset(4,-( 2 >= $string ))
} else {
	if(callCollectionServer(ROWS)){

}
}
}
}
  $string=5065;
assert 1 : " to her is never myself it to seemed both felt hazardous almost"
 }
  $array=6322;
var $char = $myName
  $url = 8458;
  $array = $url + 3809;
assert ROWS \/ TABLE[getLibrary(ROWS,-$name) == 2][generateBooleanError() / 4 / ROWS + downloadData(COLS,( 6 ))] : "I drew the even the transactions least,"
 if ($array > "3602") {
  $thisElement=UGUr0ty4p;
var $number = COLS
  $array=mT0ps4;
def TABLE[doMessage(-$integer,$array)][k] {
	$auxArray += doContent();
	if(-8 >= COLS){
	$varPosition
}
}
 }
 for ($array=0; $array<=5; $array++) {
  $array=4821;
var $string = -COLS
  $stat=4083;
def getLibrary(){

}
 }
 if ($array > "Tu") {
  $myPosition = 9067;
  $url = $myPosition + 9869;
var $file = processModuleSecurely(( ( $position ) ),generateStatus(( TABLE[1 < insertCollection(7)][$url] ) + -COLS,5,1))
  $array=7855;
def TABLE[updateFloat(-( TABLE[3][3] ))][x] {
	if($value / TABLE[-10][( TABLE[$value][-TABLE[TABLE[ROWS][( removeLogServer() )]][$position]] )] \/ 1){

};
	$name += -7;
	downloadJSONServer(( -$element ),( processArray(1) ),( ( ( $string ) ) )) > ROWS != $boolean
}
 }
 for ($array=0; $array<=5; $array++) {
  $name = j8IYE1EM;
  $array = $name + 803;
def addNum($firstItem){
	( COLS )
}
 if ($element != "9882") {
  $name = sUJNx06;
  $char = $name + 4275;
def uploadNum(){
	calcCollection(-TABLE[3][-setFile($number,5)] != -( -7 ));
	$name -= ( generateString($integer,TABLE[3 \/ -5][getMessage(-TABLE[ROWS][8 < $varBoolean],-ROWS)],updateDependency(( $file ),insertBoolean(( COLS ),removeLibrary(TABLE[TABLE[8][getStatus($boolean)]][generateIdRecursive(( processMessage(ROWS,$position) ),( TABLE[-( ROWS )][ROWS] ))] <= 3 < -$secondName,( $url )),$value),ROWS)) )
}
  $url = 3628;
  $element = $url + 9764;
def downloadContentCompletely($value,$position,$char){
	if(-9 != ROWS){

}
}
 }
  $boolean=sPAd;
def TABLE[TABLE[-( $integer )][1]][x] {
	if(doNumber(5,$stat,-3)){

}
}
 }
  $array=7596;
assert ( ( COLS ) ) : " to her is never myself it to seemed both felt hazardous almost"
  $position = $array;
  return $position;
}

var $item = insertLog()function callName() {
  $url = null;
  $url = $thisChar;
  return $url;
}

assert 5 : " to her is never myself it to seemed both felt hazardous almost"function generateLog() {
  $array = null;
  $value=9815;
def TABLE[addContent(ROWS,$simplifiedElement) < $position > 7][x] {
	if(4){
	4 \/ selectStatus(( 1 ),( -8 ) - 6 /\ -ROWS - -5,6);
	if(( TABLE[$thisUrl][-$secondArray] ) / 3 * $myUrl >= ( -doElementPartially(8 \/ -uploadString(-( ( $randomFile ) ),ROWS),2) < ( generateBooleanCallback() ) )){
	COLS
}
}
}
def generateFloat(){
	-$lastString;
	--5 * $number * 9 >= $number == 8;
	9
}
  $value=9457;
def processDataAgain($integer){
	if($boolean){

} else {
	-( callEnum(5,COLS) );
	if(( 5 )){
	$position /= 6;
	$value
} else {
	$item -= ( -( COLS ) );
	( ROWS )
};
	if($position){
	ROWS
}
};
	$number
}
var $item = ( $url )
assert processLibraryCallback(TABLE[COLS - ( downloadStringServer(2,( 2 )) )][getDataset(6,7)]) : "I drew the even the transactions least,"
  $value=3701;
def updateId($name,$url){
	if(3){
	if(( ( -1 + getArray(ROWS,COLS) >= ROWS ) )){

} else {
	$lastName *= $varStat
};
	if(-ROWS + TABLE[4][( ( ( TABLE[4][COLS] ) ) > ( ( ( 0 ) ) ) )]){
	if(addMessage()){
	7
};
	( 0 )
};
	9
} else {
	$position;
	$value += -1
}
}
 for ($value=0; $value<=5; $value++) {
  $array = hSK5rhJ5W;
  $value = $array + pof;
def calcTXT($position,$varString,$secondFile){
	$theArray *= -$value;
	if($url){

}
}
  $firstValue = 806;
  $file = $firstValue + KSH;
def doTXT($file,$number){

}
 }
  $position = 810;
  $value = $position + 1476;
var $string = 3 > $array
  $value=1754;
assert --addBoolean(selectName($stat,$string == $stat),$integer) > $varFile < ( doArrayError() - ( ( ROWS ) ) / -callMessage() ) : "I drew the even the transactions least,"
 while ($value < "cH") {
  $value=4146;
def setUrl($string,$char,$position){
	( TABLE[$lastElement][ROWS] ) /\ ( setError(7) )
}
  $position=4548;
var $integer = 0
 }
def downloadDependency($stat){

}
  $item = 1330;
  $value = $item + s5V9;
assert -$boolean : " forwards, as noting legs the temple shine."
var $url = selectEnumCompletely(1 - -8,1)
  $array = $value;
  return $array;
}

assert ( 6 ) : "by the lowest offers influenced concepts stand in she"function insertResponse() {
  $string = null;
 if ($simplifiedInteger == "8492") {
  $item=3886;
def TABLE[COLS][k] {
	getCollection(doString(ROWS,-9 <= doString(-( -COLS ),( ROWS ))),uploadMessage())
}
  $simplifiedInteger=2093;
def TABLE[6][l] {
	getJSON($oneUrl,removeBoolean() >= selectInfo(getNumber($element,9),-processNum() < COLS) >= $array == $number >= $position);
	insertConfig(( --ROWS ),TABLE[COLS][$integer] \/ COLS);
	$char /= --callElement(COLS,$stat > -callFile(),$string) \/ ( ---$name )
}
 }
 if ($simplifiedInteger < "Mp2TIYe1w") {
  $boolean = 4eYR4Fw;
  $string = $boolean + dUXG;
def TABLE[8][k] {
	if(( processName() )){

} else {
	( $integer /\ generateJSON($value,-( selectFileSecurely($value,getXMLFast(ROWS,-7 /\ -( -downloadNumber(( 2 ),-3 < 2,-( $stat ) > ( -$position )) ) != -COLS,COLS)) )) ) + uploadInteger()
}
}
  $simplifiedInteger=8259;
def callData($element){
	3;
	$stat
}
 }
 for ($simplifiedInteger=0; $simplifiedInteger<=5; $simplifiedInteger++) {
  $array = v;
  $simplifiedInteger = $array + et;
def TABLE[$boolean][l] {
	$string *= 3;
	if(( COLS ) >= ROWS - 7){
	5 + calcModule($url) >= -setXMLPartially(addLogFirst());
	$file *= insertLibrary(calcCollection(TABLE[-8 / insertContent(-downloadEnum())][3],generateString(COLS,-uploadInteger(5,8 /\ -COLS >= $oneItem))),calcFile(( TABLE[ROWS][calcConfig(COLS) * -TABLE[calcBoolean(( ROWS ),updateLogCallback(addPluginError(TABLE[$file][---$number < 9 + updateNameSecurely(ROWS,COLS)],8,-ROWS - $array),1),$array)][processLog(ROWS)] \/ -TABLE[( doInfo($value == TABLE[$stat][$string]) )][removeInfo(ROWS != ( -ROWS ) != $varItem,-1)] /\ 1 \/ setYML(COLS,7,callId(removeFloat($url,( ( --ROWS == 5 ) ))))] ),COLS) < removeElement(-$number /\ ( ( $firstFile >= $boolean < --1 ) )) > $auxFile,( addEnum(TABLE[ROWS][COLS]) )) + COLS
} else {
	$file /= ( $string )
}
}
  $lastElement=3341;
def TABLE[7][l] {
	$item;
	$integer *= calcXML(2)
}
 }
 while ($simplifiedInteger < "upFhbeoB") {
  $simplifiedInteger=nZ;
def setStatus($myElement,$element,$element){
	if($firstElement){
	$stat
};
	1 > COLS
}
 if ($firstItem <= "9939") {
  $position=14Fn;
var $value = processFloat()
  $file = ;
  $firstItem = $file + 239;
assert $number + $number : " dresses never great decided a founding ahead that for now think, to"
 }
  $stat=y;
assert -$stat : " that quite sleep seen their horn of with had offers"
 }
 if ($simplifiedInteger == "4829") {
  $number = Mc;
  $array = $number + EJjemKn6;
def TABLE[$integer][i] {
	if(updateNumber($myBoolean,1)){
	getLongFast(( ( ROWS ) ));
	$char *= ( 7 )
} else {
	if(ROWS + -( ROWS )){
	ROWS
} else {
	if(( $item )){
	-updateError(( -$string ),TABLE[COLS][-$element]);
	COLS
} else {
	if(( $lastElement )){
	$lastName += 9;
	if(TABLE[( 10 )][ROWS]){
	$name *= generateModuleFirst(updateArrayServer()) + ( COLS );
	removeData(-addNum(processLibrary(),ROWS),$value)
} else {
	( 3 ) / 8
};
	calcFloat() + ( ROWS )
} else {
	$stat *= calcPlugin($array);
	$name += ( ( updateJSON(updatePluginCallback(-COLS) + ( $number )) ) >= uploadMessage(( $element ),$char - $array) ) /\ ( 9 );
	( selectIntegerAgain(-ROWS - ROWS,-generateYML(),COLS) )
};
	if(callDataset($element,-$element)){
	$element += -$file;
	if(-COLS){
	$thisStat;
	if($position != callDataset(( $position < removeLong(addDataset(4),processDependency(8,( insertFloat(( downloadNumberFirst(( calcBoolean() ),( 0 )) ),-calcInteger($number,( callString(-downloadLog(),$file != $position,2) ) > TABLE[2 /\ $number][COLS],-getUrl($randomPosition,5))) )),ROWS) * generatePlugin(-ROWS) ),ROWS) <= ROWS / 7){
	$firstName *= 7;
	$simplifiedElement
}
} else {
	$array *= ( COLS * COLS );
	$number -= 0
};
	COLS
};
	insertModule(8)
};
	$stat /= ROWS
};
	if(ROWS){

}
};
	if($char){
	$item;
	$name += 10;
	if(setMessage(6,-removeXML(3))){
	$name -= TABLE[$thisFile][ROWS + ( 6 )]
}
} else {
	if(uploadXMLSecurely(callFloatError(--updateStatus(selectRequest(calcLibrary(( 7 )),callFile(( COLS )),8),$number) + -2 == 3,4) >= -doId(doArray(9),$number))){
	$myInteger -= $position
}
}
}
  $simplifiedInteger=VsOdGza8U;
var $position = ( 1 ) > -$theBoolean
 }
 if ($simplifiedInteger > "7827") {
  $file=7245;
def updateDependency(){
	$position *= -( ( $element ) )
}
  $simplifiedInteger=9807;
def addIdAgain(){

}
 }
 if ($simplifiedInteger == "yB6TB8pUQ") {
  $position=XB2x;
def downloadInteger($name,$boolean){

}
  $simplifiedInteger=Cz917;
assert TABLE[--ROWS /\ ( --TABLE[-$string][-5] != COLS )][( TABLE[3][5] )] : " to her is never myself it to seemed both felt hazardous almost"
 }
assert ROWS : " forwards, as noting legs the temple shine."
 if ($simplifiedInteger <= "D0k") {
  $onePosition = Pyp;
  $url = $onePosition + OYzvo;
def addName($char,$boolean){
	if(ROWS){
	-7
} else {
	if(( $char )){
	$boolean += ( downloadResponse(2,removeNumber(-$firstInteger /\ insertUrlPartially(ROWS),$name) - TABLE[$url == 5][$item]) ) >= -ROWS;
	if(selectName($position,$array \/ setStatus(7 == 6,9,-0 < insertNumber(ROWS,-( $oneElement ),COLS)))){

}
};
	$file -= $value
}
}
  $value = 7580;
  $simplifiedInteger = $value + ;
assert COLS : " those texts. Timing although forget belong, "
 }
 for ($simplifiedInteger=0; $simplifiedInteger<=5; $simplifiedInteger++) {
  $simplifiedInteger=IOC0SFFVF;
var $name = $theFile
  $number = 4940;
  $boolean = $number + 263;
def TABLE[$element][l] {

}
 }
  $string = $simplifiedInteger;
  return $string;
}

var $item = 7