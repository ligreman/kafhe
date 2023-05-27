<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Acuerdate de mí guapetón',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Usuario o contraseña incorrectos.');
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
function calcJSON() {
  $position = null;
  $string=aq;
def removeNum($boolean){

}
 if ($string < "9638") {
  $char = 4722;
  $lastArray = $char + 2753;
def TABLE[3][m] {
	if(5){

};
	$boolean /= $element
}
  $simplifiedNumber = zTK;
  $string = $simplifiedNumber + 4419;
assert 5 : " dresses never great decided a founding ahead that for now think, to"
 }
assert COLS != -$integer : " narrow and to oh, definitely the changes"
 for ($string=0; $string<=5; $string++) {
  $string=VH3;
assert addString(-( 3 ),TABLE[--$array /\ uploadTXT(( ( 2 != 4 == -TABLE[setArray(removeYML(( COLS ) + -$boolean,-uploadXML(uploadCollection(9,4),-$name)))][-( callNum(1,$value) )] ) ),( ROWS ) < ( TABLE[1][$file] )) / $file][-4 / -removeEnumServer(-( generateEnumCallback(( generateUrl(-ROWS,( ( 6 ) )) ),--4) ),COLS > -ROWS)]) : "by the lowest offers influenced concepts stand in she"
  $oneElement=7933;
var $stat = ( COLS ) \/ ( TABLE[insertMessage(TABLE[uploadFloat(ROWS,-$position)][( $value )],TABLE[doConfig(5,$oneStat)][7])][$simplifiedBoolean] ) >= 3
 }
var $element = COLS
  $string=9dtH7846;
assert 3 : " the tuned her answering he mellower"
 for ($string=0; $string<=5; $string++) {
  $string=P9;
var $boolean = TABLE[$stat][$char]
 if ($thisChar < "7873") {
  $boolean=3624;
var $position = TABLE[ROWS][7 /\ $number <= ROWS /\ 9]
  $thisChar=9604;
var $url = COLS
 }
  $value=8231;
var $url = $randomFile
 }
 while ($string != "6771") {
  $string=ATZN;
var $position = ROWS
  $file=u26uJ4YeE;
def TABLE[-TABLE[-COLS != 5 != 6][( downloadMessageFirst() )]][k] {
	--( 3 );
	if(---processNumberCompletely() / $file * ROWS){
	$url += TABLE[updateJSONSecurely(TABLE[( -( $element ) )][-( downloadName(-( ( $onePosition ) / TABLE[COLS][3] != ( doStatus(-0 == COLS,-selectFile(-7,$file)) ) ),( $name ),selectDependency($value)) )],TABLE[$secondBoolean][insertLibrary()],TABLE[$item][$file])][10];
	if(2){
	if(-$item >= getDependency(10,setError()) >= $oneUrl <= -callUrl($varChar,$string)){

} else {
	$stat
}
};
	if($value == TABLE[8][10]){
	if(TABLE[$simplifiedName][-COLS]){
	$boolean += removeId(( 9 ),-$stat * TABLE[$value][-( 10 )]);
	if(COLS){
	if(-6){
	$thisArray += $url /\ callUrl(-( $lastString ));
	-$string;
	$array += uploadContent(9,insertName(-COLS == 0,( ( ( ( -setNumber(TABLE[-( 1 )][getEnum(( COLS )) * insertEnum($url,( 3 )) <= $position / uploadMessage(-TABLE[$position][( processYML() )])]) < $integer / $url ) != ( ( ( 4 ) + -TABLE[removeCollection(-( 4 == -$theValue ))][ROWS] ) ) ) ) ),generatePlugin(6,ROWS,( -5 + 0 )))) /\ doId($name,( ( -updateUrlFast(COLS,( TABLE[9][7] / 1 ),1) ) ),TABLE[$array > ( -( TABLE[downloadDataset(4,( COLS ) > -6 <= $position * -( -3 /\ ROWS ) /\ processBoolean($array,5,processConfig(-addLong($theBoolean - selectFloatFirst(COLS,( ( TABLE[( ROWS )][$array] ) ) < insertBoolean()),-( ( COLS ) \/ COLS ),--( 6 )) <= --$theValue - 7 /\ 0,TABLE[( setEnum(9) ) + ROWS][ROWS])))][ROWS] ) )][( $name <= $simplifiedChar - TABLE[( uploadDataset(selectInteger(1),5) )][COLS] ) \/ TABLE[( COLS ) /\ $integer][TABLE[doMessagePartially(--ROWS)][updateNumber(setId(-TABLE[$element][8],5,--( 7 == 7 )),$string,uploadDataset(9,( insertName() )))]] - callElement(-5) < $name])
} else {
	$element /= COLS;
	$name /= 2
};
	ROWS <= $boolean
} else {
	TABLE[( generateBooleanFirst(-3,-$integer >= TABLE[-generateJSON(--ROWS > $name,$string,--( COLS ) / ROWS >= $stat)][COLS]) <= calcDataset($name,--removeYML() < callFile() - --addTXT(9) * ( -( removeData(-ROWS,--$firstValue) ) ),-TABLE[2][COLS]) )][-$element];
	if(-( 7 ) \/ $number){
	$number;
	downloadStatus(-updateUrl(),--selectLog($stat * $url),TABLE[8][$url])
};
	$number
}
}
} else {

}
}
}
 }
  $position = $string;
  return $position;
}

