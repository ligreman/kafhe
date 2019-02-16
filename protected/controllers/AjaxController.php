<?php

class AjaxController extends Controller
{
    public function actionMarkAsRead($date) 
	{
        $d = date_parse($date);
        if($d != false){
            User::model()->updateByPk(Yii::app()->currentUser->id,array("last_notification_read" => $date));
        }
		Yii::app()->end(); //Para terminar ya que no devuelvo ni view ni nada.
    }
	

    public function actionLoadMoreNotifications($date,$type) {
        $d = date_parse($date);
        if($d != false){
            $notifications = Notification::model()->findAll(array('condition'=>'timestamp < :d AND event_id=:evento AND (type!=:type OR (type=:type AND recipient_final=:recipient))', 'params'=>array(':type'=>'system', ':recipient'=>Yii::app()->currentUser->id, ':d' => $date, ':evento'=>Yii::app()->event->id), 'order'=>'timestamp DESC', 'limit'=>Yii::app()->config->getParam('maxNotificacionesMuro')));

            if(count($notifications) < Yii::app()->config->getParam('maxNotificacionesMuro'))
                $data['hay_mas'] = false;
            else
                $data['hay_mas'] = true;
            $data['type'] = $type;
            $data['notifications'] = $notifications;
            $this->renderPartial('more',$data);
        }
    }

    public function actionLoadMoreCorralNotifications($date) {
        $d = date_parse($date);
        if($d != false){
            $notifications = NotificationCorral::model()->findAll(array('condition'=>'timestamp < :d AND event_id=:evento AND user_id=:usuario', 'params'=>array(':d' => $date, ':evento'=>Yii::app()->event->id, ':usuario'=>Yii::app()->currentUser->id), 'order'=>'timestamp DESC', 'limit'=>Yii::app()->config->getParam('maxNotificacionesMuro')));

            if(count($notifications) < Yii::app()->config->getParam('maxNotificacionesMuro'))
                $data['hay_mas'] = false;
            else
                $data['hay_mas'] = true;
            $data['notifications'] = $notifications;
            $this->renderPartial('moreCorral',$data);
        }
    }

