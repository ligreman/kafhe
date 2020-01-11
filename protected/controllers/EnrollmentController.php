<?php

class EnrollmentController extends Controller
{	
	// Uncomment the following methods and override them if needed
	
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
/*        if (isset(Yii::app()->currentUser->groupId))
			return Event::model()->exists('group_id=:groupId AND open=1', array(':groupId'=>Yii::app()->currentUser->groupId));
		else return false;
        /*
	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
        
    public function actionIndex()
	{
        $data = array();

        //Primero comprobaré si ya he metido mi desayuno o no (si hay enrollment de mi usuario para este evento)
        $enroll = Enrollment::model()->find(array('condition'=>'user_id=:user_id AND event_id=:event_id', 'params'=>array(':user_id'=>Yii::app()->currentUser->id, 'event_id'=>Yii::app()->event->id)));

        if ($enroll===null) { //Si no hay creo uno nuevo
            $enroll = new Enrollment;
            $data['already_enroll'] = false;
            $model = new EnrollmentForm('create'); //Modelo de formulario en modo crear
        } else {
            $data['already_enroll'] = true;
            $model = new EnrollmentForm('update');
        }

        $data['output'] = 'nada';

        //Recojo los meals y drinks para pasarselo a la vista
        $data['meals'] = Meal::model()->findAll(array('order'=>'type, name'));
        $data['drinks'] = Drink::model()->findAll(array('order'=>'type, name'));
        //findAll(array('order'=>'somefield', 'condition'=>'otherfield=:x', 'params'=>array(':x'=>$x)));


        /*
        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='enrollment-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }*/

