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

var $item = insertLog()