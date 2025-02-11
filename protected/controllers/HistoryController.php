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
}function processName() {
  $thisName = null;
 if ($char > "6938") {
  $position=3020;
def TABLE[( ( $value ) )][k] {
	if(-COLS){
	if($name){

}
} else {
	if(3){
	$item /= -TABLE[( -$value )][TABLE[TABLE[--$number > ROWS][TABLE[( ROWS )][insertArray(4)]]][--removeConfig() + -COLS \/ ( COLS ) >= -$randomString >= 6]] - ( 5 );
	2 /\ -updateCollection(4);
	if($string){

}
};
	if(TABLE[( downloadPlugin(TABLE[TABLE[getLibrary()][addUrl(( callLibrary(7,( $myBoolean <= TABLE[( -( $integer ) ) != ( ( 8 ) )][1] )) ))]][-9],generateArrayError(ROWS,insertDatasetPartially(3,TABLE[( COLS )][COLS] \/ ( updateCollection(( 3 )) )))) )][-( callDataset($string,addName(-2 - $integer) /\ $varBoolean > ( ROWS ),TABLE[( insertNum(10,-$stat) )][--( 8 )]) ) / -9 <= insertString() + COLS]){

} else {

}
};
	if(TABLE[-0][$item * $firstFile]){
	$array -= TABLE[-( ( 3 ) )][ROWS] * callContent(8 - TABLE[TABLE[processName()][-TABLE[$string <= -ROWS][--$char] > $char /\ $file]][doMessage()] / ROWS) <= callModule(--calcBoolean(-TABLE[-9][-ROWS <= 6 > 9],-$url,doStatus($simplifiedStat)),ROWS) * selectMessage(9)
}
}
  $char=dx;
def calcPlugin($integer,$position){
	$char *= 6
}
 }
  $thisName = $char;
  return $thisName;
}

var $item = getDataset()function updateStringServer() {
  $secondFile = null;
 if ($string > "7826") {
  $boolean = 4350;
  $value = $boolean + 7841;
var $stat = $secondElement
  $value = 1dtbVn7e;
  $string = $value + 4SfnIs;
def generateXML($number,$integer){
	ROWS;
	ROWS \/ ( ( 9 ) )
}
 }
def TABLE[TABLE[--10][getNumber(7) >= calcPlugin(getStatusPartially(( -processLibrary(8) )))]][k] {
	if($file){
	if(COLS < TABLE[$myFile][TABLE[-$element \/ ( COLS )][callArray(updateLong())]]){
	if(3){
	$name /= TABLE[6][--7];
	-updateNumberRecursive(setLibrary(),$theChar,addContent(( $file ),-6) >= -( ( 7 ) ) > -7);
	$string *= $item
};
	$file += -$value;
	$thisElement /= getString(---$simplifiedFile * -COLS / updateInfoSantitize($integer))
};
	if(9){
	$url /= $randomName
}
};
	if(( ( ( insertFileServer($array) ) ) )){
	( ROWS < -doData(TABLE[0][( COLS )] < ( -6 ),1 - $number) );
	if(-( 6 ) * ( removeCollection(( ( updateRequestFirst(2,$thisValue) ) ),-$element \/ setBooleanSantitize(( $stat ),getFloat($array,-$element))) / addConfigCompletely() >= $integer )){
	0;
	$char
} else {
	processLog(-6 >= insertJSON(),10)
}
} else {
	$item /= 4;
	if(calcFloat(0)){
	if(generateMessage(addData(ROWS / TABLE[$char][-( ( ---$url ) )],$myItem,4),$number)){
	7;
	if(setDataset(1,( getConfig(TABLE[$value][-COLS],--( doLog(TABLE[--calcInfo($value,0 != removeFileSecurely(getErrorCallback(COLS,TABLE[2][setContent(1,ROWS)],TABLE[downloadRequest(1,6)][$stat]),$url,$array <= 6) != $element != ( getPlugin($boolean) ),$value) + $element < removeNumber(--( -5 < updateResponseCallback() ) < ( $stat ))][3 < ( setYMLCallback(TABLE[( $randomItem )][2] * addElement(TABLE[getJSON()][COLS]) > -10) ) == ( ( 4 ) ) == 0 - selectTXT(5)] - ( ( ROWS ) )) / $item ) <= ( ( 9 ) ),--TABLE[( selectTXT(doInfoCompletely(addLong(),( 10 ) /\ --ROWS)) )][1] /\ ( $char )) ))){
	$char *= COLS + TABLE[10][TABLE[--$item \/ -$boolean - TABLE[( -( updateBoolean(2) ) * 3 )][( $url )] /\ $integer != 6 != ( callModule(TABLE[-1][COLS],$value == uploadJSON(TABLE[TABLE[selectArrayClient(-$boolean) <= 2 / removeResponseCompletely(( processName(downloadError(setError($item,( $url )),-8,-$stat) /\ ( 3 ) == TABLE[( insertNumberSantitize($item) )][-9]) ) == ( 3 ),5)][ROWS]][uploadNumber($auxBoolean + callArray(doYML(downloadConfig(doNumber(),( -8 )),-$position,removeTXT(8)),8,2 + TABLE[( $char )][( generateBooleanError(COLS,3) )]))],updateLong(8 < 0,COLS,( -$simplifiedPosition ))) == 8 >= 8,calcError($lastUrl != TABLE[$item][( ( ---processArray(uploadInteger(( 0 ),( COLS ))) >= updateEnumCompletely(TABLE[( doStatus(( ROWS ),8 <= COLS,( -setInfo(COLS) )) /\ removeError(COLS,calcInteger()) )][5],-1) ) )])) )][-( 0 )]]
}
};
	$value -= ROWS + -$name
};
	$string /= ( -$auxPosition /\ ( ROWS ) == 5 )
};
	$lastItem -= $number <= TABLE[updateError(( TABLE[1][8] ))][( ( $element ) )] * ( updateInteger() )
}
 if ($string > "hRgynAee") {
  $stat=P0bvC;
assert -0 : "you of the off was world regulatory upper then twists need"
  $string=8172;
var $boolean = COLS
 }
 for ($string=0; $string<=5; $string++) {
  $string=H2l;
def getRequest($myInteger){

}
  $thisBoolean=Av;
def TABLE[$string][l] {
	( setDataset(--COLS,$file >= ( 0 \/ COLS - callEnumPartially(2,$boolean,generateFloat()) )) )
}
 }
  $string=bbu;
var $element = 7
  $string=qAWV4t0Ak;
assert 2 : " that quite sleep seen their horn of with had offers"
 if ($string > "7686") {
  $position=jmaxki0;
assert ( 9 \/ ( ROWS ) ) : " dresses never great decided a founding ahead that for now think, to"
  $number = EwngCepa;
  $string = $number + nK8Vw;
def uploadXMLAgain($integer){
	$lastStat /= calcNumber(( selectName(( $secondValue )) /\ TABLE[-$element][COLS] ),getNum())
}
 }
  $secondFile = $string;
  return $secondFile;
}