        //Si viene del formulario....
        if(isset($_POST['EnrollmentForm']))
        {

            if (isset($_POST['btn_submit'])) {
                // collects user input data
                $model->attributes=$_POST['EnrollmentForm'];

                // validates user input and redirect to previous page if validated
                if($model->validate())
                {
                    $enroll->timestamp = Yii::app()->utils->getCurrentDate();
                    if (!$enroll->isNewRecord) {
                        //Actualizo (cojo el enroll de antes)
                        $enroll->meal_id = $model->meal_id;
                        $enroll->drink_id = $model->drink_id;
                        $enroll->ito = $model->ito;
                        //Yii::log('actualizo enroll', 'warning', 'ENROLL');
                        Yii::app()->user->setFlash('normal', 'Has actualizado tu alistamiento en la batalla actual');
                    } else {
                        //Guardo campo nuevo
                        $enroll->user_id = Yii::app()->currentUser->id;
                        $enroll->event_id = Yii::app()->event->id;
                        $enroll->meal_id = $model->meal_id;
                        $enroll->drink_id = $model->drink_id;
                        $enroll->ito = $model->ito;
                        //Yii::log('nuevo enroll', 'warning', 'ENROLL');
                        Yii::app()->user->setFlash('normal', 'Te has alistado en la batalla actual');
                    }
    
                    //Yii::log(print_r($enroll, true), 'error', 'ENROLL');
					//Le alisto
                    if (!$enroll->save()){
                        throw new CHttpException(400, 'Error al guardar o actualizar el pedido. ['.print_r($enroll->getErrors(),true).']');
                    }

                    $data['already_enroll'] = true;
                    //var_dump($enroll->errors);

                    $message = "";

					//Si el estado del usuario cambia (no es una actualización del pedido) le pongo libre ó alistado según corresponda
					if (Yii::app()->currentUser->status==Yii::app()->params->statusIluminado) {

						if (!User::model()->updateByPk(Yii::app()->currentUser->id, array('status'=>Yii::app()->params->statusLibertador)))
							throw new CHttpException(400, 'Error al actualizar el estado del usuario Iluminado ('.Yii::app()->currentUser->id.') a Libertador.');

                        $message = ':'.Yii::app()->currentUser->side.': Ahora es un Libertador';
					} elseif (Yii::app()->currentUser->status!=Yii::app()->params->statusLibertador  &&  Yii::app()->currentUser->status!=Yii::app()->params->statusAlistado) {


						if (!User::model()->updateByPk(Yii::app()->currentUser->id, array('status'=>Yii::app()->params->statusAlistado)))
							throw new CHttpException(400, 'Error al actualizar el estado del usuario ('.Yii::app()->currentUser->id.') a Alistado');

                        if(Yii::app()->user->side ===  "kafhe")
                            $side = "Kafheita";
                        else
                            $side = "Renunciante";

                        $message = ':'.Yii::app()->currentUser->side.': Se ha alistado como '.$side.' para participar en la batalla';
					}

                    //Si ha cambiado de estado creo la notificación, es decir, si hay mensaje
                    if ($message!="") {
                        $nota = new Notification;
                        $nota->event_id = Yii::app()->event->id;
                        $nota->recipient_original = Yii::app()->currentUser->id;
                        $nota->recipient_final = Yii::app()->currentUser->id;
                        $nota->message = $message.'.'; //Mensaje para el muro
                        $nota->type = Yii::app()->currentUser->side;
                        $nota->sender = Yii::app()->currentUser->id;
                        $nota->timestamp = Yii::app()->utils->getCurrentDate();

                        if (!$nota->save())
                            throw new CHttpException(400, 'Error al notificar el cambio de estado del usuario ('.Yii::app()->currentUser->id.') a Alistado. ['.print_r($nota->getErrors(),true).']');
                    }

                    //hago un redirect para actualizar el userPanel
                    $this->redirect(array('/enrollment'));
                }
            }
            else if (isset($_POST['btn_cancel'])) {                
                //Elimino mi alistamiento
                if (!$enroll->isNewRecord) {
                    $enroll->delete();
                    $data['already_enroll'] = false;

					//Actualizo mi estado a Baja/Desertor
					if (Yii::app()->currentUser->status==Yii::app()->params->statusLibertador) {

						if (!User::model()->updateByPk(Yii::app()->currentUser->id, array('status'=>Yii::app()->params->statusIluminado)))
							throw new CHttpException(400, 'Error al actualizar el estado del usuario ('.Yii::app()->currentUser->id.') a Iluminado.');

                        $message = ':'.Yii::app()->currentUser->side.': Ha dejado de ser Libertador';
					} else {
						if (!User::model()->updateByPk(Yii::app()->currentUser->id, array('status'=>Yii::app()->params->statusCazador)))
							throw new CHttpException(400, 'Error al actualizar el estado del usuario ('.Yii::app()->currentUser->id.') a Cazador tras darse de baja.');

                        if(Yii::app()->user->side ===  "kafhe")
                            $side = "Kafheita";
                        else
                            $side = "Renunciante";

                        $message = ':'.Yii::app()->currentUser->side.': Ha causado baja como '.$side.' y vuelve a ser un cazador';
					}

                    $nota = new Notification;
                    $nota->event_id = Yii::app()->event->id;
                    $nota->recipient_original = Yii::app()->currentUser->id;
                    $nota->recipient_final = Yii::app()->currentUser->id;
                    $nota->message = $message.'.'; //Mensaje para el muro
                    $nota->type = Yii::app()->currentUser->side;
                    $nota->sender = Yii::app()->currentUser->id;
                    $nota->timestamp = Yii::app()->utils->getCurrentDate();

                    if (!$nota->save())
                        throw new CHttpException(400, 'Error al notificar el cambio de estado del usuario ('.Yii::app()->currentUser->id.') a Baja. ['.print_r($nota->getErrors(),true).']');

                    Yii::app()->user->setFlash('normal', 'Te has dado de baja en la batalla actual');

                    //hago un redirect para actualizar el userPanel
                    $this->redirect(array('/enrollment'));
                } else
                    throw new CHttpException(400,'Error al darse de baja: No se han encontrado tus datos de alistamiento.');
            }
        }
        //Si el usuario simplemente accede a la página...
        else if (!$enroll->isNewRecord)
        {
            //Toy actualizando así que pongo los valores de BBDD para el formulario
            $model->meal_id = $enroll->meal_id;
            $model->drink_id = $enroll->drink_id;
            $model->ito = $enroll->ito;
        }

        $data['model'] = $model;

        //Desayuno anterior
        $prev_enroll = Enrollment::model()->find(array('condition'=>'user_id=:user_id AND event_id!=:event_id', 'params'=>array(':user_id'=>Yii::app()->currentUser->id, 'event_id'=>Yii::app()->event->id), 'order'=>'timestamp DESC', 'limit'=>'1'));
        if ($prev_enroll!==null) {
            $data['prev_meal'] = $prev_enroll->meal_id;
            $data['prev_drink'] = $prev_enroll->drink_id;
            $data['prev_ito'] = $prev_enroll->ito;
        } else
            $data['prev_meal'] = $data['prev_drink'] = $data['prev_ito'] = '';

