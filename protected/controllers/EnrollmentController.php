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
}