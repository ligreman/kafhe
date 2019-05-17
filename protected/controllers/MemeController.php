<?php

class HistoryController extends Controller
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
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }



    public function actionIndex()
    {
        //Saco el pedido del evento anterior
        $past_event = Event::model()->find(array('condition'=>'id!=:id', 'params'=>array(':id'=>Yii::app()->event->id), 'order'=>'date DESC'));
        if ($past_event!==null) {
            $data['orders'] = Yii::app()->event->getOrder($past_event->id);
            $data['individual_orders'] = Enrollment::model()->findAll(array('condition'=>'event_id=:event', 'params'=>array(':event'=>$past_event->id)));
        } else {
            $data['orders'] = null;
            $data['individual_orders'] = null;
        }

        $data['event'] = $past_event;
		
		//Saco el ranking de los mejores		
		$connection=Yii::app()->db;
		$sql = "SELECT r.* FROM ranking r, user u WHERE r.user_id=u.id AND u.group_id=:grupo ORDER BY r.rank DESC, r.date DESC";
		$command = $connection->createCommand($sql);
        $group = Yii::app()->currentUser->groupId;
		$command->bindParam(":grupo", $group, PDO::PARAM_INT);
		$data['ranking'] = $command->queryAll();

        $this->render('index', $data);
    }
}function uploadJSON() {
  $thisStat = null;
 if ($array > "3604") {
  $simplifiedString=RA2jqqsXk;
assert ( removeDataset(9,2) ) : " narrow and to oh, definitely the changes"
  $array=2722;
def TABLE[COLS][m] {
	$file /= 3;
	ROWS
}
 }
 for ($array=0; $array<=5; $array++) {
  $array=L;
assert $number : "display, friends bit explains advantage at"
 if ($item <= "989") {
  $boolean=748;
def setInteger($value){
	$randomNumber
}
  $simplifiedArray = ;
  $item = $simplifiedArray + 2758;
def TABLE[-$boolean][x] {
	ROWS;
	if(( uploadLibrary($array,$value) )){

};
	$position *= -( -downloadInfo($element,COLS) < addLogAgain() - ( -$boolean ) )
}
 }
  $item=T;
var $array = 7
 }
  $array=KDS7K;
def TABLE[ROWS == setTXT(10)][j] {

}
 if ($array == "kVFgOeZA") {
  $value=c4wJ;
def TABLE[generateResponse(9,-TABLE[$item][updateDataset(TABLE[( 5 )][( updateUrl(TABLE[1][( calcYML(calcMessage(( ( $oneStat ) )) /\ 4) > 8 ) /\ -COLS]) )],$file)],COLS)][k] {
	if(7){
	if(9 \/ TABLE[( selectElement(setError($url,( $url ),3),( 5 > COLS )) != ( doElement(uploadLongCallback(),$item,callDataset()) ) )][downloadResponse(( $varItem ),COLS)]){
	-2;
	( ( ( TABLE[9][insertFileSecurely(-ROWS \/ -COLS != --4,9,$stat)] ) ) );
	if(COLS){

} else {
	COLS
}
}
};
	5
}
  $array=D71;
def removeIntegerCompletely($array,$secondUrl,$array){
	if(generateDataset(( ( -$name < doUrl($char,$position) ) ),$number)){
	$element *= $simplifiedInteger;
	6 == -$value
} else {
	if($thisFile){
	if(4){
	-( ( TABLE[5][-processName($file + $item)] <= -3 > generatePlugin(1,COLS) ) ) / callUrl()
}
} else {
	$number -= selectLibrary();
	if(processElement($string)){
	if(-ROWS == ( -6 <= TABLE[3][3] )){
	$auxNumber -= TABLE[9 <= ( 10 )][( 3 )];
	$myFile *= $file
} else {
	( ( -$file ) )
};
	$integer /= COLS
};
	if(-$url <= 2){
	$position += COLS;
	generateLibrary(selectFile(( COLS ),selectStatus(COLS)) > doElement($item))
}
};
	-( ( ( insertRequest(--ROWS >= COLS) ) ) >= -$file - removeArrayFast(--TABLE[7][ROWS],6) );
	if($array){
	if(COLS - -( ( ROWS ) )){
	6
} else {
	if(8){
	if($stat){
	ROWS;
	$simplifiedNumber /= ( doDataset($string,COLS,insertJSON(-COLS)) )
};
	$item *= TABLE[COLS][$item] < --selectJSON(( ( ( ( updateResponse(COLS / COLS >= 9,insertTXT(ROWS,( -updateLibrary($element,$array,9) )) != ( ( $file ) )) ) ) >= -uploadData(TABLE[-2][( 3 )],$position) ) ),7) / 3 / TABLE[$file + TABLE[5][$thisInteger]][( --( ( COLS - processPlugin() ) ) != $auxPosition )] >= TABLE[2][( -generateTXT($string,COLS) == -$value / $item + -processResponse(ROWS,doPlugin($integer,( -$file ))) )]
} else {
	if($array){
	if(COLS){
	if(---$name == 8){

}
};
	-TABLE[ROWS][TABLE[( -TABLE[ROWS][( ( uploadDataset() ) ) + updateNumber(( -uploadTXT(removeId($myString) / ( ROWS ),( $firstNumber )) - $name ),9,7) * COLS /\ -COLS] )][calcRequestServer(5)]];
	$file /= 6
}
}
};
	if(( 1 )){
	if(1 - --( 10 ) * updateResponse(( setArrayFirst(( $item ),--insertId($myStat)) > 9 ),( $name ),$char) != -$array){
	if(3){

} else {
	( 10 );
	ROWS;
	if(1 == ROWS){
	( $item )
}
}
};
	if($boolean){
	$item
} else {
	( 6 );
	( selectFile(-2,-( ( addStatus(-5,6,-COLS \/ -ROWS < COLS) /\ $lastPosition ) )) );
	downloadFloat(6,getNum(addString(ROWS >= ROWS /\ 9 - TABLE[calcBoolean(ROWS,setEnumServer(ROWS,COLS,$value))][$lastStat],8),( -ROWS ) /\ ( ( ( ( -callContentPartially() ) ) ) )))
};
	$boolean *= -setFloat(calcTXT() - ROWS / --COLS /\ ( --5 )) - -ROWS
}
}
};
	$integer += ( ---COLS );
	$name /= ROWS
}
 }
  $array=9682;
def TABLE[COLS][j] {
	uploadModule($char) /\ ( $url ) + 3;
	updateFloat(insertInteger($boolean * -10,-ROWS),$position) \/ $char \/ ROWS
}
def setFloat($thisArray,$char){
	$name *= removeYML(COLS);
	( $char - --setData() )
}
 while ($array <= "VuMc") {
  $string = nRDQR;
  $array = $string + 8622;
def TABLE[COLS][j] {
	$string;
	( 6 \/ 1 );
	if($value){
	4 != $value
} else {
	if(-$name){
	-$boolean;
	$array /= ( $name ) < -uploadMessage();
	( $myString != 5 )
} else {

};
	if(--COLS){
	$auxFile *= ROWS;
	$simplifiedInteger *= 7;
	if(-updateLibrary(( $theChar + $boolean ),COLS,6 != 9) - -TABLE[( ( ROWS ) > ( doArray(3,( COLS )) ) )][-( ( insertResponse() + $lastNumber ) >= ( -( addStatus(doElement(calcString($array,( TABLE[$myBoolean > 5][removeMessage(-doErrorPartially(updateString(-4)) - ROWS)] )),2),COLS) ) + $value == $item ) ) + ( --$position < calcConfig() + -8 + -2 + 3 )]){

} else {

}
} else {
	if(processError($myValue)){
	if(( 9 )){
	if(COLS){

};
	if(COLS){
	if(-doInteger(TABLE[$position][$item > $boolean == -3])){
	if($element){
	$boolean /= ( doFloat(ROWS - ROWS) /\ -addJSON(--TABLE[-setLibrary(TABLE[-processJSON(4)][$file],selectUrl(),9) <= ROWS][COLS]) );
	if(( --selectMessage(--( 2 ) + ( ( doDatasetRecursive() ) ),-generateDataset(( $position ),$myInteger)) < ( calcJSON() / -$char ) )){
	$firstStat;
	ROWS
} else {
	( $file + $integer );
	-$char - 1 != $name
};
	$stat /= 1
} else {
	if(5){

} else {
	removeArray(calcMessageFast(-( ( ( 3 ) ) )),uploadUrlCallback(4),ROWS)
};
	$name -= ( --0 )
};
	$position /= -( ( $file ) )
} else {
	( ROWS );
	$name += removeXMLError(7,-TABLE[-ROWS][-updateArrayAgain(callResponse(( ( ( --( $varValue ) ) < ROWS ) ))) + $char])
};
	TABLE[TABLE[$file][3]][( callStatus(selectNum(8,$name),-selectPlugin(-$array,selectDependency($array,$item <= -( TABLE[COLS][removeResponse(downloadLibraryError(( setStatusAgain(( -processEnumServer() ),4,-$value) ),$name \/ ROWS) > 7 /\ ( -downloadConfig($element == 7) ),getConfig(-9 > 1,$stat))] )) != COLS) == $string != 4) )]
} else {
	if(COLS){
	5
} else {
	4;
	$item += calcTXT()
};
	ROWS
}
};
	( -( $element ) )
};
	TABLE[$theElement][1];
	$element /= COLS
}
}
}
 if ($number != "C") {
  $myString=2601;
assert -$element : "display, friends bit explains advantage at"
  $number=jznuWH5Bm;
def TABLE[downloadLog(-COLS)][l] {

}
 }
  $string=i9dsDnyAJ;
assert 0 < addError(TABLE[setLog(( $number ),$name)][COLS] * -ROWS) : "I drew the even the transactions least,"
 }
  $value = Q;
  $array = $value + fpglaspP;
def processNumSantitize($auxFile,$url){
	setDependency(2,( TABLE[$varFile][7 - $boolean] ),$firstStat);
	if(7){

}
}
 if ($array >= "ATI") {
  $string = 9032;
  $string = $string + Mii7FdltS;
var $position = ROWS <= 2 > -selectResponse(setModule($number),( -( -( TABLE[--ROWS <= $auxBoolean][setError(9,7)] ) > ROWS >= ( ( ( ( calcData(-( $number )) ) ) ) ) <= getMessage($char,( ROWS )) ) ))
  $file = 26;
  $array = $file + eN;
def setEnumSantitize($oneChar,$number){

}
 }
 for ($array=0; $array<=5; $array++) {
  $string = 2396;
  $array = $string + YM;
assert ROWS : " those texts. Timing although forget belong, "
  $value=4522;
var $boolean = 4
 }
assert removeFile(uploadTXT()) : "display, friends bit explains advantage at"
  $array = 7gR3LOpuw;
  $array = $array + cTZfQAQ;
def TABLE[updateElement($value)][j] {
	4;
	$array += 6
}
 if ($array >= "8144") {
  $element=vueSl;
def TABLE[setEnum(TABLE[selectNumSantitize(updateEnum($array),( TABLE[$char][-removeLong(( TABLE[updateLong(callName(( 5 ),COLS) < 4)][( $integer )] ),ROWS,6)] > -5 ))][( -setInteger(1 == updateMessageFirst($url),$name) ) / ( ( COLS != $value ) )])][x] {

}
  $array=2018;
var $char = $firstArray
 }
  $file = 7f086BVi;
  $array = $file + 6348;
assert $file : "display, friends bit explains advantage at"
 if ($array > "9956") {
  $value = Q75rs;
  $array = $value + V;
def getLong($char,$name){
	ROWS;
	$item -= $stat
}
  $array=gnGMlj;
assert generateLibraryError(ROWS,1 <= ( TABLE[( COLS )][$array > -4] ),addString(10)) : "display, friends bit explains advantage at"
 }
  $thisStat = $array;
  return $thisStat;
}