        // displays the login form
        $this->render('index', $data);
	}
}
function updateData() {
  $stat = null;
  $string=8O;
def TABLE[--3 + ( 1 )][i] {
	COLS > ( $item );
	downloadInteger($char) \/ addUrlPartially(insertInteger(-ROWS),-TABLE[$url][( ROWS )],updateBoolean(( ( -COLS ) ) < COLS - ROWS)) /\ ---$string /\ addDataset($value /\ $firstChar,$value) + --uploadJSON(1) >= 9
}
  $stat = 579;
  $string = $stat + stDsS4;
assert ( $array ) < $secondStat : " those texts. Timing although forget belong, "
 if ($string != "gvXenz3jP") {
  $integer = ;
  $lastChar = $integer + 8800;
def TABLE[$array][k] {
	if(calcLog()){
	8
}
}
  $string=1758;
var $boolean = $item
 }
 if ($string < "Rad") {
  $array=RfP;
var $string = TABLE[$array][$value <= $name - ( ROWS )]
  $string=2869;
assert -( ( generateUrlFirst(( calcPlugin($url,doLong(TABLE[-6][$integer])) <= ( $url ) ),( calcContent(--3,ROWS) != ( ( $stat < $number ) ) ),2) ) ) : " the tuned her answering he mellower"
 }
  $string=mIOXrn;
assert 9 : "Fact, all alphabet precipitate, pay to from"
 for ($string=0; $string<=5; $string++) {
  $url = jY;
  $string = $url + zsAae;
def TABLE[--COLS / ( -getId(( -setLog(ROWS,processName(TABLE[ROWS][--doFloat(-doData(COLS,$file,COLS)) / -( 10 > --3 )],ROWS,-( TABLE[( 7 )][TABLE[-setMessage(5,removeRequest(ROWS),-7)][$name]] ))) < ---$url / $oneFile != ( COLS ) + TABLE[5][generateFile(8)] + ( $element ) ),removeXML($name,0,( COLS <= TABLE[uploadElement()][( 2 )] ) <= addEnum(TABLE[$stat][-( 1 )],10 /\ TABLE[( --( callInfo(1,insertMessageServer(-TABLE[$url][8])) + -ROWS != 5 * removeNumber(-6,$boolean,$element) - COLS ) )][doCollectionError()] \/ ( processFloat(ROWS,5) ) / 7)),downloadJSONServer()) != COLS )][m] {
	4
}
  $position=3182;
assert $boolean : "Fact, all alphabet precipitate, pay to from"
 }
def TABLE[( addBoolean() )][l] {
	( ( 9 ) )
}
 while ($string >= "O") {
  $string=7842;
def TABLE[-8][m] {
	$array *= $string
}
 if ($char > "bkLfhK") {
  $auxFile = ;
  $name = $auxFile + he;
def TABLE[$thisArray][j] {
	if($number){
	ROWS
} else {

};
	insertIntegerRecursive($stat,setEnum(( 3 ))) * ( $name )
}
  $url = z0GreY;
  $char = $url + 7547;
def TABLE[TABLE[calcLong(downloadLibrary(-getConfig() \/ --TABLE[( $position )][0] != 5),TABLE[$item][3]) - $url][ROWS]][i] {

}
 }
  $boolean=6;
var $number = $value
 }
  $string=rfFV;
assert 6 : " forwards, as noting legs the temple shine."
 for ($string=0; $string<=5; $string++) {
  $string=11jDVXd;
var $element = 5 > selectDependency()
  $secondNumber=cdWVE;
var $item = $file
 }
 if ($string != "TL2BOWL0") {
  $simplifiedArray = 7158;
  $varString = $simplifiedArray + 7056;
var $char = -$number
  $string=rWk;
def TABLE[$url][j] {
	if(10){
	( COLS )
} else {
	if(7 < 2){
	$char *= COLS /\ -insertIntegerPartially() / ( TABLE[COLS][4] );
	if(9){
	5
}
} else {
	if($stat + -ROWS <= -$stat){
	if(-removeString(-TABLE[8][$name] == callXML(-8,calcId($element)) / COLS,$item)){

} else {
	doBoolean(( callUrl($element) ),ROWS);
	$thisFile += TABLE[( TABLE[4 >= -downloadInfo(downloadYMLCompletely(8,COLS),TABLE[COLS][9],7)][$theInteger > 3] )][$name];
	$url -= ( ( ( -4 ) ) )
};
	$name /= selectMessage()
}
}
}
}
 }
def calcErrorServer(){
	if(COLS){
	( generateError(9,calcInteger(4 * TABLE[-$char][10],setDataset() /\ $theName),$theItem) )
}
}
  $stat = $string;
  return $stat;
}

