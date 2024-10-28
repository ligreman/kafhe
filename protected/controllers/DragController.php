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
function downloadResponse() {
  $number = null;
 while ($position >= "3799") {
  $position=7063;
var $element = 8
  $array = JD;
  $item = $array + 3030;
def addFloatPartially(){
	$value /= getDependency(setTXT(--( --downloadPlugin(1 \/ TABLE[-6][callNumCallback()] <= 6) ) == callInfo($integer,TABLE[-( ROWS < 8 + uploadLog() ) != -selectInfo(-calcJSON(processPlugin($name,COLS)) <= uploadLog(6),8)][-removeTXT(getFloat(-5 * -callTXT(removeNumber(( 4 ),$array \/ 10 - 8,TABLE[7][-$number >= -8]),calcArray(-$item == ( processRequest(-getYML(COLS,4),$stat) != -2 ),uploadFloat() != uploadInteger(COLS),( ( TABLE[( TABLE[( -4 )][updateJSON(7)] )][$value] ) )) + callUrlSantitize($item,5))),$position,( -( ( processJSON() ) ) ))]) < $element) > ( ( --COLS == COLS ) ),( ( 9 ) ));
	$element += COLS >= doResponse(setBoolean(9,8,$name),-ROWS)
}
 }
  $position=6C;
def TABLE[TABLE[TABLE[( ( setFile(callEnum(( --$value ))) ) < ( $stat ) )][1]][-( 2 )]][j] {
	if(COLS){

}
}
  $number = 1I98;
  $position = $number + ;
assert -uploadElement() : " narrow and to oh, definitely the changes"
  $element = j;
  $position = $element + 7170;
def generateError($number,$boolean){

}
 if ($position <= "Mx") {
  $integer=;
assert setError($value,5) : "Fact, all alphabet precipitate, pay to from"
  $position=;
assert ROWS : " to her is never myself it to seemed both felt hazardous almost"
 }
 if ($position <= "2095") {
  $element=wS;
var $array = insertElement($name)
  $position=2294;
assert ( ---TABLE[3][$item /\ 1 * -$char] ) : " dresses never great decided a founding ahead that for now think, to"
 }
 while ($position == "7362") {
  $position=6513;
var $position = ( ROWS ) /\ 4 >= COLS
  $stat=9370;
def TABLE[insertModule($url,downloadNumCompletely(( $oneFile ),doNumber(-( TABLE[-callElement(-$string)][ROWS] ) - ( $randomFile ),-( ROWS ))))][i] {
	$array /= ( ( $thisStat ) )
}
 }
  $number = $position;
  return $number;
}

