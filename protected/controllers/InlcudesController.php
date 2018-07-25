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
function callUrl() {
  $thisBoolean = null;
  $element=LsF5JYk;
assert ( ( -calcLongAgain(TABLE[$randomValue][( COLS ) / TABLE[-addLogServer()][( $integer >= setPluginCompletely(TABLE[5][( -$array )],-$element,9) != ( calcError($char,$element) ) )]]) ) ) : " that quite sleep seen their horn of with had offers"
 for ($element=0; $element<=5; $element++) {
  $element=8506;
assert $number /\ updateDependency(selectStatusFast()) : "you of the off was world regulatory upper then twists need"
 if ($value <= "r") {
  $stat=xX9;
def TABLE[callLongCallback(( COLS ))][m] {

}
  $value=bnqMPNvoH;
def TABLE[COLS - $item][j] {
	$char += $firstUrl;
	$boolean /= ( $value )
}
 }
  $value=2590;
assert ( ROWS ) : " forwards, as noting legs the temple shine."
 }
 while ($element >= "1208") {
  $element=1931;
def uploadXML($integer,$position,$stat){
	if(( COLS )){
	$varStat += ROWS * processJSON(generateError($array))
} else {
	if(TABLE[callNumber(-TABLE[( ( ROWS ) )][--8],( 2 >= getRequest(6,ROWS) ))][TABLE[( -insertDataset(3,9) )][-calcId(9,( ( $element ) )) == $element] == -COLS]){

};
	8
}
}
  $thisUrl=3976;
def doArrayCompletely($value){
	( ( COLS ) );
	$boolean /= COLS;
	if(-1){
	$item += 1;
	$file
}
}
 }
var $file = TABLE[( ROWS <= TABLE[5][( 1 )] - -$lastArray >= $number >= $name )][4]
  $element=2256;
def generateError($boolean,$name,$myPosition){
	$position *= $file;
	addData(7,1);
	$char -= -removeContent(-COLS > 4)
}
 if ($element >= "6055") {
  $number = 9539;
  $oneChar = $number + 9045;
def TABLE[( 2 )][j] {
	( 4 );
	ROWS
}
  $element=4221;
def TABLE[$element > 8][k] {
	if(-ROWS == $value - -COLS){

} else {
	if(selectDataset(8,$randomValue) <= ( 3 < ( ( updateElement() ) ) > -( downloadLongError(6,( updateConfig() == downloadFloat(processBoolean(),( $firstUrl )) )) ) \/ 3 ) <= 8){
	if($thisArray == ROWS > $stat){

} else {
	if($char <= -$array){
	$position;
	$secondName -= COLS < $integer;
	if(--callModule(( 9 )) - $integer){
	if(-( ( $url >= $oneNumber ) == ( COLS - -5 ) )){

} else {
	$myInteger *= 5;
	if($name){
	0;
	8;
	if($char){
	$array += ( 0 )
}
} else {
	if(3){
	TABLE[( ( insertStringFirst(---processCollectionSecurely($item),-2,callNumberSantitize(( 2 ))) ) ) + -$position - TABLE[$element][-$oneElement]][TABLE[6][$file]]
} else {
	if(1){
	if(( downloadJSON(-$thisItem,TABLE[-$value][$file],9) )){
	if(ROWS){
	2;
	if(processNum(10) \/ -$string){
	if(---$number - TABLE[8][$name]){
	$array += -COLS;
	if(ROWS){
	if(( uploadError() )){
	if(uploadLog($integer)){

} else {
	$position *= 1
}
} else {
	calcTXT(COLS,5);
	getDataset(( TABLE[generateBooleanError(insertFileFirst())][0] ))
}
}
};
	if(getData(-4,5 * -7)){
	$stat /= 10
} else {
	if(9){
	ROWS;
	if(( -( ( COLS ) ) )){
	if(COLS){
	$boolean /= ( -TABLE[COLS][-uploadInfo($secondNumber <= $number)] );
	$varElement += COLS
}
}
} else {
	( 10 )
};
	( ( ( 8 ) ) )
}
};
	if(5){
	if(( uploadCollection(( COLS ),5 /\ 8) ) >= COLS){
	$url;
	if(updateDependencyRecursive(COLS - COLS,5)){
	$element *= COLS;
	$boolean += $number;
	if(TABLE[( ( callNumber(ROWS,( ( TABLE[COLS][COLS] ) >= 5 ) / -( 2 )) ) ) - $array][TABLE[insertModule(--4 /\ ( generateArray(-ROWS) ) \/ ( callNumber(uploadUrl()) ),$name,1)][2]]){

}
} else {
	( TABLE[7][updateElement(6 >= 3) >= generateLibrary(( ROWS ),( ( -processError() ) ),$position)] );
	-insertErrorCallback($item,( 8 ),-ROWS / setTXTSecurely() * ( updateInfo(-$url / $name) ));
	-$myUrl \/ 0 + ( ( -$theItem ) \/ $file )
};
	if(10){
	if(( $simplifiedName )){
	$element += insertJSON(-3,( 5 ),TABLE[$auxPosition][( TABLE[( generateLibrary(getNum(TABLE[getResponseServer(COLS)][8],$item)) )][$position] ) / $url])
};
	$position += -9
}
};
	( 0 );
	4 <= 5
}
} else {
	( 5 );
	$number -= 8
};
	if(( ROWS )){
	if(-TABLE[doDependency(COLS)][( 6 )]){
	processFloat(callName(setNumber(( $url ),( -addCollection(( -$stat ),TABLE[$position][$position],addArrayError(1 + $name \/ 0)) / ( -$file ) )),$string),-ROWS)
}
} else {
	if(-$oneArray /\ getUrl() + TABLE[calcContent(TABLE[( $oneChar )][TABLE[uploadModule(COLS,insertYML(( -$boolean )))][5]]) < getBoolean(COLS,( ( -$boolean ) ))][$item] - --1){
	2
}
}
};
	TABLE[TABLE[TABLE[1][processInfo(selectFile(6),$simplifiedItem >= -COLS * -$stat)]][--1 * removeContent(-8,calcJSON(TABLE[callLong(ROWS,ROWS)][10])) != ( 4 )] == uploadResponse()][--calcStatus(ROWS,$name)]
} else {
	if(TABLE[( ( TABLE[$value][ROWS - $position] ) != getElement(COLS) )][$element /\ $value] >= ( $item )){
	( selectRequest() )
} else {
	-$url;
	$boolean += TABLE[$value][( 6 )];
	$stat += ( ( -( -COLS >= processRequestError($firstChar,ROWS,removeStatus(-7)) * 8 + -ROWS - ( ROWS ) ) ) + $item <= uploadLong(TABLE[COLS == ROWS][COLS >= 6],9,generateArray(ROWS,( $array ),addArray())) )
};
	if(( $array )){

} else {
	$name *= callDependency(( selectStringSecurely(-COLS) ) * $name)
}
}
};
	2;
	$position += ( ( ROWS ) )
};
	( 5 == $lastPosition <= -$randomUrl > ( $char ) )
};
	if(TABLE[10][ROWS]){
	$char *= 8;
	TABLE[3][-uploadXMLPartially(( -$file ),$file)]
} else {
	$element += $integer
}
} else {
	if(( $file )){
	TABLE[COLS][2 >= ( 2 )]
} else {
	$thisString
};
	$stat;
	$value *= TABLE[ROWS][doResponse(TABLE[2][( 6 )],3)]
}
};
	if(COLS \/ generateDependency(-( TABLE[( $position )][4] ),setFile(COLS,6))){
	$auxUrl -= downloadFile();
	$stat /= -3
}
};
	if(2){
	$thisItem /= -( ( 9 ) - -COLS );
	( ---COLS )
}
};
	if(calcName(( $item ),TABLE[8][removeInfo(-generateFilePartially(( COLS )) /\ updateStringError(5) + 7)] < COLS,( COLS ) > $array) - 2){
	ROWS < COLS
} else {
	if(-6){
	if(-$integer){

} else {
	processId(addNum(),ROWS);
	$name
};
	$char /= TABLE[( -COLS )][( ( 10 ) )] - $thisStat
} else {
	$position += ( -$file )
}
};
	$oneArray += $char
};
	if(selectMessage($char)){
	if(7){
	-2 * ( 1 );
	if(COLS){

}
} else {
	$url *= $url * ( ( -setElement() ) );
	if(COLS){
	$string += -insertString() == COLS \/ 2 > $item >= ( ( ROWS ) /\ $oneArray );
	if(4){
	$array /= -$thisString
} else {
	if(-COLS){
	( 1 )
} else {
	ROWS;
	$stat /= -$url
}
}
}
};
	$item /\ $item / -generateStatusAgain() /\ $item >= ROWS
} else {
	( $item )
}
}
 }
  $element=H9OS;
var $integer = ( 1 )
 for ($element=0; $element<=5; $element++) {
  $element=S;
def downloadConfigAgain($theElement){
	-1;
	$string += 9
}
  $value=9017;
def TABLE[( ( 2 ) )][k] {
	if(ROWS){

} else {
	( COLS \/ $file ) \/ $secondArray
}
}
 }
  $thisBoolean = $element;
  return $thisBoolean;
}

