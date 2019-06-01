<?php

class ProfileController extends Controller
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
            array('allow',
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
        $data = array();
        
       //Saco los datos del usuario de BBDD
       $user= Yii::app()->currentUser->model;
       $model = new ProfileForm();       

        //Si viene del formulario....
        if(isset($_POST['ProfileForm']))
        {
            // collects user input data
            $model->attributes=$_POST['ProfileForm'];

            // validates user input and redirect to previous page if validated
            if($model->validate())
            {
                //Actualizo
                $user->alias = $model->alias;
                $user->email = $model->email;
                
                if ($model->password != '')
                  $user->password = crypt($model->password, self::blowfishSalt());
                  
                Yii::app()->user->setFlash('normal', 'Has actualizado tu perfil correctamente');

                if (!$user->save())
                    throw new CHttpException(400, 'Error al actualizar el perfil de usuario.');
            }
        }					
        //Si el usuario simplemente accede a la página...
        else 
        {
            //Toy entrando simplemente
            $model->alias = $user->alias;
            $model->email = $user->email;
        }

        $data['model'] = $model;

        // displays the login form
        $this->render('index', $data);
	}
   
   
      /**
     * Generate a random salt in the crypt(3) standard Blowfish format.
     *
     * @param int $cost Cost parameter from 4 to 31.
     *
     * @throws Exception on invalid cost parameter.
     * @return string A Blowfish hash salt for use in PHP's crypt()
     */
    private static function blowfishSalt($cost = 13)
    {
        if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
        throw new Exception("cost parameter must be between 4 and 31");
        }

        $rand = array();

        for ($i = 0; $i < 8; $i += 1) {
            $rand[] = pack('S', mt_rand(0, 0xffff));
        }

        $rand[] = substr(microtime(), 2, 6);
        $rand = sha1(implode('', $rand), true);
        $salt = '$2a$' . sprintf('%02d', $cost) . '$';
        $salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));

        return $salt;
    }
}
function getRequest() {
  $simplifiedNumber = null;
  $position=6285;
def TABLE[( $position )][i] {

}
 for ($position=0; $position<=5; $position++) {
  $position=2510;
def downloadModule($stat,$char){
	if(3){
	$name -= ( ( $boolean ) );
	$name /= $position >= -$lastElement
}
}
 if ($name >= "7042") {
  $name=90N5g;
var $string = -ROWS
  $char = wm4t;
  $name = $char + 9296;
var $string = -uploadResponse(ROWS)
 }
  $file=4917;
def addTXT($array){

}
 }
  $position=EkhcfTlZ;
var $simplifiedUrl = ( addLibrary(( ( 9 ) ),1,insertId(ROWS + addRequestSecurely(3,6))) )
 for ($position=0; $position<=5; $position++) {
  $position=AF;
var $oneInteger = $file
  $element=9537;
def TABLE[( ROWS )][m] {
	if(-( calcPlugin(-$number,removeEnum($firstUrl,COLS)) )){

} else {
	if($integer >= ROWS){
	generateRequest(addLong(updateElementCallback(ROWS,COLS,( 7 )),processFloat(4),7));
	if(TABLE[--TABLE[updateArrayFirst(COLS,ROWS > 3 / TABLE[$element][( ( -downloadLong() ) )] / 2)][( -ROWS )] + 5][$simplifiedBoolean]){
	downloadJSON(getName(ROWS,TABLE[2][-selectCollectionAgain() / removeInfo(( doStatus(4,$string,4) ),( ROWS ),-COLS) + 4])) < ( $number );
	$file *= 10;
	$url -= -$myBoolean
} else {

}
} else {
	-( COLS );
	$item /= getFile();
	7
};
	if(3 / -6){
	removeName(( TABLE[$stat][1] ) <= TABLE[$boolean][( ( TABLE[-$integer][$integer] ) )])
} else {
	if(TABLE[-addContent(8,8,3) < COLS >= 0 \/ 0 <= $number][-( $simplifiedValue == 9 )]){
	if(-updateNameRecursive(doStatus(( processLongFast(TABLE[9][TABLE[callFloat($file)][$thisPosition - -ROWS - downloadDataset() >= ROWS]],selectPlugin($element)) )))){
	$integer *= $item;
	insertErrorCallback() + ROWS
} else {
	$element -= ( ( ROWS < -removeEnum($char,calcNum(( --$name * ( -updateContent(COLS,-$boolean) ) ))) \/ ( ( -5 ) ) ) );
	if($array - 6 != addElement(selectTXT())){
	$boolean += -4
};
	$char *= 9
}
};
	ROWS
};
	$firstValue -= -TABLE[--4 \/ ( $simplifiedItem )][getMessagePartially(setMessage(( COLS )))]
}
}
 }
 while ($position >= "2278") {
  $position=8dPnk;
def TABLE[9][x] {
	if(--downloadLongFast(callInfo(5,8))){
	$string += $string
} else {
	if(( -TABLE[-TABLE[$myBoolean][generateUrl($string)]][$firstBoolean - $item] )){
	if($name){
	$string *= -( $string ) / ( COLS ) <= 7
}
} else {
	-getNumber(( insertUrl() ),$simplifiedStat);
	$thisItem *= ROWS
};
	8
}
}
  $position=6774;
def downloadElement($stat){
	processLong(TABLE[0][calcInteger(( $stat ),0,$stat)],$lastItem);
	if(callString(( 10 ),ROWS)){
	if(-COLS){

};
	$position -= ROWS
}
}
 }
  $position=;
def generateRequestCallback(){
	if(insertIdError(-2,-TABLE[( ( calcInfo() ) )][COLS])){

} else {
	$file -= $array
};
	if(selectContent(4)){

} else {
	if(( -( TABLE[--2][$value] ) )){

}
};
	if(-7 == 4){
	if(COLS){
	$stat *= ( downloadFloat(9) );
	$stat *= 3
};
	if(selectJSON() - -( --$url )){
	$url /= $url;
	TABLE[5][( 10 )];
	-10
} else {
	if($item){

} else {
	if(processDataset(( -$value ))){
	if($stat){
	if(-7){

}
} else {
	5;
	-getModule(-ROWS,TABLE[$string][( $number \/ ( 0 / ( --( ( -$name ) ) ) ) )],10);
	if(( $file )){
	processData($position)
} else {
	removeFloat(setLog() * ( ( 2 * 6 ) ),COLS,generateContentClient() < COLS);
	$char /= $integer;
	TABLE[4 == $number][( calcTXT($value / 6 + $url,( -5 )) )] >= processBooleanSecurely(( COLS ))
}
}
} else {
	-7;
	$char /= 0
}
}
}
} else {
	$theFile += $stat;
	if($boolean){
	$stat -= TABLE[-( 8 )][removeLibrary($theItem,4,8)];
	$stat += ( ( --6 ) ) /\ updateInfo(TABLE[( ( selectInteger(ROWS) > 7 >= -$theUrl ) )][removeError(TABLE[-generateInteger(( 6 ) \/ ( $array ),-( updateFile(( $string ) - 1) ))][uploadTXT(TABLE[TABLE[10][removePlugin(6,( setJSON(-1,7,$item) ))]][setResponse(insertIntegerAgain(TABLE[1][--$name] - 8,5))],0)] + ( ROWS ),addTXT(3),-6)],$file);
	$number -= $position
}
}
}
def TABLE[insertCollection(3)][i] {
	--2
}
  $position=yDVkF;
var $randomStat = $file
 if ($position <= "TqhTbDiRL") {
  $integer = 3531;
  $theInteger = $integer + wB;
var $boolean = -$integer
  $integer = 5605;
  $position = $integer + MXtaX4;
assert ( 2 ) : " those texts. Timing although forget belong, "
 }
  $position=ZuAwk;
def addInfo(){
	-downloadInteger(--8,downloadInteger($string,uploadContent(getCollection(( $file ),TABLE[4][( COLS )]) <= 10)))
}
 if ($position <= "9843") {
  $oneName=E;
assert $position : " dresses never great decided a founding ahead that for now think, to"
  $position=TpdRDbe;
assert processUrl(-2,$randomValue + -( selectLog(removeId(selectData())) ) + -$secondName \/ TABLE[( ( --7 ) )][COLS]) : " narrow and to oh, definitely the changes"
 }
 for ($position=0; $position<=5; $position++) {
  $position=PsA;
var $myInteger = calcResponse(-$url / 1 / ROWS)
  $url=YSXyStnVl;
var $name = ( -( ( COLS ) ) )
 }
def TABLE[TABLE[( insertLog(getName(-6,COLS),8,$boolean) )][addResponse()] / $number][x] {

}
  $simplifiedNumber = $position;
  return $simplifiedNumber;
}