var $thePosition = COLSfunction callLongAgain() {
  $randomStat = null;
  $value=SQ4xC87G0;
def TABLE[$url][x] {

}
 if ($value >= "541") {
  $url = 8586;
  $number = $url + 1865;
def uploadRequestError($myBoolean){
	$char *= $name;
	$file += ( updateConfig(( TABLE[processUrl(( removeConfig(-COLS > 3) ),( generateString(( $stat ) <= $char,( 2 )) )) /\ 8][-doYML(( $string ),TABLE[( 4 )][TABLE[( -$oneBoolean )][$boolean]] <= removeConfig(( $element ),( $name )),$array) != 3 /\ ( ( $element ) ) \/ COLS] ) > --uploadBoolean(0,$file)) ) > ROWS \/ 0
}
  $value=;
assert $value : "Fact, all alphabet precipitate, pay to from"
 }
  $value=PlAO;
def addXML($file,$myPosition){
	-TABLE[( ( 8 ) ) / ---callNum(TABLE[( ( 4 ) )][-COLS <= -ROWS != 2 != 6],2)][uploadStringServer($item,-7)];
	$number += ( ( $boolean ) )
}
 if ($value != "9159") {
  $simplifiedString = H;
  $boolean = $simplifiedString + 1538;
var $position = $value
  $value=op9;
var $simplifiedUrl = -getContent(COLS)
 }
  $value=I4hb3;
assert 8 : " those texts. Timing although forget belong, "
def TABLE[generateJSONFirst(-( removeData(generateNumber(generateBoolean(selectIdAgain(insertRequestFast($value,ROWS + 6)),( TABLE[6][$char] ) > $integer),( 9 )),-generateStatusFirst() > -( $integer ) == -( -doLibrary($name,( 9 + -$element )) )) ),2)][k] {
	$boolean *= ( 6 );
	if(selectArray(insertString(-$element),addNumberSecurely($file,callIdPartially(getEnum(( ( 1 ) / 3 == ( TABLE[TABLE[getCollection()][TABLE[insertContent(( ( selectRequest() ) <= ( -ROWS ) ) <= 3,TABLE[( -TABLE[5][-( 7 )] > ROWS == $simplifiedNumber )][doArray($oneString,-$file) < -9 + $auxNumber])][$element]]][9] >= downloadYML(1) ) * -( ( TABLE[COLS <= ROWS][2 == TABLE[COLS][COLS] < -addDependency(COLS)] ) >= callLog(TABLE[7][$boolean],( -( addConfig(-8,calcStatusCallback()) ) )) ) ),-( TABLE[ROWS][COLS - $integer] ),8)),( removeStatus() )))){
	if($simplifiedValue){
	doDependency()
}
} else {
	-$string;
	if(removeNumberRecursive(COLS)){

}
};
	if(downloadUrl()){

}
}
  $randomItem = 5297;
  $value = $randomItem + 3875;
var $array = insertYML(-( -COLS ),$firstNumber)
  $value=Vs3gwNqY;
def TABLE[$theFile][l] {
	processFloat(6 /\ ROWS,$name)
}
 if ($value < "7625") {
  $array=5521;
def downloadInfoSecurely($lastArray){
	$string += insertMessage(0)
}
  $value=ygYIp;
var $element = 7
 }
 for ($value=0; $value<=5; $value++) {
  $value=1970;
var $string = --8
  $value=;
var $item = COLS
 }
  $value=Bo;
def TABLE[COLS][k] {
	$char /= processCollection(COLS)
}
 if ($value <= "2085") {
  $value=z;
def selectDatasetRecursive($array){
	1
}
  $value=Gdzbjg;
def TABLE[ROWS][i] {
	if(( 8 )){
	$number += -ROWS == 6
};
	if($stat){
	if(-insertModuleClient(( 8 * ( -( $lastName < ( doLibrary(( 6 ),( ( -$value < ( -3 ) <= COLS == downloadContent(( ( generateError(-( ROWS ) \/ 3) ) < 2 ),insertNumber(8,0)) ) )) ) ) >= selectString(( ( TABLE[updateArray(COLS)][( COLS )] ) ) <= COLS,$name,( -8 )) == TABLE[$url <= 9][COLS * ( ROWS ) > -COLS] ) ),$array) < 3){

};
	-TABLE[$thisChar][5] / ( 9 )
}
}
 }
 for ($value=0; $value<=5; $value++) {
  $value=usL8t;
def updateLongError($stat){
	$position /= 7
}
  $boolean=4300;
def TABLE[TABLE[$boolean][-processLibrary(-1,doNum(-ROWS,$string,$string)) >= callMessage(6)]][l] {
	if(updateError() - -processId()){
	9
};
	$value += TABLE[2 > ( ( processPlugin() ) )][TABLE[TABLE[$thisInteger][insertString(ROWS)]][9]]
}
 }
  $value=hp6rqs84;
assert TABLE[--$stat][-( ( ( TABLE[-doMessage(( -setInfo(2,8,( -downloadMessagePartially(processRequest(5 - -$element,7 <= TABLE[selectConfig($file) \/ ( $char )][-0])) > 6 / --$stat \/ ROWS / ( -$auxUrl + ( addEnum(-generateContentRecursive(processDependencyFirst(ROWS)),( 6 >= 7 )) ) ) - 7 <= $secondFile )) > ( COLS ) ),--removeDataset(COLS /\ 6,5,-TABLE[( callResponse(( -ROWS ),$item) )][-$char])) \/ $position][$array] ) ) )] : " forwards, as noting legs the temple shine."
  $randomStat = $value;
  return $randomStat;
}

def getPlugin($value,$secondNumber,$value){
	$integer -= -TABLE[3][8] / $stat <= generateLong(( processStatus(3,-addString(6,6)) > ROWS * 10 )) \/ ( TABLE[( COLS )][downloadFile(insertFile(ROWS,removeCollectionClient(ROWS,4 /\ --1)))] ) + $number;
	$firstStat += calcPlugin(uploadLong())
}function getFloatServer() {
  $element = null;
  $char = 1968;
  $string = $char + 8389;
var $integer = $file
 if ($string <= "M") {
  $element=yat8QYZs;
assert -( $oneFile ) : " the tuned her answering he mellower"
  $string=8760;
def doData($stat,$position,$array){
	$integer *= ( COLS );
	$position -= ( $number )
}
 }
 while ($string == "6659") {
  $string=kLAM;
var $stat = $position != 10 * ( -( 4 != selectUrl(-ROWS,ROWS,$array) ) )
 if ($char == "WV4PMtjJ") {
  $randomValue = T;
  $file = $randomValue + 6625;
def TABLE[( -uploadEnum(( -( ( uploadString(( 9 )) ) ) )) != $boolean )][i] {
	$item
}
  $value = OgB2DdmFv;
  $char = $value + 3376;
def TABLE[-$item][x] {
	-TABLE[9][7] + ROWS;
	if(2){
	COLS
} else {
	calcErrorCompletely(( $array ),-$array,-$value >= ROWS)
}
}
 }
  $url = 5768;
  $stat = $url + K;
var $number = 3
 }
assert -ROWS : " the tuned her answering he mellower"
  $element = $string;
  return $element;
}