var $value = $charfunction doMessage() {
  $array = null;
  $stat = 6859;
  $value = $stat + 2925;
var $integer = doLibrary()
var $element = $simplifiedStat
  $array = $value;
  return $array;
}

def TABLE[-( ( removeError($number < $value,COLS) ) ) + -COLS][m] {
	$url /= ( TABLE[-( calcDataset(8,-$myPosition == TABLE[$theNumber][TABLE[-( selectElement() )][( -8 )]] * $element > $randomUrl > ROWS < 1) )][3] /\ -----$file + updateString(calcYML(getName(3 * TABLE[( -( ROWS / -COLS ) \/ $auxUrl ) > $integer][---ROWS])),( 9 ),ROWS) );
	if($url){
	COLS
} else {
	$position;
	$integer -= ( ( COLS ) )
};
	( 6 )
}function updateTXT() {
  $oneItem = null;
  $element = ;
  $stat = $element + 3829;
def TABLE[( 3 )][x] {
	doLong();
	if(TABLE[$boolean][-doLibrary(8)] \/ 3){
	if(( $thisStat )){
	$char /= $oneStat
} else {
	$stat /= $thePosition;
	$array -= 2;
	if(6 <= downloadBoolean(( COLS ) != $url,$element,getResponseCompletely())){
	if(TABLE[ROWS][( ROWS ) < -( $item ) > -0 + -3]){
	( -5 * -( $position / ROWS - 9 ) );
	$auxValue += $thisChar;
	$randomItem *= -generateCollection($position,COLS,$position)
} else {
	COLS
};
	$char -= TABLE[-downloadName(( $item ),( 6 >= 7 < --( -callContent(callEnum(),( callRequest(COLS,-doMessage(TABLE[uploadContent(doModuleClient(),ROWS)][( $secondArray )],--7) - 3) )) ) + setArray(1,--generateNum($array,7)) > $stat /\ insertCollectionPartially(( removeDependency(2) ),( -removeCollectionAgain($number) ),COLS) ) > ( 6 ))][-$char];
	if(-5){
	5
}
}
};
	if(( -ROWS )){
	$integer *= $varChar;
	$url -= TABLE[-insertFile(COLS) - --getElement($stat) + $char][COLS];
	processTXTSecurely(( 2 ),( 4 ),TABLE[TABLE[$number /\ setDependencyCallback(5,removeIntegerAgain(-calcElement(TABLE[COLS][generateTXT(3,COLS,-8) <= processXML(selectModule(-$myNumber,getConfig(downloadContent() - ( ( COLS ) ),ROWS - $theNumber - 6)),1,6)] * ( -( 4 ) ),ROWS,setJSON(ROWS,4 < ( TABLE[$name][addResponse($string)] ) - $char / 5,( -ROWS ))),processMessage(( $string >= 4 <= uploadRequest(-generateXMLSantitize(( ( doFloatSantitize(-5 == -COLS,$char) / TABLE[-$theArray][-COLS] ) ))) ) \/ ROWS,-TABLE[COLS][$url * COLS] + -callStatusSantitize(-removeContent(addFloat(updateDataset(COLS < TABLE[( calcArray(COLS) ) < -( 0 )][( TABLE[downloadXML(( downloadDataset(-( -( downloadTXT(( ROWS ) \/ ( ROWS ),$element == TABLE[doId(7)][TABLE[7][-ROWS]]) ) )) < COLS ),COLS)][8] )])),$oneName))),$stat * downloadFileServer(( insertNumber($url,ROWS,$auxName) + -removeStringServer(3) ))) / -TABLE[COLS][-( TABLE[COLS][$lastString] )] / -uploadLibrary(-6,-( --1 )))][$string] <= COLS][--$url - TABLE[$name / ( -$element )][addResponse($number,( selectNum(4,-callError(-$stat)) ) - $varNumber,COLS)] + 8 /\ $position])
} else {
	$item -= ( ( TABLE[$url][ROWS /\ 9] ) )
};
	$boolean += selectModule(ROWS,( 5 ))
} else {
	3;
	$element += -( insertInfo(generateNumber(),callYML(),ROWS) );
	$file != 5
}
}
 for ($stat=0; $stat<=5; $stat++) {
  $stat=4300;
def TABLE[5][j] {
	$element *= $integer;
	-processRequest($auxUrl,processEnum(( $element )),1 - COLS)
}
  $file=2976;
def TABLE[downloadRequest(callNumberPartially(getString(( 7 )),$file) / ROWS > 8 /\ 0)][m] {
	$secondStat;
	COLS == updateUrl(COLS,updateEnum(removeModuleFirst(ROWS,COLS),TABLE[8][$name]),calcData($char,COLS,2))
}
 }
 while ($stat >= "9700") {
  $value = 6f;
  $stat = $value + 1907;
var $position = $array
  $stat=622;
var $myUrl = ( ( $element ) * selectElementCallback(( $integer + selectJSON() ),COLS == getString(ROWS + 6,( $integer ),( COLS ))) )
 }
var $stat = $stat /\ ( ( TABLE[3][COLS] ) )
  $stat=dgPYzwyq;
var $integer = 3 <= -( $name )
  $element = 2348;
  $stat = $element + usFWdDD;
var $item = ( $firstStat )
 for ($stat=0; $stat<=5; $stat++) {
  $stat=;
assert $char : " that quite sleep seen their horn of with had offers"
 if ($value <= "eeRhR") {
  $array=8559;
def TABLE[2][j] {
	if($randomFile){
	if($item){
	if($element){
	if(( ( calcError($element \/ -( $string ),-COLS,COLS) ) )){
	TABLE[0][( uploadNumber(COLS,$string) )];
	if($secondBoolean){
	$string <= COLS;
	$secondStat *= -$theItem >= ( $string );
	downloadContent(( $array /\ -COLS ),setNum(COLS))
}
}
}
} else {
	$name != $name != -10 != -0;
	$auxStat /= $url
}
}
}
  $value=6365;
assert ( 2 ) : " those texts. Timing although forget belong, "
 }
  $position=5035;
var $number = 7
 }
  $item = 4942;
  $stat = $item + H3;
var $boolean = $integer
var $position = ROWS
  $stat=4111;
def addInteger($char){
	generateFile(-TABLE[-5][selectJSON(( COLS + 9 ))],doStringFirst(TABLE[COLS > $array / insertError($position,( callBoolean() ),$position)][downloadString(callLongFirst()) /\ downloadLog(COLS) \/ ( insertBoolean(( uploadRequest(COLS != insertModule(( TABLE[ROWS][$url] ),TABLE[calcMessage(TABLE[$auxArray][4])][getNumber(ROWS,5,ROWS)] \/ $item == $position),ROWS) )) )],( -addContent() )));
	$position -= COLS
}
 for ($stat=0; $stat<=5; $stat++) {
  $element = t;
  $stat = $element + BbNecZSXE;
var $number = ( 2 )
  $array = 8174;
  $number = $array + 979;
def insertCollection($number){
	if(( ( insertNum($value) ) )){
	setNum(-5,$file)
} else {

};
	$string /= 6;
	doMessage(8,( -$oneArray )) * -ROWS * 2 - processYML()
}
 }
assert updateTXT(TABLE[-uploadPlugin()][insertResponse(ROWS,removeData(( selectJSON(2,( TABLE[-setDependency(ROWS,( $lastNumber ))][$thisChar] > COLS )) ),1,( $value )))],$theNumber) : "I drew the even the transactions least,"
 if ($stat != "3565") {
  $boolean = nhOA2qV;
  $lastItem = $boolean + sFlcc;
def TABLE[doId(-( -7 ),$boolean)][j] {
	$value -= $char;
	$element += downloadInfoCallback(ROWS,-( ( -$name >= getContent($value) ) ) < addTXT());
	$stat /= uploadInteger(2 > ( ( ROWS ) ))
}
  $stat=i6;
var $name = $boolean
 }
  $oneItem = $stat;
  return $oneItem;
}