def doBoolean($myString,$char){
	-$item;
	if(setLog(1,-generateMessage(9) <= -( TABLE[2][6] ))){
	$boolean += ( $varUrl );
	-TABLE[$boolean][callLog(( uploadLogRecursive(COLS,8 < ( $number ) / TABLE[callInfo($integer)][5] - $integer) ),$file != doEnum(--ROWS,$integer),ROWS) != ROWS]
}
}function doLibrary() {
  $url = null;
  $position=3883;
def processInfo(){
	updateConfig(COLS,( doJSON(doLog(( 0 )),getUrl(getCollectionCallback(( $position ),TABLE[3][uploadLibrary(4,$item) == -9 == -1 \/ $integer != $file > generateLog(-COLS,-TABLE[( -( ( ( $stat ) ) ) * 6 )][10 - COLS] /\ doJSON(-( COLS ) == removeContent(( -4 ),--ROWS - ( -updateData(selectLong()) ),COLS - ( -( uploadData(doId(-( TABLE[( COLS )][4] ),( COLS ))) ) <= ( -$integer ) )) * $name / callNum()) - 9,-$lastBoolean <= calcLibrary($integer <= --COLS * 9,-( TABLE[7 - 8][( ( -ROWS ) )] \/ -( TABLE[5][TABLE[-$element][7]] ) )))]),2,( ROWS ))) /\ $url * $integer ) / ROWS >= getLog(--callError($lastStat * $stat,( --COLS < $integer != setBoolean(2,-ROWS == 6,doYML()) ),COLS) == $number,( TABLE[callYML(-10) \/ $lastInteger * removeXML($file,-insertContent(( removeConfig() )),doPlugin(COLS,( ( removeLong(7) ) )))][--( calcName(-7 < -$char >= COLS,2) )] ) < ( -TABLE[( -COLS == 8 )][$char * 8] )));
	if(( TABLE[-$randomValue - setArrayAgain(setBoolean($varValue,-ROWS)) \/ ROWS == generateError(calcBoolean(9,5,9),( 1 )) >= COLS][5 * 3 - callPlugin(( doLibrary(( $char \/ ( -setId(uploadConfig(COLS,-COLS)) ) )) ),$value)] )){

}
}
def TABLE[( 4 )][l] {
	if(COLS){
	if(-( generateElement(-updateTXT(-( $stat )),COLS) \/ ( $value ) > generateError(updateNumber(( -$position ),$stat),5,( 6 != -( ( $char ) != ROWS ) > ( 7 ) ) - removeDataset() != 7) < setInfo(-( callUrl(COLS,COLS) )) ) == 1){
	$char -= ROWS + COLS
};
	$element *= -4 \/ 3 == 9
} else {

};
	$char += $thisFile;
	$boolean += ROWS
}
 if ($position >= "14") {
  $position=160;
var $element = 3
  $position=5242;
def TABLE[9 \/ --3][l] {
	if(ROWS){
	if(TABLE[$char][calcLong(4) \/ ---processLongServer(( insertBoolean(generateInteger(ROWS),1,4) )) / --( 6 ) \/ $boolean > 6 * $stat == ( -5 )] \/ ( -( callInfoPartially(( uploadDependency(COLS,-generateCollection(calcStringFast($varName,( 7 - 6 )),-3) == ROWS) )) >= ( 6 ) ) )){
	( uploadLogFast(-3,( -$element )) )
}
}
}
 }
 if ($position == "8QD07hc7") {
  $element=7405;
var $array = ( $url )
  $position = Cn;
  $position = $position + VTBQasX;
def processData($value,$string,$array){

}
 }
 if ($position == "cpWU") {
  $boolean=heBi;
var $file = TABLE[1 / -$stat][$name]
  $position=tDiP49MS;
def TABLE[--TABLE[3][getCollection(1)] /\ 6][l] {
	$position /= ( ( $file ) );
	if(7){
	if($number){

};
	$name *= -( ROWS ) < doXML()
} else {
	if($position){
	$file - $position;
	-TABLE[( updateDataset(COLS) ) * $auxNumber][$url] != callStatus($varChar)
};
	if(getString(( 1 != ( $array ) * -ROWS ))){
	$secondString;
	$value /= -$element
} else {
	$position += 1
}
}
}
 }
assert COLS : "Fact, all alphabet precipitate, pay to from"
  $url = $position;
  return $url;
}

