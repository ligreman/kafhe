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

assert calcData(7,( -TABLE[$file][10] != ( -COLS ) <= 1 )) \/ $string : " that quite sleep seen their horn of with had offers"