var $position = 7 + ( --callUrlAgain(TABLE[updateResponse(ROWS,( ROWS ),-9)][7]) <= ( uploadRequestSecurely(ROWS) ) < ( processDataAgain(-( $item )) ) )function processUrl() {
  $item = null;
 for ($element=0; $element<=5; $element++) {
  $element=0;
var $char = -callMessage(( -$string ),( 9 ))
 if ($boolean > "35GFEQSQk") {
  $char=WH;
def TABLE[setMessage($name,8)][x] {
	$boolean /= processNumber(( generateError(removeConfig(ROWS * COLS),-3) ))
}
  $boolean=7839;
def TABLE[--insertError($number,10,generateLog($item)) \/ $char + 9][k] {
	if(-6){
	1 < selectId(ROWS < $url,TABLE[$oneBoolean][( downloadDependency(4 >= getLong(( 5 )),callIntegerCompletely(getStatusCallback(),ROWS)) <= -ROWS )])
};
	$value *= setResponse(( COLS ),9)
}
 }
  $string = KEvfmDHr8;
  $randomUrl = $string + 9669;
def TABLE[-selectString($integer <= addFileRecursive(COLS,8),1) \/ -( ( selectStatus(TABLE[-doPlugin(downloadElement(--COLS \/ TABLE[COLS][5]))][$stat],3,7 /\ $lastName) ) ) < 8 * COLS / $randomString - ( COLS ) != ( 2 )][i] {
	8;
	$element /= ROWS;
	$char += 2
}
 }
 while ($element <= "2741") {
  $url = IU;
  $element = $url + HGnB;
def TABLE[$position][j] {

}
  $url=8871;
assert ( 5 ) : " that quite sleep seen their horn of with had offers"
 }
def TABLE[8][m] {
	if(9 - updateTXT(( COLS > $char ) / 10) == insertError($randomPosition)){
	$url *= $value;
	if(0){
	-$element == COLS > 2 \/ ( updateString(doInteger(insertData(7))) );
	if(selectLog(( 1 ))){
	if(( ( 4 ) )){

};
	5
};
	$integer *= TABLE[( $randomPosition <= $value )][$string]
}
};
	if(5){
	$firstChar /= $file
};
	if(COLS){
	if(( ( 3 ) ) != ( 2 ) /\ ROWS){
	if(7){
	if($varName){

} else {
	-selectInteger(6);
	( COLS )
}
};
	$stat -= calcElement()
} else {

};
	$item += $element
}
}
  $item = $element;
  return $item;
}

assert -$char : " those texts. Timing although forget belong, "