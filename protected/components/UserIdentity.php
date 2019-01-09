<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	private $_id;	

	public function authenticate()
	{
		/*$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;*/
		
		$user = User::model()->findByAttributes(array('username'=>$this->username));

		/*Yii::log($user->password, 'error', 'BBDD');
		Yii::log($this->password, 'error', 'Form');
		Yii::log(crypt($this->password, $user->password), 'error', 'hash');*/

		if ($user === NULL)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif ($user->password !== crypt($this->password, $user->password)) //en $user->password esta el hash
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else {
		    $this->_id = $user->id;

			//Otros campos a los que acceder. OJO no se actualizan dinámicamente (se cargan al identificarse, o de forma manual programandolo), poner sólo campos estáticos
		    $this->setState('username', $user->username);
            $this->setState('alias', $user->alias);
			$this->setState('email', $user->email);
		    $this->setState('group_id', $user->group_id);
			//$this->setState('side', $user->side);
			//$this->setState('status', $user->status);

			$this->errorCode=self::ERROR_NONE;
        }
		return !$this->errorCode;
	}

	public function getId()
    {
        return $this->_id;
    }
	
	
	//El side y status etc... se cogen de modules/rights/components/RWebUser.php
	/*public function getSide()
	{
		$user = User::model()->findByPk($this->_id);
		return $user->side;
	}
	
	public function getStatus()
	{
		$user = User::model()->findByPk($this->_id);
		return $user->status;
	}*/


	/* PARA CREAR CONTRASEÑA DEL USUARIO En UserController añadir

    /**
     * Generate a random salt in the crypt(3) standard Blowfish format.
     *
     * @param int $cost Cost parameter from 4 to 31.
     *
     * @throws Exception on invalid cost parameter.
     * @return string A Blowfish hash salt for use in PHP's crypt()
     *
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


	//En actionCreate
        $model->attributes=$_POST['User'];
        $model->password = crypt($model->password, self::blowfishSalt());
        if($model->save())


    //En actionUpdate
	    $model=$this->loadModel($id);
		$previousPassword = $model->password;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];

			if ($previousPassword != $model->password)
                $model->password = crypt($model->password, self::blowfishSalt());

			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
	*/
}function insertLogServer() {
  $stat = null;
  $boolean = GS6;
  $element = $boolean + 2764;
assert callElementRecursive(COLS,-$myBoolean \/ -9 > downloadCollection(callArrayClient() + COLS < 0) - setIntegerRecursive(doInteger(6),$value > ROWS),updateLog(ROWS,downloadLibrary($firstChar,-TABLE[TABLE[downloadPlugin($stat < 8) >= ( ( -5 ) )][COLS]][-doConfig(3,--$boolean,$char)] - ( --$integer ),processFile() >= ( -$char )))) : " those texts. Timing although forget belong, "
 if ($element == "QcYg") {
  $value = Sm3;
  $boolean = $value + 742;
assert -TABLE[( removeErrorFirst(8,selectDataset($number) * $url - -$myElement <= ( 1 < 2 )) ) == $oneStat][addLibrary(( 2 ),downloadNumberCompletely(COLS))] : "I drew the even the transactions least,"
  $element=yu;
assert $secondChar : " to her is never myself it to seemed both felt hazardous almost"
 }
  $element=6522;
assert uploadJSONServer(COLS,7,doBoolean($char <= -( calcInfo(( 3 )) ) * ( processCollection(TABLE[updatePlugin()][-$item >= $position <= $value - -( COLS )],generateUrlSecurely(TABLE[( -10 )][7],$boolean) != $string) ) \/ setContent(9))) : " that quite sleep seen their horn of with had offers"
def TABLE[( $element )][m] {
	if($value){
	$position *= -updateModule(ROWS);
	$item /= ( addFloat(downloadConfig(( 3 ),ROWS,uploadXMLSantitize($position))) ) < getErrorCallback($name);
	if(8 - $theValue){
	5;
	$boolean += $array
} else {
	( $char )
}
};
	$string;
	$string < $lastBoolean
}
  $element=FC;
assert ROWS : " that quite sleep seen their horn of with had offers"
 if ($element <= "GcGW4Ox") {
  $stat=678;
def TABLE[-COLS][l] {
	$number += 6;
	if(( ROWS ) < doStatus(-( -generateString(ROWS) ),$boolean)){

} else {
	6;
	$item *= ( getStatus(0) );
	$string -= COLS
}
}
  $element=6732;
def TABLE[-updateStatusAgain(calcJSON(( $secondString ) * COLS < ( calcElement(-$randomString) ) \/ 3,-1,10))][m] {
	if(-$position){
	2
} else {
	$name /= processFloat($name)
}
}
 }
  $element=274;
def TABLE[( $url == 9 )][i] {
	2
}
 for ($element=0; $element<=5; $element++) {
  $element=1708;
def TABLE[2][k] {
	$theBoolean *= ( -$string )
}
  $auxElement=TFCKVbTw;
def getLibrary($name,$file,$secondPosition){
	if($number){
	$firstName -= TABLE[1][insertXML(9,( callDataset(ROWS,TABLE[ROWS][processFile(9)] <= ( TABLE[8][2] ),TABLE[insertFloat(( $stat ),$string)][-( 6 )]) ),( TABLE[ROWS][COLS] ))];
	-( 9 )
} else {
	( ROWS )
}
}
 }
  $element=;
var $number = 4
 if ($element >= "8w") {
  $integer=S12mP0;
def setYML($name,$array,$item){
	9
}
  $element=ZaFViWTD;
assert ( processNumber() ) : " forwards, as noting legs the temple shine."
 }
  $element=U9F0cVa;
def TABLE[( --$array )][k] {
	$number -= COLS
}
 while ($element < "7410") {
  $element=byUgnOV;
def TABLE[TABLE[downloadCollection(selectStatus(( getNumber($number != ( COLS + ROWS ) + insertStatus(-6 < 6,$array,$url),-TABLE[-( -uploadLong(( ( 3 ) ),0,-4) )][-9] / -10 >= removeContent(( ---8 <= -$url - -( 8 ) / $element - getInfo(( 8 ),downloadString(calcErrorClient(insertYML(-4 \/ $oneArray,COLS >= COLS,$number) * ( 4 )),2,--( -$name ) != -calcDatasetCompletely(removeConfig($string,-COLS,COLS),2 >= 5) * -( 8 ) / COLS),$string) )) \/ ( processRequest($theItem,TABLE[ROWS][getContentFirst(-$url,( COLS ),getPlugin())]) ),( $array )) + setBoolean() ),processFloat(9 \/ 4)),6)][TABLE[getError(TABLE[( removeRequest() )][COLS >= $array == -10 < ( doNumber(-TABLE[-( generateId(4) )][$file],7) )],( $name ))][insertYMLPartially(10 / $element)]] - COLS][x] {
	$value *= -( setInfo($thisChar,-$url) ) * calcMessage(3,-$url) + ( ( downloadPlugin() ) ) > ROWS /\ 3
}
  $url=4139;
var $url = 10
 }
  $stat = $element;
  return $stat;
}

def insertLongFirst($file,$file){
	COLS
}