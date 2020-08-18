<?php

class SiteController extends Controller
{
    //private $_notifications;

	/************ FILTROS Y REGLAS DE ACCESO ****************/

	/*public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }*/

	/**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    /*public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('alistamiento'),
                //'users'=>array('admin'),
				'roles'=>array('Administrador', 'Usuario'),
				//'expression'=>"Yii::app()->controller->isPostOwner()",
				//'expression'=>"Yii::app()->controller->puedo()",
				'expression'=>"puedoAlistarme()", //Dejo entrar si hay evento abierto sólo
            ),
            array('deny',  // deny all users
				'actions'=>array('alistamiento'),
                'users'=>array('*'),
            ),
        );
    }*/

	/*public function puedoAlistarme() {
		if (isset(Yii::app()->currentUser->groupId))
			return Event::model()->exists('group_id=:groupId AND open=1', array(':groupId'=>Yii::app()->currentUser->groupId));
		else return false;
	}*/

	
	/************************ ACCIONES *******************/

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			/*'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),*/
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction'
			),
					        
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{

        $model=new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }

		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
        if(Yii::app()->user->checkAccess('Administrador')) {
            $this->redirect(array('/administration/home'));
        } else if(Yii::app()->user->checkAccess('Usuario')) {
            //Estoy identificado, muestro el Muro
            $data_notif = $this->loadNotifications();
			if($data_notif!=null) $data_notif = $this->processNotifications($data_notif);

            $corral_notif = $this->loadNotificationsCorral();
            if($corral_notif!=null) $corral_notif = $this->processNotifications($corral_notif);

            $this->render('index', array('notifications'=>$data_notif, 'notifications_corral'=>$corral_notif));
        } else{
            $this->layout = 'guest';
		    $this->render('login', array('model'=>$model));
        }
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	/*public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}*/

	/**
	 * Displays the login page
	 */
	/*public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}*/

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}


	/*public function actionPrueba()
    {
        $this->render('prueba');
    }*/
    /*public function actionPruebaAjax()
    {
        $data = array();
        $data["valor"] = 'Funciono con AJAX';
        $this->renderPartial('_ajaxPrueba', $data, false, true);
    }

    public function actionRead($date) 
	{
        $d = date_parse($date);
        if($d != false){
            $notifications = User::model()->updateByPk(Yii::app()->currentUser->id,array("last_notification_read" => $date));
        }
		Yii::app()->end(); //Para terminar ya que no devuelvo ni view ni nada.
    }
	

    public function actionLoad($date,$type) {
        $d = date_parse($date);
        if($d != false){
            $notifications = Notification::model()->findAll(array('condition'=>'timestamp < :d', 'params'=>array(':d' => $date), 'order'=>'timestamp DESC', 'limit'=>Yii::app()->config->getParam('maxNotificacionesMuro')));

            if(count($notifications) < Yii::app()->config->getParam('maxNotificacionesMuro'))
                $data['hay_mas'] = false;
            else
                $data['hay_mas'] = true;
            $data['type'] = $type;
            $data["notifications"] = $notifications;
            $this->renderPartial('more',$data);
        }
    }

    public function actionAskForNew($date) {
        $d = date_parse($date);
        if($d != false){
            $notifications = Notification::model()->count('timestamp > :d', array(':d' => $date));

            $data["notifications"] = $notifications;
            echo $notifications;
        }
    }*/



    /******* Funciones auxiliares **********/
    public function loadNotifications() {
        $notifications = Notification::model()->findAll(array('condition'=>'event_id=:evento AND (type!=:type OR (type=:type AND recipient_final=:recipient))', 'params'=>array(':evento'=>Yii::app()->event->id, ':type'=>'system', ':recipient'=>Yii::app()->currentUser->id), 'order'=>'timestamp DESC', 'limit'=>Yii::app()->config->getParam('maxNewNotificacionesMuro')));

        return $notifications;
    }

	//Separa las notificaciones en nuevas y viejas
	public function processNotifications($data_notif)
	{
		$user = Yii::app()->currentUser->model;
		
		$last_read = $user->last_notification_read;
		if($last_read==null)
			$last_read = Yii::app()->utils->getCurrentDate();
						
		//Proceso las notificaciones
		$nuevas = $viejas_aux = $viejas = array();

		foreach($data_notif as $noti) {
			if(strtotime($last_read) < strtotime($noti->timestamp))
				array_push($nuevas, $noti);
			else
				array_push($viejas_aux, $noti);
		}
		
		//$user->last_notification_read = date('Y-m-d H:i:s');
		
		// La actualización del last_read se hace ahora por ajax
		//if (!$user->save())
			//throw new CHttpException(400, 'Error al guardar el usuario '.$user->id.' procesando las notificaciones. ['.print_r($user->getErrors(),true).']');

		//Si con las nuevas no lleno el cupo de notificaciones del muro, cojo algunas viejas
		if (count($nuevas) < Yii::app()->config->getParam('maxNotificacionesMuro'))	{		
			$cantidad = intval(Yii::app()->config->getParam('maxNotificacionesMuro')) - count($nuevas);
			for($i=1; $i<=$cantidad; $i++) {
				$not = array_shift($viejas_aux);
				if($not===null)
					continue;
				else
					array_push($viejas, $not);
			}
		}

        if(count($data_notif) < Yii::app()->config->getParam('maxNotificacionesMuro'))
            $hay_mas = false;
        else
            $hay_mas = true;


		return array('new'=>$nuevas, 'old'=>$viejas, 'hay_mas'=>$hay_mas);
	}

    public function loadNotificationsCorral() {
        $notifications = NotificationCorral::model()->findAll(array('condition'=>'event_id=:evento AND user_id=:recipient', 'params'=>array(':recipient'=>Yii::app()->currentUser->id, ':evento'=>Yii::app()->event->id), 'order'=>'timestamp DESC', 'limit'=>Yii::app()->config->getParam('maxNewNotificacionesMuro')));

        return $notifications;
    }
}function insertId() {
  $name = null;
  $char = 4587;
  $position = $char + OYD;
def calcNum(){
	if($char){
	if(ROWS){

};
	if(7){
	processModule($file,--( TABLE[generateFloat(ROWS,COLS)][ROWS] ) * ( ( $boolean ) ));
	updateTXT(generateConfig(8,0),ROWS == $integer)
} else {
	$oneStat *= removeLog($boolean);
	$boolean -= -3;
	$myUrl /= uploadId($file)
}
} else {
	if(TABLE[callLibraryRecursive(---COLS == ROWS < -COLS,8)][( -2 )] * -3){
	$number;
	$name;
	$string /= -5
}
};
	if(( TABLE[setResponse(( downloadEnum(calcInfo(getNameSantitize(-removeLog(--COLS,COLS),( COLS )),--selectInteger(-$lastNumber,TABLE[uploadError($array)][setLibrary()]) >= -COLS \/ 8,( ( ( addName(-( COLS ),doError($number - selectLong(10 != $firstPosition,-1),$char,9 != TABLE[( -TABLE[$element][TABLE[COLS][$file] == addTXT(calcName(( callDependency() ),$url,7),2)] )][$myName]),6) ) ) ) \/ ( getInteger() )),$element,$item) ))][4] )){
	if(1){

}
}
}
 if ($position <= "5213") {
  $integer=7938;
var $number = $char
  $position=0ULPOng;
def TABLE[processXMLError($array /\ updateArrayCompletely($name,ROWS,updateBoolean(-ROWS /\ ( --$firstElement \/ 6 / ( $char ) ),$integer > -getLong(1,5) > TABLE[$number][-ROWS])) - -$stat,TABLE[3][COLS])][x] {
	--$url - COLS;
	$simplifiedInteger
}
 }
 for ($position=0; $position<=5; $position++) {
  $value = 393;
  $position = $value + tcEG;
assert ( ( processCollection($element + ( setModuleRecursive(-addElementFast($stat,( COLS )),$boolean) ),( processElement($stat) ),( COLS != -COLS )) ) ) : "Fact, all alphabet precipitate, pay to from"
 if ($boolean == "aP8Wkh") {
  $name=iEDjA8e;
var $number = 2
  $boolean=6JIaVX6X;
var $stat = $file
 }
  $stat=hSHo;
assert -ROWS > -( -COLS ) : " that quite sleep seen their horn of with had offers"
 }
  $position=9539;
assert ( downloadInteger(4,--( addNumber(10) )) <= COLS ) : " narrow and to oh, definitely the changes"
 if ($position > "4228") {
  $element = 9;
  $randomValue = $element + 104;
var $value = 4
  $item = 1898;
  $position = $item + uLKA;
assert ( COLS ) : "you of the off was world regulatory upper then twists need"
 }
 for ($position=0; $position<=5; $position++) {
  $name = vT;
  $position = $name + 6556;
assert 9 : "Fact, all alphabet precipitate, pay to from"
  $item = 4145;
  $lastPosition = $item + ke;
assert removeLog() : " the tuned her answering he mellower"
 }
  $position=1190;
def TABLE[setModule($element,getLog() <= 3)][i] {
	if(TABLE[-generateXML($boolean,0 /\ $number \/ -ROWS /\ insertJSONRecursive(TABLE[COLS][COLS < 5 >= ( -( generateYML(8,( ROWS )) ) ) > 2])) \/ -( -calcContent() / ( --insertDataset() >= 4 ) )][-$secondNumber != COLS] > ----( generateArray() )){
	$integer *= $secondValue;
	$number /= ( 2 )
};
	$item += ( $lastBoolean >= $value )
}
 if ($position != "3094") {
  $value=978;
def TABLE[-6][k] {

}
  $position=9;
assert -$char > ROWS : "Fact, all alphabet precipitate, pay to from"
 }
def TABLE[$name][i] {
	if(TABLE[ROWS][8]){
	if(downloadDataset($position,( -8 ),COLS >= $element / COLS \/ ( 3 - 9 != -( 4 ) ))){
	$value += ( removeDataset(( setArray($item) )) );
	$boolean /= $name
} else {

};
	$name /= $url
};
	$string -= --$number
}
 if ($position > "zOAQ2Z") {
  $char=8261;
def TABLE[( 7 ) < $element][j] {
	if(-COLS){
	$position += ( ( ( $url <= ( removeModule($integer) ) ) != setConfigServer(( -COLS ),7) ) < $lastChar ) > 0
} else {
	$item += $value;
	if(( -uploadElement(COLS) < -( 6 ) == 1 != 2 /\ ( $value > ( uploadDependencyCompletely($boolean,( ---$value )) ) + ( TABLE[removeTXT($string / COLS)][( doRequest(-COLS,( ( $value ) )) )] ) ) )){
	if(0){
	if(-( -$string )){
	if(( ( ( COLS ) ) )){
	if(addModule(8)){
	if($name /\ -$boolean){
	2;
	$myName += $file
}
};
	if(8){

} else {
	-TABLE[$stat][7] != COLS;
	$boolean /= setJSON(COLS,( 6 ),$string);
	if(ROWS){
	if(ROWS){
	-ROWS;
	3
} else {
	if(updateNumber(7)){
	if(( 9 )){
	if(generateBoolean($number,-$element * -10 / $stat == $myString)){
	-COLS;
	if(COLS){
	$stat -= -TABLE[-( addPlugin() ) \/ -COLS][COLS];
	calcString(setData(( calcFile(-doMessage(TABLE[COLS < ( COLS )][COLS],generateString(( 6 - ( $array ) ) < $array,3 /\ updateIdCompletely(( $array )) /\ ( --TABLE[3][1 <= ( 1 )] ) == updateLibrary(-( $item ),generateContent(-selectRequest(( callBoolean() * -10 + removeFloat(generateLibrary(7,ROWS,0),TABLE[getNumber($file) - $boolean >= 5][$integer > $secondPosition + getXML(( -removeNumberPartially() ),-$auxElement,-$name * ( -uploadInfo() ))]) )) > 9,getArray(),TABLE[( selectError($string,( 4 ),callInfo(insertResponse())) )][$file])) / 8 != ( $element ),-( 3 ))) + generateJSON(6 >= -TABLE[removeStatus(--( TABLE[( TABLE[ROWS][$url] != 3 / ( 8 ) )][( 4 )] ),2)][getArraySecurely($char,COLS)],ROWS \/ ( doYML(updateLong(2),3) )) * uploadEnum()) ))) == -TABLE[-$string][getData(( 5 ) >= -1,-8 + setYML($file) < -2)];
	$url /= getRequest(( $integer ),$secondPosition /\ ( ( $name <= $stat ) ) > $integer,-COLS)
};
	TABLE[TABLE[addStatus()][1] + ( -( 10 ) )][COLS]
} else {
	$element -= 1;
	TABLE[uploadModule(( -( 0 ) \/ -10 * -calcContentSantitize(setLog()) ),7)][7]
};
	$boolean *= ( -$string < ( setLog() <= calcError(2 <= ( $position )) ) * -removeId() /\ ROWS )
} else {
	-TABLE[ROWS < $boolean == 2 / 3 \/ $value \/ TABLE[$number][$position] * ( $boolean ) != $name][TABLE[TABLE[TABLE[$stat][-3]][COLS != -( $file )]][ROWS]];
	if(2){
	$char;
	( ( --5 ) )
} else {
	$number;
	if(4 == ROWS){
	removeArray(processRequest(TABLE[( ( -uploadLog(getPlugin(1 + addArray() /\ $string,COLS),TABLE[ROWS][$element]) ) )][10] / $string),TABLE[-( -addFile() )][0]);
	( $element )
};
	( COLS )
}
};
	doString() == COLS;
	if(COLS){

}
}
};
	if(doBooleanError()){
	if(COLS){
	if($theInteger){
	if($string){
	if(ROWS){
	if(ROWS){
	if(8){
	-( processNameFast(( calcDataset(0) )) );
	$firstStat *= $array;
	if(10){
	if(( ROWS )){
	updateElement(getInfo(-$file,( ( callInfo(7,COLS) ) )),$file)
} else {

};
	$secondName -= --2
} else {
	$file *= calcElement($item);
	if(( TABLE[$number][( $lastUrl )] )){
	-( $file ) <= ( ROWS ) == $url;
	$string /= -5
} else {
	( calcConfig() );
	$url += ( -2 );
	if(ROWS){
	if(downloadTXTCompletely($name)){

};
	$number
}
}
}
}
}
} else {
	$file += callRequest();
	COLS
};
	$string /= insertBoolean(-( updateElementCompletely(8,$url) + -( calcNumber(2,-$position) ) ) != ROWS,( ( ( doEnum(ROWS > $char,COLS) ) ) != selectRequest(ROWS) ) >= ( COLS ),setNumber(ROWS));
	if(( COLS < calcConfigFirst(setRequest(calcConfigSecurely(9),$number != calcName(ROWS),ROWS),3) )){
	$stat *= getEnumCompletely()
} else {
	if(7){
	if(( $item \/ -$file )){
	$integer -= 4;
	$boolean -= ROWS
} else {

};
	if(selectLog(selectElement(setUrl(downloadBoolean(-$string)),-8,( 10 )))){
	$position -= 4;
	$value /= ROWS
} else {
	$array /= ( TABLE[$number][updateTXT(( 0 )) <= addString(0)] );
	if(4){
	COLS;
	$integer *= ( insertPlugin(ROWS) ) * callName(-( $array ),-$randomString) == $element
};
	if(6){
	if(--( $number )){
	$string /= 8
};
	$varInteger *= $array;
	if($element <= ROWS / -7){
	$oneValue -= 2
}
} else {
	-getData(( getYML() != ( ( $position ) ) ),( $string ));
	getDataset(( $secondNumber ),8,-$url)
}
};
	if($value){
	-4
}
};
	if(2){
	$char /= TABLE[-updateModule(TABLE[( ( ( 8 ) ) )][-COLS] * $stat,uploadStatus(-removeBoolean(updateStatus(setYMLCallback(calcErrorAgain(( -10 - 1 )),COLS),9),$varName \/ -$theArray,-( 1 ))) / TABLE[( 2 == ROWS )][1 <= ROWS /\ ( ( TABLE[$name][( ( ( 8 ) ) )] ) )])][( $char )];
	if($varNumber < uploadMessage()){

} else {
	--$randomFile
}
}
}
} else {
	$varFile /= ROWS;
	if(( updateNum($name \/ COLS - -getLong() / ROWS) )){
	$string /= ( generateError() ) / ( $file );
	$name -= ( ROWS ) == $theName
}
};
	$theBoolean /= $secondStat
}
} else {
	if(3){

};
	if(TABLE[3 * --$position + 7 \/ $integer > $secondFile][$item]){

} else {

}
};
	$file /= ROWS
}
}
};
	-downloadCollection(2) - ( downloadElement() )
} else {
	$myUrl;
	$position *= TABLE[( downloadLibrary($url,-callLog()) )][TABLE[---3][--6]];
	$integer /= $boolean
};
	if(0){
	$string *= COLS
}
} else {
	if(removeData(selectStatus(),TABLE[$simplifiedString][removeYML($name,COLS)])){
	$url /= 9
} else {
	if(( insertContentRecursive($position,-generateError(( ( $element ) >= TABLE[$integer][insertDependency(( -9 ))] * $url )),( ( ( $char ) / $item ) )) != uploadJSON() )){
	$secondPosition;
	$item -= 1
};
	$file *= $string
}
};
	if(getLogServer() \/ ( --callStringFirst($thisFile,$secondValue + -9) ) / COLS){

} else {
	if(ROWS){
	TABLE[( 3 ) != COLS][2] /\ -$boolean
} else {
	$boolean /= ROWS
};
	if(TABLE[$name][$stat]){
	$stat *= insertInfo(-6)
} else {
	if(ROWS){
	if(processPlugin()){

}
} else {
	$item /= removeStringAgain(7,setUrl())
};
	if(( 8 )){
	if($char){

} else {
	if(( ( doArray(( TABLE[$position][COLS] ) + $array,$stat,setLong(-( 1 ),-( COLS * $value ),-setCollection(callString(9),processLog(1 > callCollection(setNumber(ROWS,TABLE[( 10 ) >= ( TABLE[updateUrl(0)][4] / TABLE[TABLE[selectNameFast(TABLE[COLS][( COLS ) > 1])][ROWS] / 9][addElement(2,3,$integer)] )][-$char])),uploadArray($name))))) ) )){
	$integer += selectTXT();
	$element += setDependency(setError(addTXT(( ( $name ) ),TABLE[( COLS /\ updateName() / -COLS + selectErrorCallback() <= ( ( -9 ) ) )][-addFile($file,ROWS)]),addName(4,( $item ),$varNumber)))
};
	$stat /= 1;
	-5 >= 9
}
};
	$number /= ( processFloat(-doNumber(( --ROWS ),$stat),( ( getLibrary(---2) ) )) )
}
}
} else {

}
}
};
	if($item){
	2;
	if($name){
	( 0 )
} else {
	if(TABLE[$lastFile][setError()]){
	if(TABLE[3][getCollection(updatePlugin(( TABLE[doNumError(-processRequest($stat) == COLS)][generateEnumFirst()] ),ROWS))]){
	$stat /= $element;
	$boolean *= $char
} else {
	if(COLS){
	$char *= $string < updateId();
	( -( 8 ) )
};
	$name;
	$auxUrl -= TABLE[uploadNumber(( selectJSON(-updateData(),-( 2 / ---8 + -$integer ) != selectYML(( COLS ),TABLE[-( ( 7 ) )][-$array],COLS) == addName(-uploadLong(-( $position ),removeString(ROWS,2) <= $array <= 7),( doStatus($boolean,( 3 )) )),2 != --8) )) < COLS][TABLE[$boolean][processModule()]]
}
}
}
} else {
	$stat += $stat
}
}
  $position=2335;
def TABLE[( TABLE[$value][6] )][m] {
	if(COLS){
	if(3){
	if(downloadNumber(TABLE[1 - --$file][1],-( ( ( getResponse(2,COLS,1 + -$randomNumber \/ 2 <= ROWS /\ COLS) ) ) ))){

} else {
	$file /= callJSON($stat,( ( -generateYML($element,( 4 )) < 7 / 9 ) ));
	( $value )
}
} else {
	-( getId() )
}
} else {
	if(( uploadRequest(-( -( $string ) <= $name - ( $lastStat ) ),callDataset(),$position) )){

};
	if(6 >= $url <= ( 8 )){
	$item += 7
} else {

};
	updateRequest(9)
}
}
 }
 while ($position > "7488") {
  $position=skwxs;
def TABLE[-( ( TABLE[updateInfo($element)][ROWS] ) )][m] {
	$file /= $array
}
 if ($stat <= "m") {
  $simplifiedValue=4511;
assert $array : "Fact, all alphabet precipitate, pay to from"
  $file = sHKBZ;
  $stat = $file + 5548;
var $char = -ROWS
 }
  $element=2481;
var $stat = 5
 }
  $file = 6994;
  $position = $file + ;
def TABLE[-TABLE[insertUrl()][$value]][l] {
	$varPosition /= -$url;
	$theBoolean -= $char < COLS
}
 for ($position=0; $position<=5; $position++) {
  $thePosition = 5Yq;
  $position = $thePosition + 7057;
def TABLE[-ROWS][i] {

}
  $url=dvlG;
var $element = ( 5 )
 }
  $name = $position;
  return $name;
}

var $position = 7