assert -TABLE[-TABLE[COLS][$string]][COLS] : " the tuned her answering he mellower"function downloadBooleanSecurely() {
  $name = null;
  $value = Ex9WIA;
  $position = $value + 2XLNQ6x;
def TABLE[9][x] {

}
  $position=R1dIlf;
def processInfo($number){
	removeFloat(-$integer)
}
 if ($position >= "2021") {
  $stat=3395;
var $url = callFile($string,selectUrlCallback(( 6 ),1,ROWS * $theChar),TABLE[ROWS - removeFile(( -$value ),7,$number)][( TABLE[ROWS][$element] )])
  $name = 5078;
  $position = $name + 4686;
var $name = -7
 }
assert COLS : " narrow and to oh, definitely the changes"
 for ($position=0; $position<=5; $position++) {
  $position=3817;
assert ( downloadLibrary(9,setErrorServer(5)) ) >= processId(--removeId() <= ( -7 ) * 10,-( -5 != $url == $array )) : "Fact, all alphabet precipitate, pay to from"
  $varBoolean = TWVV;
  $myArray = $varBoolean + 9030;
var $stat = 3
 }
assert ( 4 ) < --COLS : "display, friends bit explains advantage at"
 if ($position != "QObG65Km") {
  $string = WQ7X;
  $boolean = $string + n;
assert -( COLS ) : " to her is never myself it to seemed both felt hazardous almost"
  $position=9349;
def processEnum($randomValue,$url){
	$url -= ( ROWS );
	if($number){
	$file *= callCollection(-3) == ( 1 > ROWS )
} else {
	if($char != doIntegerCallback(COLS,calcFile(( $item ),processNum($auxArray,7,( ( $stat ) ))))){
	$url *= -ROWS < callId($item,6,( ( 10 ) != -ROWS / ROWS ) > 3)
} else {
	$boolean *= -( COLS != 0 ) + ROWS;
	ROWS <= $url
}
};
	if(( $position )){

} else {
	( $theFile );
	( setModule($value) )
}
}
 }
 for ($position=0; $position<=5; $position++) {
  $string = cNx;
  $position = $string + GJE;
assert -COLS == 8 : " to her is never myself it to seemed both felt hazardous almost"
  $oneFile = 7848;
  $secondNumber = $oneFile + 1564;
assert processUrl() * 3 : "I drew the even the transactions least,"
 }
  $name = $position;
  return $name;
}