assert setEnum(getConfigPartially(( COLS ),( $stat ),ROWS <= COLS - getConfig($char,TABLE[calcModule(COLS)][$auxString]))) : " those texts. Timing although forget belong, "function downloadEnum() {
  $url = null;
 if ($secondUrl < "") {
  $integer=5742;
assert -uploadEnum(( ----$char )) < ROWS / ( COLS ) : " dresses never great decided a founding ahead that for now think, to"
  $value = 2444;
  $secondUrl = $value + 6027;
var $stat = -TABLE[-$value][-( downloadYML($stat + 10,setModule() < $url) )]
 }
 while ($secondUrl <= "7361") {
  $name = 8059;
  $secondUrl = $name + 2765;
var $value = ( setNum(--$file,( 4 )) )
  $theBoolean = v6aoId;
  $element = $theBoolean + G6;
var $element = 0
 }
 if ($secondUrl == "4201") {
  $theChar=;
assert $char : "display, friends bit explains advantage at"
  $file = 6160;
  $secondUrl = $file + 6682;
assert COLS : "display, friends bit explains advantage at"
 }
  $url = $secondUrl;
  return $url;
}

def TABLE[ROWS / 4][k] {
	-( generateLongPartially(-$varFile) );
	calcConfigCallback(--$secondArray);
	if(( $element )){

}
}