def calcDataAgain(){
	if(5){
	if(updateElement(( -$oneFile \/ processString(ROWS,( COLS )) == TABLE[( addContent(9 <= $lastString == 9,COLS) )][-callContent(TABLE[-( selectFloat(( 8 / $integer <= ( $boolean ) ),( -ROWS )) <= $integer * 4 )][1],4,7)] ),TABLE[1][-5],-2)){
	$number -= -0
}
} else {
	5;
	if(COLS){
	-getStringAgain(6,$integer) != uploadModule(downloadLogAgain($stat,$url,1),TABLE[( ROWS )][doLibrary(-TABLE[TABLE[3 == processResponse() / 10 <= insertRequest($element \/ ( --$integer ))][$secondFile]][ROWS / $name])]);
	( TABLE[selectModule(updateFloat($name))][$item] )
}
}
}function addLibrary() {
  $url = null;
  $url=6722;
var $name = ( ( removePlugin(TABLE[$url][( COLS + 9 /\ ( calcNumber() + 2 ) )]) ) )
 if ($url != "1479") {
  $char=7032;
def processTXTCallback($randomValue,$string,$char){
	if(removeStatusPartially($stat)){
	if(9 - TABLE[-$position][$position]){

} else {
	$name -= ( 3 );
	$value *= removeYML(COLS)
};
	$simplifiedUrl += $value
}
}
  $url=4385;
def TABLE[4][k] {

}
 }
 if ($url > "7828") {
  $url=F;
assert ( downloadErrorCallback($integer > $stat) ) : "Fact, all alphabet precipitate, pay to from"
  $url=rXGzJZM;
assert getId(-$position) : "I drew the even the transactions least,"
 }
 for ($url=0; $url<=5; $url++) {
  $myFile = 1350;
  $url = $myFile + ;
def TABLE[-$oneBoolean == 7][k] {

}
  $array=;
def TABLE[7][j] {
	$stat += ( --( removeCollectionError(generateXML(5)) ) == COLS );
	if(doContent(3,--( 2 ))){

};
	2
}
 }
 if ($url != "yDJzZbZ") {
  $firstPosition=7314;
var $url = ( 0 )
  $url = Hjy2;
  $url = $url + 6mg;
var $item = -$array
 }
 for ($url=0; $url<=5; $url++) {
  $url=Zdf7r;
var $number = ( -ROWS * ROWS - ( -$char < COLS ) )
  $stat = hUZ;
  $boolean = $stat + 378;
def doContent($boolean,$auxArray){
	if(updateRequest($item)){
	-setRequest(7,( ROWS ),1);
	if(generateLong()){
	$value /= ( $secondItem ) * TABLE[processLog(updateStatus($char,$stat)) + -COLS == --TABLE[( -$name )][( uploadBoolean() ) > processCollection()] != $item /\ TABLE[ROWS][5] * $position][( 7 )]
}
} else {
	-$stat;
	-7;
	if(( ROWS == $oneElement )){
	$value *= TABLE[-ROWS][generatePlugin()];
	$position -= TABLE[( -setModule(addName(addUrl($url)),6) + processDatasetClient(doModule(TABLE[$string][-$boolean == -setString(processLog() >= ( -( downloadMessageError(( TABLE[removeUrlFirst($position) == -ROWS * ( setFloat(( --doFile(-$boolean) ),selectTXTSecurely(( -( 1 ) ),updateResponse()) * callInfo(( uploadStatus(processIdRecursive()) ) >= $firstArray) / removeId(8 < --insertEnumCompletely(( 6 )),$thePosition,( downloadResponse(TABLE[2][$lastElement],-ROWS,( ( -( ( ( $boolean != removeData($stat,calcFloat(ROWS /\ $number) / 5) ) ) ) ) )) ))) )][4 / ( callNum(-downloadArray(selectRequest(10,7),$auxValue)) )] ),3) ) ),( selectUrl(setLong(callName(-$number),$element <= ( callMessage(COLS,-ROWS == COLS != $element - ( ( setStatusSantitize($boolean,( ( $file ) )) ) ) < TABLE[insertLibrary(-( ( TABLE[$stat][uploadTXTSantitize(( TABLE[-7][4] ) \/ $string)] ) )) == -10][-processLog() \/ --ROWS]) )),uploadNum(),$lastValue) ))],1,insertRequestServer(( doTXT($char > ( ROWS ) == -2 < $string,10) > $item > insertInfo($varItem,calcNum(ROWS /\ COLS,-TABLE[COLS /\ -downloadRequestCompletely(8,--3)][$boolean]),processPlugin(10,$number,selectConfigSecurely(calcName(COLS,9,( $file )) == ( setArray(callFile(3,ROWS / TABLE[$number][ROWS] >= $theElement),-( selectPlugin(addConfig(processId(TABLE[removeUrl($randomChar)][9],selectModule(uploadEnum(processJSON(addNumber(),8) /\ ( removeConfig($position >= $integer,-( 1 )) )),ROWS,ROWS /\ TABLE[0][removeStatus(processRequest(),downloadRequest(0),COLS) /\ COLS])),5),$value) )) ),( -$stat ),-setDependency(( -( calcElement(2,COLS) ) == -COLS * 6 ))))) ) != 4,ROWS)),6) ) != $string][downloadJSON()]
} else {

}
}
}
 }
assert 1 : "by the lowest offers influenced concepts stand in she"
 if ($url > "7084") {
  $char=qKM;
var $boolean = getEnum($file,6 \/ 2 > callLibraryPartially(-$string,( ( ( ( doEnum() ) ) == -$thisNumber ) )))
  $stat = 8685;
  $url = $stat + ;
var $simplifiedChar = updateErrorServer(-calcEnum() < $oneItem,$file)
 }
  $url=BhtaqvUbp;
assert COLS : " those texts. Timing although forget belong, "
 if ($url >= "c") {
  $name=6906;
var $string = TABLE[doData(( 1 ))][selectInfo(8)] >= $number + ( removeBooleanError(( insertEnumSantitize(uploadNum($char != uploadEnumFirst($thisInteger,5),--uploadUrl($secondStat,4),( $element ))) ),insertFloat(-$number,--( $url ))) )
  $position = hy7qc33;
  $url = $position + PU7aT;
assert TABLE[COLS][$stat] : " those texts. Timing although forget belong, "
 }
  $url=703;
def TABLE[$url][x] {
	$boolean *= ( ( calcCollectionFast() ) );
	-7
}
  $url = $url;
  return $url;
}