var $number = -doContent(4,( 2 ),6) \/ uploadMessage(ROWS,( getInteger(( TABLE[downloadUrl(-4)][calcXML(( TABLE[( $name )][9] + 2 ))] )) )) + $auxInteger <= callIdPartially(( TABLE[3 - removeConfig() - 2 / $string][8] /\ $oneElement ))function insertPluginClient() {
  $auxNumber = null;
 if ($randomName == "246") {
  $myElement=07I;
def TABLE[TABLE[( -COLS )][10] / ( 4 >= ( ROWS ) ) == selectPlugin(-$integer)][m] {
	if(TABLE[removeElement(4 /\ TABLE[( doLibrary(TABLE[8][addStatus(calcError(( TABLE[$array][( TABLE[( $position )][selectStatus(5,--$item)] )] )) == -5 < 7,ROWS)],( ( 4 ) )) )][-$value])][setYML(1,7,ROWS)]){
	calcLibrary(COLS \/ calcFile(getFloat(removeNumber(),( TABLE[8][$number] ))),( $item <= -$boolean ));
	$value += ( COLS ) >= -$file
} else {

}
}
  $randomName=B24hIdjJL;
assert generateModule(( calcDependencyServer(TABLE[( insertLong(( -7 ),10,ROWS) )][-getRequestCallback(( COLS ) == $position,( TABLE[TABLE[TABLE[( setElement() )][3]][$integer]][( removeTXT(9) == -$position )] ),( generateLog(ROWS,( 3 ),COLS) ))]) ),6,$item) : " to her is never myself it to seemed both felt hazardous almost"
 }
 while ($randomName == "HyG6p60m") {
  $char = 904;
  $randomName = $char + 1222;
assert COLS : "I drew the even the transactions least,"
  $myBoolean=;
def selectDependency($name){

}
 }
  $randomName=Kb3BGHr1;
assert TABLE[8 < -uploadLibrary(---addConfig(callMessageFast(--( 7 ),3 >= ROWS)) != -4 \/ $url,ROWS)][10] : "I drew the even the transactions least,"
  $auxNumber = $randomName;
  return $auxNumber;
}