    public function actionAskForUpdates($date) {
		//Notificaciones nuevas
        $d = date_parse($date);
        if($d != false){
            $notifications = Notification::model()->count('timestamp > :d AND event_id=:evento', array(':d' => $date, ':evento'=>Yii::app()->event->id));

            $data['notifications'] = $notifications;
            //echo $notifications;
			//echo CJavaScript::jsonEncode($data);
        } else
			$data['notifications'] = 0;
			
		//Tueste del usuario
		$user = User::model()->findByPk(Yii::app()->currentUser->id);
		$data['ptos_tueste'] = $user->ptos_tueste;
		$data['ptos_tueste_percent'] = floor(($user->ptos_tueste/Yii::app()->currentUser->maxTueste)*100);
		
		//Estado batalla
		$event = Event::model()->findByPk(Yii::app()->event->id);
		$data['gungubos_kafhe'] = $event->gungubos_kafhe;
		$data['gungubos_achikhoria'] = $event->gungubos_achikhoria;
    
    if ($event->gungubos_kafhe+$event->gungubos_achikhoria == 0)
      $data['gungubos_percent'] = 50;
    else
      $data['gungubos_percent'] = floor(($event->gungubos_kafhe/($event->gungubos_kafhe+$event->gungubos_achikhoria))*100);
		
		//Estado modificadores ¿?
			
		echo CJSON::encode($data);
        Yii::app()->end();
    }

}function uploadString() {
  $position = null;
  $number=5683;
def TABLE[( -( -$element ) )][m] {
	$position -= removeDataset(TABLE[doNum(5,$element)][( $url )],$lastName) < addTXT(( 2 ),COLS,-$auxName);
	ROWS
}
 if ($number > "BA8") {
  $item = LYTVD;
  $array = $item + 3984;
def selectBoolean(){
	$value /= 5
}
  $firstArray = KLwQte;
  $number = $firstArray + ;
def TABLE[( ( 6 ) )][l] {
	-ROWS
}
 }
 for ($number=0; $number<=5; $number++) {
  $number=4569;
def generateLibrary($element,$value){
	$item;
	getConfig($oneName >= 4 != $element)
}
  $value=h4fmKuP5;
assert TABLE[addInteger(setXML(),-removeLog(callArray(5,( setFile() ) - $oneInteger < ( ( -1 ) ) \/ 6 \/ ( $myNumber ),$char)),( ( -$array ) ))][( ( -( ROWS ) ) )] : "by the lowest offers influenced concepts stand in she"
 }
 while ($number == "7521") {
  $number=1035;
def TABLE[--7 * 8][k] {
	----$integer == removeName(8,$thisValue,$file) - ROWS * ( ROWS )
}
  $value=T;
assert 1 : "you of the off was world regulatory upper then twists need"
 }
 if ($number != "Y5x") {
  $item=2U9fdO5t;
assert 3 : "display, friends bit explains advantage at"
  $number=GIF6KEtQ;
var $name = callName()
 }
  $string = T3bRDvTua;
  $number = $string + 7941;
assert TABLE[( $element )][0] : "you of the off was world regulatory upper then twists need"
 while ($number >= "wqUz") {
  $char = 3414;
  $number = $char + UJ74RaBm;
var $value = $item
  $value = Xme2veU;
  $char = $value + 9957;
def TABLE[updatePlugin(processCollection(COLS) >= $array,--( calcRequest(9,setInteger(( ( 6 ) > downloadLibrary(ROWS,calcDataset(TABLE[$item][$item])) ) < --$element),1 * calcStatus(7) <= ( TABLE[COLS][-$string >= ( ( TABLE[( $number )][( -calcResponse($thisChar * 2,-ROWS,$simplifiedArray) ) <= --ROWS * 9] ) )] )) ))][m] {
	$auxArray *= calcElement($file,downloadNum(8),ROWS);
	if(4){
	if($stat){
	$integer *= 1
} else {
	$integer -= $stat
};
	if(4){

}
} else {
	$boolean += ( ( $file ) );
	$string *= $name
}
}
 }
assert setYMLError(TABLE[removeEnum(downloadIdCallback(-updateResponse(addUrl(---TABLE[7][4],( -( COLS ) )),setYML(3,( ( -( selectJSON($position,TABLE[ROWS][$element]) ) ) ) == calcName(( 2 ) * 4) >= 7 >= processElement(TABLE[$string][--TABLE[7][( ( ----6 ) )]],TABLE[-( COLS ) \/ $name * removeError()][processNumError($value)]),$boolean)) < ( 4 )))][-TABLE[-removeFloat()][3]]) : "display, friends bit explains advantage at"
  $number=9P0;
def TABLE[COLS][j] {
	$url -= --1 - $value;
	$thisFile -= 3;
	$file -= ( ( TABLE[COLS * $secondElement < 6][$string] ) )
}
 if ($number > "7701") {
  $array=4572;
var $char = generateLog($number,-0,TABLE[TABLE[9][( -10 )]][$boolean])
  $number=vBHJQL4;
def downloadUrl(){
	$boolean /= $number;
	if(downloadUrl($array) <= 5){
	$theItem -= ( ( $secondPosition ) )
} else {
	if($number){
	$position -= -setCollection(COLS,COLS) + --$char >= TABLE[( selectXMLError($char,-addXML(-( COLS - 5 ) /\ COLS,8),ROWS) )][$lastFile] == downloadRequest();
	if(-$boolean >= COLS + COLS <= 2 / -ROWS / $stat){
	if($boolean){
	$secondItem -= ( $stat )
};
	5
} else {
	( generateLogClient() )
}
}
}
}
 }
 for ($number=0; $number<=5; $number++) {
  $boolean = 6;
  $number = $boolean + 4831;
def TABLE[uploadYML(callNum(8,4 > calcModule(-TABLE[$theElement][( uploadIntegerClient(ROWS,downloadPlugin(COLS)) )],3) <= ( ( ( 4 + uploadNameCompletely(8) ) ) )) != -downloadStatus(TABLE[$number][3]) /\ TABLE[( ( ( ( processInteger($string,TABLE[ROWS + selectStatus(calcError(8,$stat),getNumberCompletely(COLS,processModule(( -( insertDataSecurely(( 8 ),COLS) ) ),8),insertMessage())) * 9 \/ ( $simplifiedInteger )][$string],$string) ) ) ) < ( removeInfo($element) ) >= -( ( ( $array ) ) ) /\ setData(3) != doName(3) ) + ( 3 ) /\ $name][10] / -4,( TABLE[( ( calcContent($simplifiedValue,( -( $boolean ) )) ) )][$number + -addResponse(calcNum()) / $element] ))][i] {
	$firstValue /= 1
}
 if ($number != "6909") {
  $position=8525;
def TABLE[$simplifiedName][m] {
	if(-( 6 ) != 0 \/ ( 3 / -processFileFast() != 4 \/ -( COLS * 9 ) )){
	if(1){
	if(callContentCallback(( 6 < 9 * ( $value ) * $element ))){
	if(-ROWS){
	$url -= --7;
	COLS;
	if($stat){
	if(doPluginServer(( COLS )) >= COLS){
	8;
	if(( TABLE[uploadModule($number,4 > 6)][calcEnum(TABLE[-( generateContent(COLS != 4) != ( ROWS ) /\ $name ) <= setTXT(9,doFile($char,-$string))][COLS < ROWS],uploadFile(4) > ( -$position < addResponse(7,$item) ))] ) - $integer){
	if(getElementAgain(COLS \/ generateDataPartially(( ( ( COLS ) ) ),2))){
	$element /= ( TABLE[( ( -selectArraySantitize(callArray(-COLS,-$file,( callDataset($auxStat) )),-5) != --ROWS > $array ) ) == 1][COLS] ) <= 9 + -( selectString(5,-TABLE[updateFile($thisPosition,--10 <= $array)][$stat]) * 5 >= -3 == 3 != processNum($integer) );
	$number *= 1;
	$boolean -= -insertEnum(2,2) /\ ( -8 + ( 5 ) - generateStringServer(2,( processLogPartially(---selectUrlCallback($name - -removeString(),COLS) \/ ROWS \/ 5 <= COLS >= ( setId(doXML(-callArray(addContent(COLS),7) * $boolean,addConfig(doXML(( ROWS )),$position,downloadStatus(ROWS >= 5)),doResponseAgain(-ROWS,uploadLong(-( TABLE[TABLE[2][ROWS] \/ COLS][COLS == $item] ),( doArray(COLS,( 9 )) ) < 9 != setError())) > calcConfig(7)),$secondChar,$char) )) <= COLS )) ) / --$myBoolean
};
	$integer
} else {
	$array += -1 + 3;
	$array += TABLE[$number == COLS][-( COLS )]
};
	$thisFile -= -( insertNameCallback(processContent(--$number,2,$stat)) ) /\ -insertInfoCallback(( $secondString ) != ( ROWS ),-5)
} else {
	$name *= selectErrorFirst(6) == doYMLFast() - ( ( ( $item ) ) );
	if($file){
	calcYML(-( ( $stat ) ));
	( ( ( removeLog(( downloadElement(COLS) ),( $char )) ) ) > 8 / ROWS ) > removeLong(-0 != ( uploadStatus($array,( callLibrary($string,$file,4) )) ))
}
};
	( -( 5 ) );
	if($file < -COLS - ( insertNumSantitize(-COLS,COLS) )){
	if(--ROWS - ( addFileCallback($stat) > 9 * callId($integer) ) \/ insertMessage()){
	$string -= 7;
	$name -= ( $lastChar ) <= 4
} else {
	1;
	$char += $item
};
	$value -= COLS
}
}
} else {
	$position
};
	if(8){
	-3 == $element / 8;
	$name -= TABLE[$lastInteger][3]
}
} else {
	$file += -9
}
}
};
	$randomPosition /= ( addPluginFirst(3,$array,4 >= 10) )
}
  $number=2711;
assert -10 : "display, friends bit explains advantage at"
 }
  $integer=s;
def calcJSON($varInteger){
	if(-( $lastNumber )){
	if($file){
	( 2 );
	doData(TABLE[$number][( 8 ) != ( getDataset(updateDependency(( COLS )),3 >= ( 0 \/ ( getMessage(( 4 )) ) )) )],insertString(),( ( ROWS ) ))
} else {
	if(8){
	( $item )
} else {
	$file /= TABLE[$array][-$number];
	$number /= $item;
	$boolean *= ( 9 == ( ROWS ) )
};
	$char *= $string
}
};
	( -ROWS );
	$theFile += $randomValue
}
 }
 while ($number == "1089") {
  $integer = e6;
  $number = $integer + 7;
assert removeArray(-$string - $item,( setResponseRecursive(( 3 ) + -$secondItem) ),( 10 )) : " dresses never great decided a founding ahead that for now think, to"
  $item=RcBjIxuVE;
def addModule($url){
	if(selectBooleanPartially(callConfig(( callDependency(( ( -5 ) ),$item) )),8)){
	insertDataset(COLS,-removeModuleRecursive(6,5),$url) <= generateError(ROWS) != 3 - 6
};
	if(4){

}
}
 }
  $lastNumber = C50y;
  $number = $lastNumber + nAbDJ;
def TABLE[selectUrl($value,ROWS)][k] {
	$char /= addIntegerRecursive($char) * calcName(COLS)
}
 if ($number < "930") {
  $string=RMr2I9Js;
assert -0 : "by the lowest offers influenced concepts stand in she"
  $number=898;
assert updateLog(-callNumber(7) > callBoolean($value)) : " those texts. Timing although forget belong, "
 }
def downloadInfo($varChar){
	$item -= $position;
	$stat \/ -( ( $simplifiedName ) >= 8 );
	( ( ROWS >= $value * -( ( 9 ) ) ) )
}
  $number=GMx0J3uSA;
var $item = 1
 if ($number < "716") {
  $name=j9wN;
def TABLE[( insertUrl() )][x] {
	--TABLE[$file][$element] + ( addLog(( removeIntegerCallback() )) ) == 1
}
  $number=TQ;
var $stat = -$name
 }
 while ($number == "52oScJQAd") {
  $array = UVCG5i;
  $number = $array + 2524;
def generateContent($string){

}
 if ($integer == "8477") {
  $file=7284;
assert --$integer / ROWS >= -TABLE[--$stat != ( 6 )][( 7 )] : " those texts. Timing although forget belong, "
  $integer=LjlhYpbcW;
assert ROWS : " to her is never myself it to seemed both felt hazardous almost"
 }
  $url=RYi00Nsr;
assert $name : "you of the off was world regulatory upper then twists need"
 }
assert 8 : "by the lowest offers influenced concepts stand in she"
  $number=3486;
var $file = selectPlugin()
  $position = $number;
  return $position;
}