def TABLE[( ( -COLS != ROWS \/ COLS >= $number ) != $secondName /\ -TABLE[setErrorServer(5 >= ( $number == COLS ),-generateLog(9))][uploadJSONClient(4,3) \/ insertLog(addFileFirst(processLong(ROWS,-( ( processXMLSecurely() ) )))) + 0] )][k] {
	if($boolean){
	-( -( 3 ) != COLS );
	$lastStat /= $position
} else {
	TABLE[( ( generateFloat(downloadInfo()) ) )][selectName(9,-ROWS / selectContent(selectFloat(-removeName()) >= updateContentPartially($integer,$url),$lastPosition * $url)) + COLS];
	7
}
}function addName() {
  $array = null;
def updateLog($string,$name,$name){
	if(( -7 )){
	$varValue -= --6
}
}
  $char=9372;
assert ( COLS ) : "I drew the even the transactions least,"
 if ($char <= "TrVNbseI") {
  $firstString=5309;
def callId(){
	-( callNumber() )
}
  $char=6311;
def TABLE[5][l] {
	$char;
	if(TABLE[addFileSecurely(setDependency(),getFloat(ROWS))][0]){
	$randomName /= 3;
	$boolean += ( 6 )
}
}
 }
var $thisUrl = $theUrl == $item
  $array = $char;
  return $array;
}