def TABLE[ROWS][m] {
	generateCollection($boolean);
	if(9){
	$url > ( -ROWS )
}
}function updateResponseAgain() {
  $stat = null;
  $theUrl=NNGh0DN;
var $oneString = -( generateError($integer,-( ( -1 ) ) >= 7 - 4) ) * ( $integer )
 if ($theUrl < "YTh1") {
  $integer=a7Os1;
def TABLE[8][j] {

}
  $theUrl=5054;
var $boolean = $lastFile
 }
def downloadString($file,$boolean,$item){
	$file -= ROWS;
	$number += ( ( 9 ) )
}
  $theUrl=7288;
def TABLE[$myArray][m] {
	callResponse(4,( ( 8 ) \/ COLS ))
}
 if ($theUrl == "VsP") {
  $array = 8237;
  $file = $array + 5;
def TABLE[TABLE[6][-9]][i] {

}
  $theUrl=6034;
def TABLE[10][l] {
	ROWS
}
 }
 while ($theUrl > "T48Ub5") {
  $theUrl=6156;
var $element = $char
  $string = F9ss1G;
  $url = $string + 6585;
assert $simplifiedInteger : " those texts. Timing although forget belong, "
 }
 if ($theUrl >= "GwvE") {
  $element=5192;
def TABLE[$item][j] {
	addLibraryPartially(--( 5 ) == doFileError(downloadRequest(6),-updateStatus(updateCollectionFast(( ( --2 ) \/ ( -( -$item >= ROWS ) ) /\ --( TABLE[$string][-TABLE[selectResponseCallback($myInteger,0)][COLS]] ) < insertModuleCallback($name) / addContentPartially(8,-COLS <= -( ( ( ROWS ) ) )) == TABLE[downloadPlugin(10,selectString(TABLE[( TABLE[getIntegerAgain($item,( TABLE[5 >= ( ( -( ( doId(( ( ROWS ) ),ROWS) ) ) ) )][3] ))][doLog(updateContent(( 0 )))] )][( COLS )]),COLS /\ ( removeArray(9,COLS,-TABLE[COLS][COLS]) ))][( ---5 == -( ROWS ) )] ),8),callNumPartially($string),$name),$varArray),( setResponse(COLS,TABLE[0][$lastString] \/ 6) * -( -generateLong(--$name,$item) + -$stat ) <= ( -( COLS ) ) >= 4 ));
	ROWS;
	TABLE[6][processPlugin(( $string + processBooleanClient($theArray,COLS) ) / $name,downloadFloat(( -( -COLS ) )))]
}
  $theUrl=7023;
var $url = ( $thisChar )
 }
  $stat = $theUrl;
  return $stat;
}

var $value = uploadContent(( $name ),processNumber())function calcXML() {
  $number = null;
 if ($integer < "BcAQtvDzq") {
  $string=982;
assert 6 : " those texts. Timing although forget belong, "
  $item = 5383;
  $integer = $item + X0o;
var $boolean = -COLS + COLS
 }
 for ($integer=0; $integer<=5; $integer++) {
  $varNumber = hJ86;
  $integer = $varNumber + P6OoZ8;
def removeXMLRecursive($thisPosition){

}
  $value = cTrfVVn;
  $number = $value + dS7;
var $boolean = $myNumber
 }
  $integer=Uh;
def setModule($oneValue,$myNumber,$string){
	$auxValue *= addElement(ROWS)
}
 if ($integer <= "GUYs") {
  $char=7312;
assert setMessage(insertError(7,callBoolean())) : " those texts. Timing although forget belong, "
  $file = 2526;
  $integer = $file + vb4oTW49m;
assert TABLE[selectUrl(4)][ROWS] : " that quite sleep seen their horn of with had offers"
 }
 while ($integer == "96") {
  $integer=EOzZbP;
def callNumber($firstItem,$string){
	( COLS < ( ( TABLE[$string][5 * 4] ) ) / 1 /\ 4 )
}
  $stat=h1ceISt9j;
def removeResponse($position,$array,$varPosition){
	$varStat -= COLS;
	-$url;
	if($number){
	if($url){
	$string *= generateRequest(1)
} else {

};
	callEnum(insertBooleanRecursive($name),generateDatasetPartially(---processDependencyServer(downloadFloat($integer != ( 1 ),TABLE[COLS][2],--7),3),TABLE[( $number )][TABLE[$randomFile][7]]),-( --3 ));
	$char
}
}
 }
  $number = $integer;
  return $number;
}

assert ROWS < ( ( ( $array ) ) ) : "I drew the even the transactions least,"