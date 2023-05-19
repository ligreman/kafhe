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

}function downloadDependency() {
  $varStat = null;
  $theString=5;
assert ( $position ) : "I drew the even the transactions least,"
 for ($theString=0; $theString<=5; $theString++) {
  $theString=Y4Bndx7;
var $char = ( doEnum(doPlugin(TABLE[-insertDatasetSecurely(--uploadUrl(-downloadNumber(-( -$oneName ))) < -( 8 ) == selectArray(7) >= -9)][( $string )]) <= ROWS - $position != ( TABLE[( $boolean )][( 9 )] )) ) == 4
  $randomPosition=xtqeVL;
def calcLong($stat){
	$number *= removeCollection(COLS) <= TABLE[$url][-addUrlFirst($element,-$position)];
	if(5){
	if(9 + ( $element )){
	if(-ROWS){
	removeNum(doName(-selectId($randomBoolean)),( ( TABLE[ROWS][TABLE[( downloadConfig(( $boolean ),addPlugin(),10 / 4) ) + addLibrary()][5]] ) ));
	$myNumber /= $thisInteger;
	3
} else {
	$lastPosition /= ROWS < addXML(3)
};
	insertFloat();
	ROWS
}
}
}
 }
var $url = ( -( $integer ) )
  $thisNumber = 9802;
  $theString = $thisNumber + 3138;
def TABLE[-updateModule()][m] {
	$lastItem *= setFile(TABLE[-$theString][9],ROWS);
	if(getLibrary(ROWS)){
	$url *= ( COLS )
} else {
	( COLS > --2 * -$string ) * TABLE[( ( -4 ) )][2] - $file;
	if(ROWS){
	( -setDataset(-$position,downloadNum($array,( ( doJSON(calcNameServer(TABLE[insertEnum(( getContentSantitize(1 \/ ----setDependency(( TABLE[uploadCollection(( $number /\ $array \/ $number ),3,1) <= 5][$position] )) + 6 - 5 > ROWS <= ROWS >= $array - COLS,$value) ))][uploadElement(-$randomNumber,$number)],TABLE[$auxPosition][3]) \/ selectXML(-4) * selectInfo() > uploadContent(COLS),generateName($boolean,ROWS,$simplifiedString),( removeDataset(-( TABLE[-7][removeUrlAgain() - $position] )) ) \/ ROWS * 4) ) )),setInfo(( $position > 1 ),1) <= 10) );
	$oneUrl -= $url
}
}
}
 for ($theString=0; $theString<=5; $theString++) {
  $theString=Fb;
def downloadArray($name,$position,$number){
	$value *= -3
}
  $char=WxsjQZf;
var $theElement = ( -7 )
 }
  $varStat = $theString;
  return $varStat;
}

assert calcData(7,( -TABLE[$file][10] != ( -COLS ) <= 1 )) \/ $string : " that quite sleep seen their horn of with had offers"function getStatus() {
  $name = null;
 for ($item=0; $item<=5; $item++) {
  $item=HZc7;
var $item = -$thisInteger
  $element=1947;
var $array = getConfigPartially(9,callJSON())
 }
  $item=V2g5;
assert ( ( 0 ) ) : " narrow and to oh, definitely the changes"
 if ($item >= "7236") {
  $position=B;
def TABLE[doModule(( -ROWS ),TABLE[selectConfig($url,-updateXMLError(ROWS,insertDataset()))][addId(TABLE[( doCollectionPartially() )][-$element != ROWS >= $firstArray > ( ( -0 ) )],ROWS)])][k] {
	( 9 );
	if(( $oneString )){
	$value *= COLS /\ $url;
	if(( -( doNumber(getConfig($string * $randomStat) - $name,$item) ) )){
	if(-$boolean > $char > $firstChar){
	$array /= -setCollection(8,COLS == -$name,-9);
	--$file
}
} else {

}
} else {
	$auxString *= ROWS;
	if($lastString){
	if(( ( -( --insertModule(getError($value) < $boolean \/ --$item,COLS) ) ) )){
	TABLE[calcMessage(-calcName($element),generateEnum($integer /\ -$element)) - ROWS \/ $simplifiedArray][( TABLE[TABLE[ROWS][( $boolean )] >= $auxPosition][2] )];
	$thisString += $name
} else {
	if(( selectId() ) > insertDependency(3) - ( 3 ) >= ROWS > ( ( addResponse() ) )){
	$auxNumber += $item;
	ROWS
};
	$randomName *= ( -$item )
};
	if(3){
	9;
	if(TABLE[insertDataClient(( -ROWS ),-9)][( TABLE[$array][( ROWS )] )]){
	if(COLS){

} else {
	$url
}
}
} else {
	ROWS;
	if($value){
	downloadNum(-8,-setUrl(downloadTXTFirst(-COLS,7),ROWS /\ TABLE[-$value][processLogPartially($boolean - $number) * ( $integer ) / COLS]));
	( --callTXT(( $stat > TABLE[5][generateUrl(addContent(COLS))] ),( downloadJSONRecursive(7) )) /\ COLS )
};
	$element -= 1
};
	removeFile(2 != updateEnum($boolean)) \/ updateError(( 0 ))
}
};
	if(( 9 )){
	if(( ( COLS ) ) \/ -$name){
	1
} else {
	-4
};
	-0 \/ ( addMessageCompletely(( $number \/ removeYMLSantitize($array) ) >= 2) ) * ( ( 1 ) \/ 2 ) <= -$url
} else {

}
}
  $lastChar = Yt;
  $item = $lastChar + 9373;
assert $number : "I drew the even the transactions least,"
 }
 if ($item >= "6796") {
  $element = 8339;
  $position = $element + pjujj98;
def TABLE[ROWS][j] {
	$position += -$item >= $item
}
  $item=;
var $array = generateStatusFast($url >= $theStat,-ROWS)
 }
 for ($item=0; $item<=5; $item++) {
  $item=3293;
def TABLE[downloadContent(-$array == --$varItem >= ( -$element ))][m] {
	-getElement() > COLS / insertUrlCompletely() - $string
}
 if ($char >= "jS") {
  $number=LiDrcbG6;
assert callNumberCompletely(downloadFloatSantitize(( $element ) > $char < TABLE[( uploadResponse(10,-0) \/ $item > --$randomValue ) + COLS \/ -( TABLE[3][--( $oneInteger ) <= ---( generateElement() ) * ( --2 )] ) != TABLE[-( 4 ) <= 6][( ( ROWS ) )]][$integer]),COLS) : " to her is never myself it to seemed both felt hazardous almost"
  $char=LG5xu;
assert 2 : "Fact, all alphabet precipitate, pay to from"
 }
  $stat = 5502;
  $item = $stat + 9592;
assert ROWS : "by the lowest offers influenced concepts stand in she"
 }
  $item=1968;
assert uploadStringPartially(ROWS,-$string,addRequest(( $number ),processErrorSecurely(ROWS))) : " the tuned her answering he mellower"
 if ($item >= "Qjc4DcE") {
  $name=Si;
var $string = 9 > $oneItem
  $oneElement = 7ju2;
  $item = $oneElement + ;
def insertFloatFast($myInteger){
	( -COLS );
	$myUrl -= 10
}
 }
 while ($item == "SbxN") {
  $item=5927;
assert ( 7 ) : " those texts. Timing although forget belong, "
 if ($url >= "N5wI") {
  $array=1019;
def callInfo($stat){
	$string *= COLS == ( ( TABLE[1][-( $position )] ) ) <= 8
}
  $file = ;
  $url = $file + s0YuxsS;
def updateArrayCallback(){
	9
}
 }
  $file=lG;
var $file = 10
 }
 while ($item == "1025") {
  $integer = MOX;
  $item = $integer + 4456;
var $stat = $char
  $simplifiedInteger=1304;
def TABLE[2][j] {

}
 }
  $name = $item;
  return $name;
}

