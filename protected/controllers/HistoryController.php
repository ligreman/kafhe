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
}