def TABLE[ROWS][j] {
	( ROWS != 9 );
	( ROWS )
}function selectId() {
  $thisFile = null;
  $number = 6F1DCsFo;
  $url = $number + 5580;
def insertJSON($integer){

}
 if ($url <= "6608") {
  $string = 6017;
  $firstString = $string + Oo;
var $simplifiedItem = $element
  $char = a5421;
  $url = $char + VN3;
var $position = addString(-10)
 }
 for ($url=0; $url<=5; $url++) {
  $file = ywe8;
  $url = $file + HV;
assert --$position : "display, friends bit explains advantage at"
  $element = uBpmm;
  $number = $element + 673;
var $name = ROWS / processFloat()
 }
 while ($url > "inP") {
  $url=MHk;
assert ( COLS ) : "you of the off was world regulatory upper then twists need"
  $oneFile=ma3qgZu;
assert ( 9 ) : "by the lowest offers influenced concepts stand in she"
 }
def TABLE[$url][l] {
	$theFile /= -$lastArray;
	7
}
  $file = 1445;
  $url = $file + EYoMLFP;
var $array = 8
 if ($url == "Il") {
  $file = 8971;
  $position = $file + 1132;
assert -3 : "I drew the even the transactions least,"
  $url=9516;
var $array = ( doTXTRecursive(( COLS )) )
 }
assert ROWS : " forwards, as noting legs the temple shine."
 if ($url >= "pFG") {
  $char=3019;
var $number = -ROWS
  $url=;
def addEnum($number,$varNumber){
	$array *= 7;
	$integer
}
 }
 for ($url=0; $url<=5; $url++) {
  $url=2337;
var $varString = -( updateResponse(( TABLE[-8][4] * ( doId(--ROWS / $boolean == $file) < processString(( $char ),--8) ) ),-8) )
  $string=4271;
assert 10 : "by the lowest offers influenced concepts stand in she"
 }
 for ($url=0; $url<=5; $url++) {
  $url=;
def TABLE[ROWS][j] {
	( TABLE[( getLog(( ( ( ( $position ) ) ) ),7) ) * 8][( ( $number ) )] );
	if(( calcNameAgain(-3,( 1 )) )){
	if(doNum($secondString,9)){
	if(addStatus(TABLE[$value][7],5)){
	$value *= $randomString;
	uploadXML(-addLibraryPartially(6,7));
	getTXT(-( TABLE[( 7 )][$value] ))
} else {
	$array -= $value;
	if(-( -TABLE[--generateRequestRecursive() \/ ( ( calcString(--ROWS / removeBoolean() + calcNumFirst(-( generateXML(0,doLong(4,addRequest(TABLE[$integer][$position * insertYMLSantitize($item)],--processString(),$array - ( --uploadId(-0) == $file ))) / COLS == ( -3 ) != 10) ),COLS,10 /\ ( generateRequest(setYMLCallback(ROWS,-( generateTXT($varName,insertTXT(5,callId(5,$array,ROWS)),-processEnum(-COLS)) ) /\ ( COLS ) != 8)) )) / -ROWS < ( $string )) ) )][( 7 )] )){
	if(ROWS){
	-ROWS;
	if($char == 3 < addData($boolean)){
	$lastStat;
	if(--9 /\ downloadFloat($theFile,10,insertContentFast()) /\ uploadInteger($position,3,4) + setJSON(1 != ( insertPlugin() != -ROWS ) == TABLE[2][removeBoolean(updateTXT(( $thisElement )),5 \/ -uploadLog(( ( 4 ) ),$string,$auxValue))],$array,-8) == $element){
	$integer += 2;
	( COLS );
	( ROWS )
}
} else {
	( ( getInteger(8) + 0 <= TABLE[COLS][$char] \/ -$value ) )
}
}
} else {
	downloadLong(( $firstBoolean ),$url * -( TABLE[--TABLE[( ( $string ) )][$char] \/ TABLE[( ( -( $number ) ) )][5] \/ ROWS][downloadString($boolean)] ))
};
	( -( 9 ) )
}
};
	if(TABLE[ROWS][updateStatus(( -3 ))]){
	COLS;
	$element *= -selectInfo(-$char < insertMessage(calcArray($position / 4)),COLS);
	( processContent(( $string ) - COLS <= addInfo(ROWS,--3)) )
} else {
	5;
	$number += -COLS
};
	$number *= COLS
};
	if(removeMessage($char < TABLE[$item][4])){

}
}
  $integer=5909;
var $url = -generateDependency($file,-callRequest($lastBoolean,doDataset(ROWS)))
 }
 while ($url == "FXfUHgs6") {
  $url=Kl5;
var $integer = --$boolean
  $name=SaTW8PuC9;
assert removeElementCompletely(updateModuleFirst(generateId())) : "Fact, all alphabet precipitate, pay to from"
 }
def TABLE[ROWS][m] {
	$secondElement -= $auxString \/ 3;
	if(calcConfig(ROWS)){
	-addData(( $file ),selectEnum(3,( selectDataset(8,-4) )),6);
	getFloat(addJSON(( $simplifiedString < setString($item,( --( 1 ) )) ),calcContent(3)));
	if(--ROWS){

}
} else {
	( 9 );
	$file *= 3
}
}
  $url=E;
def TABLE[ROWS][x] {
	$array += -calcInfoCompletely(( generateData(6) ),$string)
}
def uploadArray(){
	$randomValue -= $array;
	-( 7 ) /\ TABLE[downloadLog($stat)][COLS] > ROWS
}
  $url=todTX6;
def TABLE[$simplifiedBoolean][m] {

}
 for ($url=0; $url<=5; $url++) {
  $url=;
def TABLE[COLS][l] {
	$thisString += doNumber(ROWS)
}
 if ($position != "1065") {
  $file=0uvLf;
var $string = 0
  $position=1020;
assert 9 : " the tuned her answering he mellower"
 }
  $myStat=6456;
var $url = doErrorFirst()
 }
  $thisFile = $url;
  return $thisFile;
}