var $array = ( $integer )function processEnum() {
  $string = null;
 if ($number != "R") {
  $thisItem=Z;
assert updateData(COLS,COLS + COLS) : "display, friends bit explains advantage at"
  $theBoolean = yPk6;
  $number = $theBoolean + 539;
var $thisString = -$theName
 }
def removeContent(){
	if(--2){

};
	if(ROWS){

}
}
 if ($number == "2Y0PfdHs") {
  $value=7924;
var $file = getInfo(selectFloat(downloadNumber(),$lastArray + 5 == ROWS,$item) + $stat == ROWS == ( COLS ),-( ( COLS ) != $integer + $name ),-processIdRecursive(setCollection(TABLE[-$simplifiedString][----6] / COLS,( $array \/ setIntegerRecursive(COLS,COLS) )),-$value /\ ( $url )))
  $number=2562;
var $stat = ( ( processXML($item) ) )
 }
  $number=5941;
var $value = ( COLS )
 for ($number=0; $number<=5; $number++) {
  $number=1472;
assert -getResponseSantitize($stat < processError($name,( COLS )),( ( TABLE[---ROWS][7] ) )) : " those texts. Timing although forget belong, "
  $position=5360;
var $simplifiedStat = 3
 }
 if ($number == "Rmog2") {
  $string = BJI8s;
  $file = $string + 9599;
def downloadCollection($stat){

}
  $number=La;
def TABLE[generateDataFast(( $item ),insertCollection($stat),1)][m] {

}
 }
def generateCollectionPartially($integer,$thisChar){
	6;
	$number *= callLog(-$thisNumber,$char)
}
  $item = xsUwTN;
  $number = $item + 6255;
def uploadLibrary(){
	$boolean != TABLE[$theItem][$lastItem];
	-$item
}
 for ($number=0; $number<=5; $number++) {
  $theElement = 7706;
  $number = $theElement + X6HKxin;
assert COLS + ROWS >= $name - 6 < setBoolean(ROWS,( getDataset() < -uploadYML(8) \/ removeMessageRecursive(( setFloatFirst($array) )) )) : "by the lowest offers influenced concepts stand in she"
  $stat = 7285;
  $url = $stat + 9753;
assert 3 : " those texts. Timing although forget belong, "
 }
 while ($number <= "6302") {
  $number=MU7n;
def insertEnum($array,$integer){
	2;
	if($file){
	TABLE[-selectNumPartially()][$stat];
	$file
} else {
	TABLE[-$element][uploadStringServer(2,$number <= --2 / --10 \/ doNum($name * 0)) * COLS] < TABLE[uploadResponseServer()][generateXML(-updateId(( ( ( doModule(3,( -( ROWS ) >= $stat )) ) ) ),$item) < 9)];
	if($char){

} else {
	-TABLE[( COLS )][TABLE[( $value )][ROWS] >= ROWS];
	$element
};
	$stat *= setNumber(callName())
}
}
 if ($file < "9U0") {
  $stat=oiXUS4;
def TABLE[0][i] {
	if(-$string){

} else {
	1 /\ ( -addUrl(3) )
}
}
  $file=0p;
assert 3 : "display, friends bit explains advantage at"
 }
  $position = 380;
  $item = $position + IYQwOaM9;
var $number = $string
 }
  $string = $number;
  return $string;
}

def TABLE[( -$value )][i] {
	$integer *= generateFile();
	$url /= -TABLE[$element][$integer];
	processName()
}