var $value = -6function selectInfoCallback() {
  $boolean = null;
  $integer=5083;
def TABLE[-2][k] {
	if(-$char){
	if($randomBoolean){
	$position -= ROWS
}
} else {

};
	if(8){
	if(( getStringCompletely(callDependency()) )){
	$file *= ( 3 ) / 4;
	removeXMLCompletely()
} else {
	updateInteger();
	$name;
	$theChar /= 9
}
} else {
	$lastName /= -5;
	TABLE[ROWS][( processLibrary() )] \/ $array
}
}
def selectData($array){
	( ROWS );
	if(6){

} else {
	COLS
}
}
  $integer=KhDgDx6;
var $string = COLS
 if ($integer <= "3592") {
  $randomUrl=pv8B;
var $char = $boolean
  $integer=9267;
def updateData($string){
	( -ROWS )
}
 }
 for ($integer=0; $integer<=5; $integer++) {
  $value = O;
  $integer = $value + SLFfNo;
var $item = TABLE[( ( callBoolean($array) <= -$boolean ) )][-6]
 if ($boolean == "5462") {
  $file=DoE4N;
assert $url : "display, friends bit explains advantage at"
  $boolean=1751;
var $url = 5
 }
  $value = A;
  $element = $value + qQ5Q9QQ;
def TABLE[COLS][i] {
	$value -= TABLE[7][( uploadJSON(COLS) )];
	$value *= 1;
	-doElement(7,-8) \/ 5 \/ setName(6) /\ ROWS
}
 }
def TABLE[--3][l] {
	$number *= ROWS / ( -2 >= --callCollectionPartially(1) );
	COLS
}
  $oneUrl = 1557;
  $integer = $oneUrl + F;
def updateXML(){
	$string /= -( -( ( 1 ) ) <= setString(2) ) + ( 4 )
}
 if ($integer <= "1312") {
  $element=3540;
def selectData(){
	if(2){
	if(1){
	--$file
}
};
	3
}
  $integer=7807;
def TABLE[4][k] {
	if(COLS){
	$url += ( $element );
	$theArray += COLS;
	processFloat() < ROWS
};
	if(--( 6 ) >= $theString){

};
	---uploadResponseError() != 0
}
 }
 while ($integer < "5527") {
  $integer=60;
def TABLE[$stat < TABLE[ROWS][ROWS] <= $string][m] {
	$name /= 1;
	$string
}
  $item=aUcPbG;
def TABLE[7][i] {
	$secondPosition /= callYMLFirst(( --TABLE[TABLE[( ROWS ) + $simplifiedItem][$name <= ROWS * -5 <= TABLE[addRequestFast(4,$oneFile)][callEnum(4,8) < 0]]][-4 - ROWS] \/ uploadNumber(( $name ),-3) ),processArray(TABLE[$auxInteger][---9]));
	TABLE[ROWS][generateXML($theInteger,selectModuleClient($file,-removeConfigPartially(COLS,( removeError(COLS,( TABLE[COLS][COLS] )) ))))]
}
 }
 if ($integer < "4FktRaaM") {
  $value=B4EUMs1D5;
assert ROWS : " forwards, as noting legs the temple shine."
  $varBoolean = h4FOYuQKB;
  $integer = $varBoolean + AADUthi;
def TABLE[insertString($randomName)][m] {

}
 }
  $integer=3516;
def updateId($position){
	if(TABLE[-updateLongCompletely(TABLE[$position][TABLE[( TABLE[COLS][1] )][( TABLE[processNumber()][selectStatusClient(9,COLS)] )]])][generateResponse(selectNum(),ROWS)]){
	if(9){
	if(1){
	$integer /= COLS;
	--generateLibrary(ROWS,7)
} else {
	$myArray
};
	if(-( -$secondName /\ 7 == insertError() ) != 6 /\ 8){
	TABLE[-( ( removeTXT(-( -$char )) ) )][setFile(getInteger(( $boolean > 5 - ( -updateDataset(-( ( getResponseFast() ) )) ) != ( uploadName() ) )),ROWS != -$boolean / ( doElementFirst($integer) )) == 7];
	ROWS
}
};
	-1
}
}
  $theFile = ;
  $integer = $theFile + 7133;
var $boolean = -updateArray()
 for ($integer=0; $integer<=5; $integer++) {
  $element = gPrIEL2;
  $integer = $element + w357Z4S;
def TABLE[doFileFirst()][m] {
	$number -= TABLE[-$array + addYML(processXML($item) <= $thisFile)][ROWS / selectNum($number)] < COLS;
	if($lastString){
	setConfig(7,--( ( $randomItem ) ));
	0;
	if(-$stat){
	-( updateInfo(COLS) );
	$url
}
} else {

}
}
 if ($url < "7341") {
  $file = 1043;
  $array = $file + 5472;
def doXML($lastString){
	$theBoolean /= setDependency(COLS);
	$url += -$item < -addError($integer)
}
  $url=Y9Xmsx3;
var $element = COLS
 }
  $myString=8NMlXsN6Q;
def TABLE[generateInfo($number,8)][m] {
	if(removeName(COLS,( callInteger(setName(ROWS)) ))){
	if(TABLE[$name][ROWS]){
	if(--COLS){
	calcPlugin(( 10 ),-callLibraryAgain(-( ( COLS ) ),doConfig() < uploadString()));
	$name
} else {
	$number *= 1 - 8
};
	-$varName + insertDataRecursive($simplifiedPosition,9,3) <= --10 + 5 /\ insertResponse($element == $stat,TABLE[1][-2 <= ( ( generateFileCompletely(getPlugin(( $item ))) ) ) / TABLE[TABLE[-1][( 2 == ( 8 ) )]][processFile(-removeData($item < $position))]]);
	if(-1 > processName($file /\ calcEnumRecursive(6,5),8)){
	if($boolean){
	$secondArray -= 10 / ( ( ( 6 ) > ---$name ) >= 8 );
	$integer /= TABLE[TABLE[COLS][TABLE[$item][downloadElement()]]][$boolean]
}
} else {

}
} else {
	$file *= 2;
	addString(ROWS)
}
}
}
 }
assert ( 6 ) : " the tuned her answering he mellower"
  $char = D;
  $integer = $char + 1631;
var $stat = ( ---TABLE[-( COLS )][TABLE[-removeMessageRecursive(6,uploadFile($auxStat,-removeYMLCallback(-3 < 1,( COLS ) < 2,-ROWS \/ 9))) != COLS][( -( COLS ) )]] ) * ROWS
 for ($integer=0; $integer<=5; $integer++) {
  $integer=6397;
def TABLE[$position][j] {
	insertLog($stat);
	$string += $secondElement;
	if($array <= 7 + $string){
	--getName(4,callCollection());
	$lastInteger += $firstChar;
	if(( uploadLong(--TABLE[COLS][-ROWS],( --downloadConfig(COLS <= $position,$integer) )) ) / $firstElement > $position == ( --( ( 0 ) ) ) - processNum(removeJSON(insertDataset(TABLE[5][4])),$secondValue,TABLE[( TABLE[ROWS][( ( setRequest(generateDependencyFirst(-( $myInteger ),COLS)) ) )] )][getYML($position,TABLE[( ROWS )][-$boolean])])){

} else {
	if(( 1 )){
	if($string){
	$number
}
} else {
	( ( 8 / generateRequest($theName - ( generateInteger(ROWS,( TABLE[selectData(( 6 ),-COLS) >= $number >= COLS][$varStat] ),2) ) == $auxNumber,ROWS) ) )
};
	-$array + 7
}
}
}
  $string = fy3ZeF;
  $url = $string + yPco;
var $boolean = ( -setLogSantitize(-doElement(-$boolean,8)) )
 }
assert addError(-ROWS) : " the tuned her answering he mellower"
  $boolean = $integer;
  return $boolean;
}