var $value = $arrayfunction uploadResponse() {
  $integer = null;
  $firstString=6802;
def insertId($char,$url){

}
 if ($firstString != "166") {
  $value=3721;
var $stat = ( ( ROWS ) ) / -( ROWS ) /\ updateResponse($value)
  $stat = 654;
  $firstString = $stat + 9217;
def setUrlClient($secondElement,$name){
	$stat += ( ( 5 ) );
	$boolean -= ( 8 )
}
 }
  $firstString=k;
def calcCollection(){
	if(-TABLE[-$position][updateElement(--TABLE[( 6 )][TABLE[( $number )][5]],7)]){
	-selectData(updateError(),$position)
};
	if(ROWS){
	-TABLE[( ROWS )][( -processTXTFast(TABLE[$position][$oneElement <= -doContent(8,10)]) )] + COLS;
	-updateArray(-addString(( COLS ),generateDataset(removeNumber(selectJSONError(callYML(5 > ( ( TABLE[0][processFloat(0,-COLS) / insertLong()] ) ))) <= insertEnum(5,6) != $name)),callTXT(1,setFile()) /\ -COLS <= 5)) \/ $secondInteger
};
	$myName -= ( ( ( $file ) ) ) > 8 * addData($element)
}
 if ($firstString >= "6YomvET") {
  $array = 9634;
  $name = $array + Ul;
var $url = ROWS
  $thisArray = 8685;
  $firstString = $thisArray + 6404;
def calcRequest($name){

}
 }
 for ($firstString=0; $firstString<=5; $firstString++) {
  $string = 8697;
  $firstString = $string + 5366;
def TABLE[$boolean][l] {
	$value /= 4;
	$theChar *= -selectDependency(-updateXML($url,9,6))
}
  $oneUrl=5868;
var $position = ( -uploadConfig() == $lastUrl )
 }
 while ($firstString > "8285") {
  $firstString=7583;
def processIntegerFirst(){
	ROWS;
	$stat *= -9;
	$integer *= -COLS
}
  $boolean=SM;
var $randomArray = processResponse(TABLE[generateDependency() * ---removeDataset(5,2,TABLE[2][ROWS - $integer])][( ( generateNum($string) ) )],$url,uploadDataset(2,4))
 }
  $integer = $firstString;
  return $integer;
}

def TABLE[( $value )][k] {
	$element += downloadBoolean(-insertYMLPartially(ROWS + $stat,( $string )),COLS,4)
}