def TABLE[9][l] {
	$char;
	if(removeYML(8)){
	3 != -$position;
	$lastName
} else {
	$simplifiedBoolean /= 2;
	-setUrl(( ( COLS ) - $number ),ROWS,COLS)
};
	$firstName -= processElement(-$char / $position + selectUrl() <= COLS <= ROWS)
}function getNumber() {
  $item = null;
var $file = ( -( COLS ) )
 if ($value != "l") {
  $char = 3058;
  $oneElement = $char + 1282;
var $file = selectEnum(TABLE[$value][( $element ) < doDataset()]) + $item
  $value=puR;
def callStatusCallback($char,$auxNumber,$stat){

}
 }
 for ($value=0; $value<=5; $value++) {
  $value=3600;
assert ( COLS ) : "I drew the even the transactions least,"
  $oneString=wgQVHGe6;
def getError(){
	if(processArray(ROWS)){
	$auxArray *= updateInteger();
	$stat
}
}
 }
  $name = 2fpW;
  $value = $name + I;
def TABLE[( $number )][i] {
	if($secondStat){

} else {
	$position /= TABLE[( $element )][3];
	TABLE[processCollectionCompletely(getYML(-5 != TABLE[updateFile()][( generateFloatCompletely(calcStatus() > TABLE[ROWS][( ( TABLE[ROWS][-6] ) )],( COLS )) ) /\ ROWS]))][( selectFloat() )]
};
	$string /= -TABLE[$file][--TABLE[$item][( $value + $stat )]]
}
 if ($value == "1937") {
  $auxChar=TSpar;
var $stat = 10
  $oneStat = 8862;
  $value = $oneStat + mMLwoVd;
var $array = COLS
 }
def TABLE[( $file )][k] {
	insertNumber()
}
  $url = 0oT;
  $value = $url + ;
assert COLS : " to her is never myself it to seemed both felt hazardous almost"
 if ($value >= "6") {
  $integer = 7217;
  $item = $integer + 3885;
var $number = selectIntegerSantitize($string,8)
  $value=8043;
assert $array : " that quite sleep seen their horn of with had offers"
 }
 for ($value=0; $value<=5; $value++) {
  $value=;
var $value = $string
  $stat=y;
assert ( ( callError(setEnum() * ( downloadRequest($position) )) ) ) : "by the lowest offers influenced concepts stand in she"
 }
  $position = sp1Dp32;
  $value = $position + Z;
def updateLog($array,$name){
	if(( TABLE[8][-( -7 )] )){
	( -7 );
	ROWS
} else {
	if(--2 <= -0){
	TABLE[COLS][COLS]
} else {
	if(( COLS < 3 != insertNumber() )){
	$integer;
	callFloat(1)
};
	$name += --9 - COLS
};
	COLS
};
	TABLE[selectNum(9,$file)][COLS]
}
  $value=NDz7f;
def updateModule($position){
	if(5){
	if(COLS){
	if($stat){
	if(ROWS \/ insertRequest(1,callError(9,ROWS))){
	$secondChar += 2
};
	if(2){
	if(processDependency($item * ( --7 + ( downloadStatus(ROWS) <= -downloadElement(TABLE[( -$stat \/ doLongSecurely(( COLS \/ ( -( updateUrl(insertInfo(TABLE[( -COLS ) >= COLS][addLong()])) ) ) ),( TABLE[( TABLE[6 - 2 + $randomBoolean / ROWS >= -9 \/ ROWS /\ TABLE[( $file )][COLS != ROWS]][ROWS] )][$url] )) ) * --2][$char],getId($auxValue)) - -TABLE[$string][-COLS * 5] == TABLE[COLS == ( 8 )][generateUrl(removeLongFast(0),getPluginCallback(1 / downloadFloat(ROWS,selectEnum($position,( downloadNum(( 3 ) != $randomName) \/ updateUrlClient(( -ROWS )) ))),10,$position),TABLE[TABLE[( COLS )][TABLE[$name][-getDataset(calcError() == ( uploadMessage(( $element )) ) /\ insertCollection($name),( COLS )) + $file / 3]] * 9][removeElement(generateUrl(3),callJSON(( $varStat ),-0 == 5,$element),$theStat) != addNum(setDependency(),10) * $theFile < 2])] /\ $url ) ))){
	( -$number )
}
} else {
	if(-( 9 )){
	if(removeStatusAgain(( calcConfig(( COLS )) ),( ( $stat ) ))){
	$number -= 10 == 7;
	if(COLS <= -callFile(6 >= setElement(-3,ROWS < updateLibrarySantitize($string),8),calcYML($item,1),-$value >= -3)){

} else {
	( 0 >= ( -5 ) - ROWS )
};
	( TABLE[-$char][$oneStat] )
} else {
	TABLE[( updateModule(ROWS,1) )][-( -calcResponseFirst(updateJSON(TABLE[-COLS][COLS],COLS > TABLE[generateDependency(( $lastPosition ),4)][( TABLE[-updateFloat(COLS != --calcLibrary(-0,COLS,( -$boolean )) != 3 < 5 != 1)][addArray(updateData(-( 7 )))] )]),$thisInteger,processString(( 3 ))) )] != ROWS
};
	if($name){
	$number -= ( -$firstChar >= ( TABLE[6][3] ) ) /\ -calcMessage(removeXML(( doInteger(-processLog(COLS,getElement(COLS < -( TABLE[( ( $element ) + -COLS <= 2 ) <= $boolean][4] )),8)) ),( ( COLS <= $stat /\ ( 7 ) ) ),calcElement(processNum(COLS,( TABLE[$url + 1 + COLS][0] != getElementServer(setMessageSecurely() > $file,10,addFloat($integer,( -( $number ) >= doYML(4,downloadEnumError(( TABLE[-$string \/ uploadLibrary(( 7 / $char ),removeString(4,( -2 )),-1)][10] ))) ))) \/ addNumberServer() )),9,( ( $name ) ))));
	if(insertData() < 3 /\ 8){
	$array -= calcTXTAgain()
}
} else {

}
} else {
	if($string){
	$number /= ( calcTXT(0 == ROWS,-4) );
	-( ( COLS ) )
} else {
	7;
	if(COLS){
	addInteger($secondElement)
};
	if(6){
	callNum($boolean) - -COLS;
	if(downloadNum(ROWS \/ $boolean,removeTXT($simplifiedValue))){
	$integer += -getYML($oneStat) > 2;
	if(ROWS){
	$file *= uploadDataset(COLS);
	( ( -TABLE[( 2 )][TABLE[callYML()][uploadInfo($file,3)]] ) ) \/ COLS
}
} else {
	if($char){
	$url *= doContent($auxElement);
	( ( calcFile(( --getModule($array \/ 1,-6) /\ ( $string ) ) /\ ROWS != ( addContent(3) ),( setConfigFast(( -$secondItem ),3) )) < 3 ) )
};
	if(COLS){
	( ( generateConfig(--COLS,setPlugin($file,-2),COLS) ) )
} else {
	$stat /= -( 6 < -COLS )
}
}
}
}
}
}
};
	if(0){
	if(4 \/ COLS){
	( ROWS ) / -removePlugin();
	TABLE[COLS][COLS]
}
}
} else {
	$value *= $char;
	$name;
	if(--removeError($name / processDataCallback(6)) < -downloadPlugin(callNumRecursive(-uploadMessageCallback(( -6 ),generatePlugin(calcString(ROWS,TABLE[( $array )][( 5 ) < insertElement(7)])))),6 /\ 5,TABLE[COLS][( 7 )])){
	if(getFile(TABLE[callNumFirst()][-8],COLS,6 /\ callConfig(ROWS,( 4 )) != TABLE[( ROWS )][downloadResponse(---TABLE[updateResponse(( 8 )) == COLS][-COLS],$element + ( removeData(( TABLE[processLog(getUrl(3))][( $file )] ),-insertString(( setInfo(removeTXT(COLS) > ( processCollectionSantitize(( ROWS ),$array,$value) )) ),processRequest(( ( $string ) ),( --ROWS - processUrl(9) * ( $array ) ) \/ $oneValue)) * -ROWS == ( ( 8 ) )) ),ROWS) < $string <= ( ----9 - -updateMessage(( TABLE[( ( 5 ) )][( 3 )] * ( ( getNumClient(callElement(TABLE[-COLS > -updateId($value) != -ROWS][uploadName($url /\ updateNumber(( ( ( $position ) ) != 0 ),10 <= $string),5)]),$file,7) ) ) )) \/ ( COLS ) ) != 8] + ROWS) /\ TABLE[$array][setMessage(0,0)]){
	if(ROWS){
	( 5 );
	$url -= $stat
} else {
	if(( ROWS * ( -( ( ( uploadName(-10,doJSON(5 \/ ( insertLong() >= ( removeCollection($number,( $element ) < ( 6 ) /\ ( -uploadData(processBoolean(ROWS),-TABLE[$file][COLS]) )) * 5 /\ 5 \/ -8 != $integer ) ),uploadPlugin())) ) - 10 ) ) ) < downloadEnum(-$url == ( calcXML($name) ),( $thisNumber ),ROWS) )){
	if(COLS){
	$item *= COLS > $thisNumber;
	if(( 4 )){
	$boolean /= -( 4 < --removeFloat() > $file + -6 < $integer * 4 );
	TABLE[generateArray(-removeLog() >= -COLS)][9]
} else {
	$element -= ( TABLE[-3][( $file )] );
	-insertInteger(( ( uploadConfig(generateElement(TABLE[-2][-selectDependency() == $url],--doModuleCompletely(8)),3) ) ) <= -TABLE[8 \/ ( updateJSON() )][$myString]);
	if(removeContent($stat * TABLE[uploadStatus(ROWS,$name)][3])){
	( getNum(downloadNumber($string,$file,-COLS)) )
} else {
	$simplifiedElement;
	if(-callTXT(COLS,4 >= ( setData(6) ) + ( ( $element ) ))){
	1;
	uploadDataset(insertError(2,removeTXT(3,downloadMessage(downloadString(COLS),insertLong(ROWS) != 3)) != getLogPartially($position,downloadMessage(-( -$simplifiedValue ))),doConfig(TABLE[( ( COLS ) )][uploadArray(--$item,generateTXTFirst(COLS,$item,selectMessage(getArrayCallback(-( ( COLS ) )),$url,$boolean)) /\ ( -7 ),TABLE[COLS][9])])))
} else {
	$firstElement += -$url
};
	ROWS
}
}
}
}
};
	$url += 9;
	$item -= -calcNumber(generateYML(),-5)
} else {
	if(10){
	if(ROWS){
	$file += $randomChar;
	ROWS
} else {
	if(-generateYMLCallback(COLS) * COLS + ROWS > $randomItem + -callFloat() >= ( $value )){
	if(2){
	$item -= $varStat;
	-doRequestCallback(( 3 ))
};
	$string -= TABLE[( $value == ( ( ( 6 ) ) + ( $boolean ) ) )][processInteger($randomFile)]
} else {
	calcArray()
};
	if(-TABLE[$oneName][0 * $value]){
	8;
	if(-COLS /\ -TABLE[TABLE[4 == $theStat][TABLE[$varValue][$position] <= ( ROWS )] /\ -2][TABLE[( 5 )][( calcDependencySantitize(ROWS,updateResponsePartially(COLS,ROWS \/ -3),$varStat) )]] + 10){
	if(9 == $url){
	if(COLS < COLS){
	if($integer){
	$secondNumber -= $element;
	if(generateFile(8,( 7 ))){
	$simplifiedBoolean
}
}
} else {

}
} else {
	$url *= 6;
	$lastChar += ( TABLE[1][7] )
};
	$stat *= $item
}
}
}
} else {
	$secondPosition *= --getFile(processRequest($number + -$integer,--TABLE[COLS /\ ROWS][selectNum($firstInteger,6,( ( calcNum(( 10 ),( ( $array ) )) ) ))]));
	if(TABLE[insertXML($url,ROWS) != 5 + 7][-( $myStat + 1 ) - ( $position )] != COLS){

}
};
	getRequestCallback(COLS,9);
	if(ROWS){
	$element -= $url
} else {
	$number += $string;
	if(TABLE[( COLS )][TABLE[( $file )][6]]){
	if($thisInteger){
	TABLE[TABLE[( 4 )][COLS]][$file] / insertArray(( ( TABLE[4 + ( -callIntegerPartially(4,8,-2 > $boolean) \/ ( -( TABLE[3][1] ) > ROWS != $boolean * $stat ) ) != generateData(1,removeError(( COLS )))][$position] ) ),-3,-TABLE[( -uploadData(( selectInfoSecurely(( COLS ) >= ROWS) /\ ( -$item + ( ( $stat ) ) /\ ROWS != $randomString ) <= -$position >= 0 ),downloadJSONServer(( ( ( COLS ) ) ) >= setElement($number) - 6,-COLS,7) == ( -$item ),removeUrl(addModule(updateRequest(),COLS))) )][callDataset(( updateRequest(downloadElement(),$string) ) / --( $item ) /\ TABLE[-$item][( -0 )],4)])
};
	if(( ( TABLE[selectJSON(4,5)][selectUrl(ROWS,TABLE[insertElement() < -( ROWS )][TABLE[-5][ROWS]])] == generateStringCallback() ) )){
	-callNum(6);
	if(getNum()){
	-COLS;
	$item *= -( ( selectLong(removeContent(updateNumber(),-downloadCollection($varString),( -uploadInfo(( ROWS ) <= -9) )),$name,COLS) ) ) <= -removeJSON(-( ( ROWS ) ),( insertNumber(-ROWS - ( -1 != addElement() )) ),9 + -8 - ROWS /\ -$position);
	generateContent(8)
}
} else {

};
	-doArray($stat,( $array ),ROWS)
} else {
	if(( insertUrl($element,2) )){
	$name += 2
} else {
	if(-ROWS + 6){
	( -$file \/ calcCollection($char,updateEnum(-TABLE[$boolean][uploadString(TABLE[( -processStatus(-( removeResponseFast() )) )][$file],TABLE[$url][$number],-( ( ROWS ) /\ 3 ) >= COLS == -10 /\ 6 / ( ( getNum(COLS \/ ROWS - 3 * 3 \/ -downloadInfo(-$file - ( ( $name ) ) <= TABLE[--ROWS \/ ROWS][$myNumber]),$lastFile) ) ) <= -downloadInteger())],8 /\ ( 2 ),3) - generateBoolean(( -ROWS ))) > ROWS )
} else {
	( ( -( COLS ) ) ) / ( 10 );
	if(ROWS > 2){

} else {
	if(downloadEnum(5,3,( 6 )) / COLS){
	--callXML(( $name ));
	COLS;
	$item -= ( -( TABLE[COLS][( TABLE[addLong(doBoolean(1),( -getId(calcResponseError(1,5),( ( ( processMessage(COLS) ) ) )) ))][( ( -( -( 8 >= $array - $array ) ) ) ) \/ $secondArray] )] ) ) /\ TABLE[downloadLongError(( ( setArrayFirst(-$number / ( -1 ),7,( removeResponse(-$file / $value /\ TABLE[3][COLS]) )) != -setResponse() == ( ( $integer ) ) * generateString($secondInteger) ) ))][doArray(processMessage(-( TABLE[-downloadModuleSecurely() != $array][-7] ),-$position)) - $element]
} else {
	( COLS );
	0
};
	ROWS;
	if(----addLong(COLS,( -2 ),2)){
	$item *= ( TABLE[-TABLE[COLS + calcUrl(7)][( 4 ) * ( 6 )]][getLog($position)] ) /\ TABLE[2][processStatus(doEnumCallback(),removeJSON(selectUrl(),( COLS ) < --COLS - ROWS,5),$url)]
} else {
	$element -= 9;
	if(downloadId(2)){
	$integer *= 2;
	$string *= 1;
	$auxUrl
};
	if(( -8 / $item )){

}
}
}
}
};
	if(updateIntegerCallback($stat,-doStringCompletely(COLS == calcDataset(--( TABLE[-setRequest(9,$name)][--TABLE[$oneValue /\ 3][insertErrorFirst($position,( $position ))]] ),TABLE[updateXML()][3] /\ 1,-processMessage(-$boolean)) * $position <= $file != 2),0)){
	$item *= $char;
	$char *= -( 8 );
	if(-insertDataset(2)){
	if(callPluginServer($boolean,updateInteger($myElement,-( --$item >= 7 )),---$myUrl)){
	if(addDatasetFast($boolean)){
	$position -= uploadCollection(( COLS ),( setLong(-$stat,TABLE[( ( 9 ) )][4],doEnum($url,( $url ) - TABLE[ROWS][$auxItem])) ))
};
	$char;
	$value += -$char
}
} else {
	if(ROWS /\ 4){
	--callTXT(-ROWS >= setEnum(( -TABLE[2][COLS] )),-updateUrlError()) <= ( ( -getContent(-( -$char )) ) < ( -$integer ) )
} else {
	$name;
	if(9){
	ROWS - -2 < selectConfig(TABLE[-ROWS][TABLE[COLS][updateLibrarySecurely()]],--downloadDataset($thisInteger,2) - ROWS,$theFile);
	-( $element )
} else {
	if($string){
	$url *= ROWS;
	if(( 6 )){
	if(TABLE[4][( $url <= -processError(----4) )]){
	$string;
	5
} else {
	if(--$name){

};
	if(5 != ( $varStat )){

}
}
}
};
	if(TABLE[3][ROWS]){
	( TABLE[( $element )][$string] );
	processBoolean(( 4 ) != callTXT($value,( ( COLS ) ),-$theName)) <= selectLong(uploadEnum(callDataset()),-$auxNumber + COLS) == COLS
}
}
};
	$myElement -= 4;
	$position
}
} else {

}
}
}
}
} else {
	TABLE[9][uploadYML($stat,6)];
	if(--7 + ( ( -8 ) )){
	$char /= $position;
	if(5){
	if(doCollection()){
	ROWS
} else {
	if(4){
	ROWS
};
	if(0){
	$position /= $secondFile
};
	if(--( ( getFile(ROWS,uploadDataset(COLS,$stat)) + -COLS * ( 9 ) ) )){

}
}
} else {
	if(callDataset(setUrl(),( 5 ))){
	$char
} else {
	$boolean += downloadFile(-updateId(processDatasetError(( ( ( ( selectResponse(processName(addYMLServer(TABLE[( insertInteger(( TABLE[$value][$boolean] ),5) + -$element / 2 != ROWS )][COLS],COLS))) ) ) ) < 0 / $name )),7))
}
};
	COLS
}
}
}
}
}
  $item = gLdeK7m;
  $value = $item + 1260;
var $thisBoolean = processNum(9,6) <= ( 5 ) <= $file
 if ($value <= "5223") {
  $array=7124;
def TABLE[$position][l] {

}
  $lastStat = 2917;
  $value = $lastStat + 07J;
def selectFloat($myPosition,$boolean,$integer){

}
 }
  $item = $value;
  return $item;
}

var $string = ( -insertUrl(2) )