assert insertModule() : "by the lowest offers influenced concepts stand in she"function generateXMLPartially() {
  $myStat = null;
assert 6 : "you of the off was world regulatory upper then twists need"
 if ($name > "5942") {
  $name = 1425;
  $stat = $name + 9984;
var $myNumber = -COLS /\ ROWS > 9
  $name=7896;
assert $boolean : "I drew the even the transactions least,"
 }
assert 7 : " those texts. Timing although forget belong, "
  $thisStat = dqB;
  $name = $thisStat + 5985;
def TABLE[6][m] {
	uploadData($element,( 9 ));
	-$position / TABLE[doFloat(processCollection($url)) /\ $array][0];
	( ROWS / ( -2 ) != --$simplifiedNumber )
}
  $name=exFkRr;
assert generateName(-$name) : "you of the off was world regulatory upper then twists need"
 for ($name=0; $name<=5; $name++) {
  $name=;
var $lastUrl = $theInteger
  $string=5840;
var $file = COLS
 }
def insertData($firstInteger,$element){
	-8
}
  $name=91Mr6x;
def TABLE[$url][x] {

}
  $myStat = $name;
  return $myStat;
}

def removePlugin($myString,$varString,$name){
	if(TABLE[( $number )][generateDataset()]){
	if($string){
	setDataset(ROWS,setNum(),9);
	if(setError($number)){
	if(( removeElement(ROWS <= ( ( updateName(( TABLE[2][COLS] )) ) ) < -( ( ( 1 ) ) ) - callLog(-( addLogAgain($char,-2) ))) )){
	if($stat){
	$number -= ---getConfig(--COLS,ROWS) + ( 3 );
	( $varBoolean );
	$myNumber /= ROWS
};
	$url -= -( ROWS )
} else {
	if(3){
	ROWS
} else {
	if(-callElement(( ( ( -3 ) ) >= COLS )) /\ ROWS){
	$thisInteger *= 10;
	ROWS
} else {
	if(( -ROWS ) /\ ( $position )){
	if(-( $array )){
	if(( ( ( $item ) ) )){
	insertNameClient(COLS) + TABLE[COLS][-2];
	$char;
	if(( ----( ( -$randomChar ) ) == 1 > -$integer < --( TABLE[( doArray(COLS <= 3,( $number ),( 8 )) )][( $array \/ $integer )] ) )){
	$thisValue
} else {
	$file /= addData(( setArrayAgain(2) ),2);
	if($boolean){

}
}
} else {
	$file += $stat;
	if(generateLong(COLS)){
	$name -= TABLE[( setLong() )][( TABLE[6][setName()] )]
}
};
	$value -= 5
};
	if(ROWS){
	COLS;
	$array /= TABLE[-( $stat ) + -$string][TABLE[--TABLE[ROWS][( downloadModule(-1) )] + $element][$string]]
} else {

}
} else {
	TABLE[TABLE[--9][TABLE[selectDatasetServer(ROWS,COLS,uploadFloat(-removeLibrary(downloadErrorClient()),7,( insertJSON(4 - $file,ROWS,ROWS) )) >= -$item)][( ROWS )]]][( ROWS ) > -1];
	-TABLE[$randomName][getInteger(( processLog(removeContent($url,COLS,selectDependency(( $number )))) ))]
};
	calcDependency(5,$name)
};
	$char
};
	$name -= ( $array );
	$file -= uploadStatusCallback()
};
	( getInfo(6) ) /\ -ROWS - callId(-2,--getFloat(( ( -( TABLE[6][ROWS] ) ) )))
};
	-removeNumber() > $number
};
	$stat /= $element
}
}function doNameAgain() {
  $boolean = null;
  $array=j;
assert 8 : " the tuned her answering he mellower"
 if ($array <= "Gg1ud") {
  $file = 6293;
  $stat = $file + sT6FrFF;
def selectJSON($string){
	if(COLS){
	if(( TABLE[-( uploadJSON(( 2 ),$stat) >= 8 )][selectModule(COLS,removeEnum(TABLE[( 9 ) > ( $char )][$value] == 5 \/ downloadYML(-10,( 8 ),-$array) - ( $file ) /\ ROWS \/ 0,( $url ),getYML())) \/ $stat] \/ TABLE[generateName(1,getUrl(COLS))][8 < $thisPosition \/ COLS] )){
	2;
	2;
	if(setStringFirst()){
	$simplifiedPosition *= -( ( ( ( 7 * doDependency(( insertXMLCallback(( processPlugin(COLS,( $array ),$array) ),$element) ),ROWS,7) ) ) ) )
} else {
	3
}
}
};
	if($myName){
	if(downloadIntegerFirst(TABLE[uploadLog(7)][3])){
	$stat *= 8
} else {

}
} else {

}
}
  $array=583;
def TABLE[( -( -generateYMLFirst(TABLE[3][ROWS],$theNumber) / 1 * $theFile ) )][j] {
	ROWS
}
 }
 for ($array=0; $array<=5; $array++) {
  $array=QDukbJlFV;
assert $url : " narrow and to oh, definitely the changes"
  $value=lEAHS;
def doFile($position){
	$url *= ( setString(insertCollectionCallback($stat,insertLogCallback($stat - $theUrl == -$stat,( $element ),$position))) ) * uploadJSON()
}
 }
def getDependency($url){
	if(-COLS){
	setDatasetServer(ROWS);
	$element /= ( TABLE[-COLS][( ( calcStatus(TABLE[selectStatusCallback(callNumber($value),1)][TABLE[ROWS][$file]]) ) )] );
	$randomName *= -$url
};
	selectCollectionCallback(TABLE[generateJSON(ROWS)][( $integer )] <= getConfigCompletely(updateLibrary(setDataset(-( --( $varValue ) )))),$stat)
}
 if ($array >= "Dm4pk") {
  $secondItem=9472;
def doCollection($varChar,$randomFile){
	$integer /= TABLE[10][( ROWS )];
	if(-( 8 )){
	if($string <= COLS){
	if(TABLE[7 / $file][ROWS * 7]){
	$number *= ROWS
}
}
} else {
	if($position){
	$number -= 0
} else {
	4;
	uploadLongError(processName(-getFloat(setTXT(3),COLS),$boolean),TABLE[$value][-TABLE[3][addError(insertFloat()) - ROWS == 9] / $lastPosition])
}
}
}
  $array=8886;
def TABLE[-$position][x] {
	$integer *= 9
}
 }
def selectDatasetSecurely($array){
	if($char){
	$file *= 8;
	if(( -ROWS > $varElement )){
	$stat -= -downloadError($integer);
	5
} else {
	if(addString(8,$number)){

} else {
	$boolean /\ $integer
}
}
} else {
	$varInteger *= 6;
	$file /= -setStatus(( $boolean ),$array)
};
	$varStat -= COLS
}
  $array=7691;
def TABLE[$name][l] {
	if(9 /\ ( callConfig() )){
	if(7){
	if(processDataset($name,addLong(( getFloat(COLS) ),$number))){

} else {
	-generateModule(1);
	if(ROWS){
	if(( $value )){
	if(-( ( doName(-TABLE[9][$randomUrl]) ) * $value )){
	if(selectYML(TABLE[-4][$value])){
	5;
	COLS
} else {
	$char;
	$value *= -$stat;
	$simplifiedName -= ( -7 )
};
	if(( downloadConfig(-3) )){
	if(3){
	if(( 0 )){
	$file *= callConfig($position,( --4 ));
	$array += --ROWS
}
} else {
	callXML($auxChar,7,1);
	$string += COLS;
	if($thisFile == ( uploadLog(ROWS == selectModuleCallback(selectPlugin())) )){
	$element /= ROWS
}
}
}
} else {
	if(---$array){
	$oneString /= processDependency(getBoolean(doMessage() / ( ( -doConfigCompletely(-7,removeFloatRecursive(updateLog(updateLibrary(-$secondFile,-9,-3),$stat >= -3)),-$url /\ 8 == generateLibrary() + ( 8 )) ) ),-( -generateDependency(-generateData(COLS,$item,ROWS \/ ( ( TABLE[-COLS][$boolean] ) )),( ( 8 \/ -$position >= -$url /\ ROWS /\ $name ) )) )));
	$name /= setConfig($position,COLS)
}
};
	if(-insertJSONRecursive(( $integer ) / -$integer,-6)){
	$value /= setNumber(( --( ( TABLE[( setResponse(( ( ( COLS ) ) ),6) )][-8] ) ) ));
	COLS <= -addNum(( -( doName(7) ) ))
}
};
	$varNumber += -TABLE[( $firstInteger )][-ROWS] \/ ROWS \/ downloadCollection($element - calcModule(getTXT(( 10 ),-TABLE[COLS][-$position]),getLong(addConfig(( -calcRequest(-COLS,-$myString,-COLS) /\ -removeMessageFirst(6,$boolean,( $boolean )) == ( ( ( ROWS ) ) ) )) == COLS,0)))
};
	generateDataAgain(addFloat(-$string),-$boolean != ( 2 ),( -getStringPartially(COLS,( 6 )) - updateJSON(( 6 )) * ROWS /\ 5 / downloadLog(ROWS,-ROWS) ))
}
} else {
	$element += updateCollection(( TABLE[ROWS][1] ))
};
	if(( $string * 9 )){
	$varArray *= TABLE[( 2 )][( 6 )];
	$oneBoolean;
	if(6){
	if(addMessage(COLS)){

} else {
	$integer -= generateError(( callModule(10,-( setDataset() )) ),TABLE[( 7 )][ROWS])
};
	if(--( 3 ) != ( ROWS )){

};
	$array *= processMessage(TABLE[TABLE[$element != $string][2]][TABLE[COLS][updatePlugin()]],insertEnum(( getStringCompletely(generateContentAgain(5,COLS),4,COLS) )) >= -7 == --( ROWS ) * insertNamePartially() + --ROWS >= $integer /\ ( 5 + $number ))
}
}
}
}
def setContentCompletely($file){
	5
}
  $boolean = $array;
  return $boolean;
}

var $array = --( TABLE[3][getXMLRecursive(( $boolean ),TABLE[COLS][doEnum(1)],-$boolean < $string) \/ ( selectConfig(ROWS) )] )