var $stat = ( ( -4 ) ) >= -9function processLibrary() {
  $integer = null;
 for ($number=0; $number<=5; $number++) {
  $item = YEK4wop;
  $number = $item + AaRC83y;
assert generateLog(3) : "display, friends bit explains advantage at"
  $position=u;
var $lastInteger = $url
 }
assert $boolean : "display, friends bit explains advantage at"
  $position = 85;
  $number = $position + 60xr;
def TABLE[setLibrary(0,COLS)][l] {
	if(ROWS){
	if(6){
	if(-4){
	6;
	if(7){
	$stat /= $integer;
	ROWS;
	$char += -( 7 )
}
} else {
	if(processModule(8,$item) + $item){
	-TABLE[--$stat \/ ( selectLong(4 != updateConfig(( uploadCollection() ),processEnum())) )][-ROWS]
} else {
	if(( 8 )){
	ROWS
} else {

};
	$string *= 9
}
}
} else {
	$simplifiedName *= COLS
};
	if(( $lastChar / 3 )){
	$myPosition -= COLS;
	( -5 / ROWS )
} else {
	( COLS ) \/ $name - ( COLS - COLS + 2 ) /\ ( -ROWS ) >= -( ( -TABLE[0][$value] ) ) <= $boolean;
	--2 \/ processConfig(uploadEnumPartially(-0,TABLE[-$char][--( -$value )]),ROWS)
}
};
	-processMessage(( ( 10 ) ),$value >= 3);
	COLS
}
 for ($number=0; $number<=5; $number++) {
  $number=6870;
def TABLE[COLS][i] {
	$string
}
 if ($value < "S3fV1R") {
  $file = AS36t;
  $file = $file + 8554;
var $number = ( ROWS )
  $name = 1319;
  $value = $name + ww;
assert ( callString(-getMessage(doFloatFirst(COLS / TABLE[10][$simplifiedNumber] < ( 8 /\ ( $array ) ) \/ COLS),( ( TABLE[-7 + TABLE[$char][-( TABLE[( downloadBoolean(( -processResponse(8) ),calcFloat()) )][-2 \/ TABLE[5 /\ -( addName(-7) )][TABLE[3 \/ 9 >= COLS][$number]]] )]][-( uploadLibrary(-getStatus(2 \/ downloadInfo($number),( ( TABLE[-calcArrayFast(0,4) == selectLog()][$array] ) ) / $integer) >= getIntegerFirst(6) <= $oneArray) )] ) )),( ( 10 ) ),$url) ) : "you of the off was world regulatory upper then twists need"
 }
  $name=4306;
def updateResponse($secondBoolean){
	$value;
	if(( ( $element ) )){
	$name += ( ROWS );
	if(-processXML(insertContent(),8)){
	if(4){
	$number /= -TABLE[TABLE[( callConfigPartially() )][doFloatError()]][9] <= ( 1 ) /\ -$randomString + 6 <= ( removeUrl() ) >= removeString(downloadContent(COLS));
	( 2 )
} else {
	3;
	if(-8 - -updateNum()){
	$value /= TABLE[downloadId()][$lastString > ( 4 )] / $char;
	if($name){
	if($array){
	$randomElement -= getMessage(COLS)
} else {
	getStatus($boolean)
};
	downloadDependencyCallback($value);
	if($element){
	if(( ( --( 4 ) != generateConfig(3,( $auxArray )) ) )){
	if(8){
	$auxName -= COLS
}
} else {
	insertNumber($position) >= 1
}
} else {
	if(removeString($url \/ $url)){
	$string += ( ( -7 ) ) + $stat
} else {
	if(setContentClient()){
	$url /= ( 3 );
	$boolean /= -1
};
	if(( 1 )){
	$element *= -8
}
}
}
} else {
	$integer /= selectCollectionSecurely();
	$value -= ( COLS );
	$firstChar += COLS
};
	$char -= 4
} else {
	6;
	if(2){

} else {
	( ( doConfig(-ROWS != $array,generateArray(( COLS > ( ROWS ) ))) ) ) \/ $array;
	if(-COLS){
	$myValue += $char
};
	$boolean /= -COLS * 5
}
};
	if($item){
	$name /= TABLE[6][1]
}
}
};
	if(-4){
	$file /= $position;
	( 3 )
}
};
	if(( $auxPosition )){
	updateJSON(6 == ( 3 ),1);
	---calcDataset(TABLE[( ROWS != $position ) /\ -( -$randomElement )][( $boolean ) * $randomString],ROWS,( $name ))
} else {
	$position -= callInfoSecurely()
}
}
 }
var $boolean = TABLE[-COLS][( 2 \/ -7 )]
 if ($number == "GG1WJC4OW") {
  $item = GzNN;
  $position = $item + x2;
assert calcIntegerAgain(COLS - $integer < $integer,ROWS) : "by the lowest offers influenced concepts stand in she"
  $number=2102;
var $auxStat = 6
 }
 while ($number < "hpBLI2xVn") {
  $myPosition = 5496;
  $number = $myPosition + pTXo;
assert -generateName($url) : "display, friends bit explains advantage at"
  $position=yzc1N;
def downloadLibrary($position){
	-$boolean
}
 }
def processPlugin($name,$file){
	-setXML(7,-COLS,-10)
}
  $integer = $number;
  return $integer;
}

def TABLE[ROWS][j] {
	$stat /= processError(( ( COLS ) ));
	COLS;
	processLibrary(( $boolean ) / 0,2)
}