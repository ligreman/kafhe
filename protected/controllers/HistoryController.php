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
}