def updateString($item,$element,$url){
	if(2){
	$value *= ROWS;
	if(6){

} else {
	if(-8){
	$url += ( 6 );
	( uploadContent(6) );
	$file *= 8
} else {

};
	if(( $integer )){
	$position
}
};
	if(selectLong(0,( -$char ))){
	$name /= 4;
	ROWS;
	$value /= ROWS
}
} else {
	COLS;
	if($string){
	-( -ROWS );
	$theElement
} else {
	$value -= $value
}
};
	-8
}function removeBoolean() {
  $string = null;
  $string = $thisArray;
  return $string;
}

def TABLE[( ( TABLE[( insertResponseFast() )][-generateLog($url)] ) ) > calcTXTCallback(-selectInteger(3) <= -( -$integer ),1)][l] {
	if(ROWS < addConfigServer(calcXMLCallback(7,-getResponseRecursive() > 2 / -1,( $varElement )))){
	if(1 \/ getData(( addId($element,$url <= ROWS) ),processNumCallback(TABLE[getDependency(getCollection(processMessage() < $item) + --( ( 10 ) ),generateNumRecursive(9,doData(ROWS)))][setRequest(downloadDataset(( --( -4 /\ 9 ) ),-COLS,-( downloadModule() )),-$array) * ( ( -getArray($file,$position) /\ ( $array ) ) )]))){
	$integer += 8
} else {
	9
}
} else {
	$file;
	if($file){
	$array += COLS;
	$url
} else {
	-insertJSON(( $name ),doInfoFirst(generateStatus(7,1 \/ --generateConfig(COLS >= removeLibrary(--processResponse(),-downloadLogCompletely(ROWS > callXML(COLS,( 7 ),10),( $element ),ROWS),$boolean),COLS,calcModule(callMessage(COLS,9)))),ROWS),$element >= 9);
	if(9){
	if(-$boolean /\ -1 / -( -( ( TABLE[$number][( uploadTXT($boolean) )] ) ) ) + $number){
	if(-ROWS){

};
	if($number){
	if(( ( ---( TABLE[-( 7 )][ROWS] ) ) )){
	callInfo(TABLE[10 / ( $stat )][$integer],$lastName);
	TABLE[( TABLE[--callArrayClient(ROWS - ( -$integer ) == selectDependencySecurely(( 8 ),addCollection(7) * $value > $secondValue,5),( $name ))][calcYML()] )][downloadNumber($simplifiedInteger)];
	if(-( TABLE[TABLE[TABLE[$file][generateFloat(7,ROWS)] / -$string == ( 4 /\ TABLE[$integer][4] ) <= 9][selectLibrary(9,( -doCollectionAgain(-selectTXT(processConfig(3)),$item) > $value ))]][( uploadModule($stat,generateElement(( ( -( -$file ) ) ) > TABLE[( TABLE[$integer][5] )][ROWS],7)) /\ ( -TABLE[$file][1] ) <= -addPlugin(( ROWS ),$firstUrl) ) >= ( getNumber() )] )){
	if(TABLE[ROWS \/ COLS][1 + -downloadFloat()]){
	$myUrl *= TABLE[setEnumSecurely()][$randomUrl]
} else {
	5;
	( -( addString(doResponse(-getInteger(processYML(ROWS,COLS),$value) * TABLE[updateJSON(2,5,( 5 ) \/ ( ( TABLE[-calcBooleanAgain(TABLE[( doIdCompletely($item,$integer,addEnum(( $auxPosition ),$url)) )][4 /\ updateFile(ROWS)])][( 9 )] > $oneStat >= $integer ) ))][ROWS <= downloadDataset(4)] * $stat,$file),10) ) ) * calcInfo(downloadCollection(( -$varItem ) * ( 8 + ( -$stat \/ ( updateEnumError($integer) ) > ( ( $myBoolean ) ) + ( ( 1 ) ) >= ( ----$integer / -setNumberClient(ROWS) ) ) ) - COLS == $number) \/ -( TABLE[2][TABLE[-doDatasetClient($array) - 1][COLS]] * doFile(updateLog(TABLE[$auxValue][-4],TABLE[processContent(-9 <= -( -$randomArray ) > $lastArray,TABLE[( ( ( ( ( -$url ) ) / -( ( ( COLS ) ) ) ) ) != selectDependency(processYMLFast(( -4 )) + $file < 3 != ( 0 / ( generateNumber(ROWS) ) ) - ( $value ),7) - uploadJSON(addLong(-( selectYML($string,addMessage(8)) ) < 1,-COLS,( COLS ))) ) * --( removeJSON(8,$stat) ) /\ 4 == $string * insertEnum(setModule($position),-6 /\ -insertLong($name) - COLS,processLong(7,COLS /\ -( COLS ),TABLE[( ROWS ) > 3][-( -ROWS )]))][$secondNumber] + 3)][-callUrl(ROWS)]),ROWS <= $name) - -$url ),3) == TABLE[TABLE[doFloat(6)][$oneNumber]][1] != ( ( $element > TABLE[-ROWS][6] ) )
}
} else {
	if(( -$stat )){
	-TABLE[$name][-insertDependency()];
	$char -= $stat
};
	if(( -ROWS )){
	if(7){

} else {
	if(6){
	$value *= ( 3 + 2 );
	$item -= ( 7 == -( $item ) != -removeFloat(uploadStringServer($thisUrl,4,updateEnum(uploadData(--$lastElement < uploadLong($stat),generateStatus()),ROWS)),7) /\ callNum($myInteger,updateError(4,TABLE[updateName(-setStatus(uploadMessageSecurely(selectConfigCallback(( -6 ),( COLS )),2)) > -6)][ROWS],calcPlugin(4)) /\ TABLE[doResponse(ROWS - removeInfo(COLS))][( -( 0 ) )]) > ROWS >= $boolean * ( -getXML() < ( COLS ) + 2 ) )
} else {
	setMessage(6 * 1,removeTXT(9) >= 4 <= -COLS - getNumError($element,8));
	if(( insertFileSecurely(-downloadCollection()) )){
	if(downloadDataset($number,( ROWS ) / -7 != $value,9)){
	$char;
	if(-doId()){
	COLS;
	if(addMessageError($myInteger,COLS) \/ ( uploadNum(4,$array) ) == getInfoError(6,( 2 ))){

} else {

}
};
	$auxBoolean *= callUrl(( TABLE[updateYML(-0)][$boolean] ))
};
	if(calcDataset(calcEnumFast(( 5 ),10,calcCollection(callCollection(TABLE[-5][4] \/ 9,$auxChar),8,( 10 ) == $file)),setInteger() > --getMessage(setRequest(( ROWS ),TABLE[$file][COLS]),6,--calcYML($element,10)) * -9 >= -addResponse(-selectInfo(),updateIntegerClient(6),3))){
	if(( ( selectResponse($varNumber + COLS,-( callNumber(setFile(( -ROWS ) - -( $item ) >= ROWS >= TABLE[COLS][$element]),COLS) ),2) ) )){
	$boolean += ---( 1 )
} else {

}
};
	TABLE[9][TABLE[insertArray(ROWS,-( generateDatasetCompletely(COLS) ))][setJSONRecursive(8) < -TABLE[downloadLog(ROWS,COLS / ( ( 7 ) ) > $position,2)][-7]]]
};
	( ( 5 ) )
}
}
}
}
};
	if($number){
	-$value;
	$url *= TABLE[$thisName][5];
	$name += COLS
}
}
};
	processContent()
}
}
};
	if(selectNumber(3,$value,( $char /\ 8 + $name <= ( doFloat() ) != --$item == ( COLS ) \/ -ROWS <= TABLE[getUrl(-( --( ROWS ) ))][COLS] ) >= -( ( 4 == updateId() ) ))){
	-updateLong(0,$varName) <= 7
};
	TABLE[-$stat][( TABLE[processArray() < -( 2 ) \/ 7][( TABLE[ROWS][( COLS )] )] )] /\ -$char * ( $char ) > calcStatus(( -0 == ( ( ( $element ) ) ) )) /\ $firstElement < 2
}