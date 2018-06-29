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
}