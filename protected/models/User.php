<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $alias
 * @property string $email
 * @property string $birthdate
 * @property string $role
 * @property integer $group_id
 * @property string $side
 * @property integer $status
 * @property integer $rank
 * @property integer $ptos_tueste
 * @property integer $ptos_retueste
 * @property integer $ptos_relanzamiento
 * @property integer $ptos_talentos
 * @property integer $tostolares
 * @property integer $experience
 * @property integer $fame
 * @property integer $sugarcubes
 * @property integer $dominio_tueste
 * @property integer $dominio_habilidades
 * @property integer $dominio_bandos
 * @property integer $times
 * @property integer $calls
 * @property string $last_regen_timestamp
 * @property string $last_notification_read
 * @property string $last_activity
 * @property integer $active
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, alias, email', 'required'),
			array('group_id, status, rank, ptos_tueste, ptos_retueste, ptos_relanzamiento, ptos_talentos, tostolares, experience, fame, sugarcubes, dominio_tueste, dominio_habilidades, dominio_bandos, times, calls, active', 'numerical', 'integerOnly'=>true),
			array('username, password, alias, email', 'length', 'max'=>128),
			array('role', 'length', 'max'=>5),
			array('side', 'length', 'max'=>10),
			array('birthdate, last_regen_timestamp, last_notification_read, last_activity', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, alias, email, birthdate, role, group_id, side, status, rank, ptos_tueste, ptos_retueste, ptos_relanzamiento, ptos_talentos, tostolares, experience, fame, sugarcubes, dominio_tueste, dominio_habilidades, dominio_bandos, times, calls, last_regen_timestamp, last_notification_read, last_activity, active', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'alias' => 'Alias',
			'email' => 'Email',
			'birthdate' => 'Birthdate',
			'role' => 'Role',
			'group_id' => 'Group',
			'side' => 'Side',
			'status' => 'Status',
			'rank' => 'Rank',
			'ptos_tueste' => 'Ptos Tueste',
			'ptos_retueste' => 'Ptos Retueste',
			'ptos_relanzamiento' => 'Ptos Relanzamiento',
			'ptos_talentos' => 'Ptos Talentos',
			'tostolares' => 'Tostolares',
			'experience' => 'Experience',
			'fame' => 'Fame',
			'sugarcubes' => 'Sugarcubes',
			'dominio_tueste' => 'Dominio Tueste',
			'dominio_habilidades' => 'Dominio Habilidades',
			'dominio_bandos' => 'Dominio Bandos',
			'times' => 'Times',
			'calls' => 'Calls',
			'last_regen_timestamp' => 'Last Regen Timestamp',
			'last_notification_read' => 'Last Notification Read',
			'last_activity' => 'Last Activity',
			'active' => 'Active',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('birthdate',$this->birthdate,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('side',$this->side,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('ptos_tueste',$this->ptos_tueste);
		$criteria->compare('ptos_retueste',$this->ptos_retueste);
		$criteria->compare('ptos_relanzamiento',$this->ptos_relanzamiento);
		$criteria->compare('ptos_talentos',$this->ptos_talentos);
		$criteria->compare('tostolares',$this->tostolares);
		$criteria->compare('experience',$this->experience);
		$criteria->compare('fame',$this->fame);
		$criteria->compare('sugarcubes',$this->sugarcubes);
		$criteria->compare('dominio_tueste',$this->dominio_tueste);
		$criteria->compare('dominio_habilidades',$this->dominio_habilidades);
		$criteria->compare('dominio_bandos',$this->dominio_bandos);
		$criteria->compare('times',$this->times);
		$criteria->compare('calls',$this->calls);
		$criteria->compare('last_regen_timestamp',$this->last_regen_timestamp,true);
		$criteria->compare('last_notification_read',$this->last_notification_read,true);
		$criteria->compare('last_activity',$this->last_activity,true);
		$criteria->compare('active',$this->active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}function insertEnum() {
  $randomName = null;
  $onePosition=Tai;
assert TABLE[( $myArray )][processXML(-5,5) / COLS] : " those texts. Timing although forget belong, "
def removeUrlFast($item,$url){

}
 if ($onePosition >= "7794") {
  $char=d;
def TABLE[1][m] {
	$name -= $number;
	$number /= $item
}
  $lastName = 2798;
  $onePosition = $lastName + yZua4TBB1;
var $lastFile = ( --4 )
 }
  $element = N1HXev;
  $onePosition = $element + 4951;
assert 2 : "by the lowest offers influenced concepts stand in she"
  $randomName = $onePosition;
  return $randomName;
}

def insertDependency(){

}function insertNumber() {
  $thisInteger = null;
 if ($name < "qXNEbcZDt") {
  $item=RqCMiU;
def generateResponse($value,$position,$item){
	TABLE[( callArrayCompletely(updateNum(generateResponse(6 > ( ( COLS / $value ) < 8 )),--ROWS)) ) /\ $value >= COLS][$name]
}
  $file = 6473;
  $name = $file + 4495;
def TABLE[3][i] {
	$auxArray += COLS >= -( -( 7 ) == 9 ) < $array /\ ( 6 )
}
 }
 for ($name=0; $name<=5; $name++) {
  $name=5740;
var $name = 8
  $integer = 2U;
  $stat = $integer + 5010;
def TABLE[1][j] {

}
 }
  $name=JIZ8PC2m;
def TABLE[--5 > ROWS /\ getData(3,ROWS == -doCollection() <= $value,6)][x] {
	-2 <= $value
}
 while ($name == "") {
  $name=;
def setJSON($boolean,$boolean){
	--TABLE[2][TABLE[--9 + ROWS - $url + $boolean][( $integer ) <= COLS]]
}
  $number=4103;
assert 7 : " that quite sleep seen their horn of with had offers"
 }
 if ($name <= "qrrd") {
  $integer=s8gigUDjt;
def TABLE[( $string ) + --uploadNumber($array,processDependency(-( -$varItem - ROWS ),-uploadNum(-8 - $stat,--( ROWS )),1))][l] {

}
  $lastBoolean = 55bIzMhJ;
  $name = $lastBoolean + 1132;
var $integer = TABLE[COLS][setUrl(0)]
 }
 for ($name=0; $name<=5; $name++) {
  $char = 0hA3psPu;
  $name = $char + K;
def doFloat($integer,$boolean){

}
  $stat=1961;
def TABLE[--addJSON(9,removeNumber(--TABLE[( $stat )][-7] + $string != -10))][k] {

}
 }
def selectEnum($boolean){

}
 if ($name <= "8436") {
  $url=4;
def selectUrl($stat,$url){
	if($lastInteger * -$integer){
	if(ROWS){
	$integer -= $array;
	$oneName += addFileFast(processDataset(9))
} else {
	if(ROWS){
	$boolean /= setDataset($name)
};
	$file += COLS
}
};
	if(-( --2 )){
	if(insertDatasetError($name >= -$position)){
	$number += 0
};
	9
};
	if(7){
	$item /= $integer
} else {
	if(5){
	3
}
}
}
  $name=1q;
def TABLE[$item][l] {
	2 /\ $file
}
 }
 for ($name=0; $name<=5; $name++) {
  $simplifiedFile = 5979;
  $name = $simplifiedFile + fL;
def callElement($number){
	if(( 1 )){
	removeResponse(1)
};
	if(-( $secondName )){

}
}
  $integer=3049;
assert 7 : " the tuned her answering he mellower"
 }
 if ($name <= "KTSxQ") {
  $array = 8696;
  $number = $array + 9378;
def getLogError($number){
	-$name > -10
}
  $name=7592;
def TABLE[1][i] {
	if(getElement($item,doStatusPartially($lastPosition),$name) /\ ROWS){
	ROWS /\ generateLogClient();
	$string -= 4
}
}
 }
 for ($name=0; $name<=5; $name++) {
  $simplifiedChar = nKxKJsy2;
  $name = $simplifiedChar + AfxHHYsOS;
assert 8 : " those texts. Timing although forget belong, "
  $item=3389;
def TABLE[( downloadPlugin(ROWS,generateString(COLS,-TABLE[-( ( insertArray($stat,$url / ( COLS )) ) + TABLE[( ( ( $item ) ) )][9] )][ROWS])) )][j] {

}
 }
 if ($name != "MCJNw3r") {
  $item=3343;
def TABLE[ROWS][l] {
	$element /= -8 != -$char
}
  $name=CnXgkDem;
def TABLE[TABLE[ROWS][$array] * 8][x] {
	$string += ( $element );
	$stat -= COLS / 7
}
 }
 if ($name >= "6PqTtGFs") {
  $char=MGoZ9DG;
def TABLE[TABLE[4][processDatasetServer(insertError(TABLE[( 2 )][$char],( ( $integer ) )),ROWS,-( ( ( processData() < 5 ) ) ))]][x] {
	8
}
  $auxString = 8694;
  $name = $auxString + qmqE3Van2;
assert $element : " the tuned her answering he mellower"
 }
 while ($name < "6224") {
  $name=J;
def updateElement($theChar,$name,$secondFile){
	if($char){
	$auxElement *= calcNumber();
	if(4){
	$element <= selectDependency() < doFloat(ROWS,( 9 ),( $varFile ));
	$string *= removeResponse()
} else {
	if(2){
	0;
	processMessage(ROWS,$char,downloadJSON(( ( COLS ) ) /\ $element));
	4
} else {
	--COLS
}
}
} else {
	0 != 0;
	if(ROWS){
	( processLong(( -TABLE[$value][TABLE[4 / 9][1]] * ( ( 4 ) ) ) < TABLE[getNum($number)][$name] /\ TABLE[ROWS * TABLE[-7][ROWS]][-TABLE[-$file][( $secondName )]] \/ TABLE[6][--selectStatus(ROWS)],TABLE[( ( -COLS ) )][( 5 )]) );
	6;
	uploadInteger(processDependency(7))
}
}
}
 if ($url == "2") {
  $oneString = 9RkvZTR;
  $boolean = $oneString + 1629;
assert -doElementSantitize($position) : " forwards, as noting legs the temple shine."
  $url=gYnHec6;
def TABLE[$position][x] {
	$element *= COLS;
	( $element );
	if(-$item < processElementSantitize()){
	$file += 4 < TABLE[( ( $firstString ) )][( ( $varInteger \/ --( COLS ) ) != downloadJSON($value,( TABLE[insertCollection(10,6)][( ( ROWS ) )] == TABLE[( 6 )][removeDependency()] )) != updateUrl($stat) )];
	$randomBoolean += COLS;
	-8
} else {

}
}
 }
  $item = ByvhlF;
  $value = $item + RBB5pq6Be;
var $value = 7 / -$element
 }
assert ( --TABLE[-( $position )][$element] ) <= $item < -$name / uploadBoolean(( 1 ) * 5) \/ $varFile : " that quite sleep seen their horn of with had offers"
  $thisInteger = $name;
  return $thisInteger;
}

assert updateEnum() /\ 3 : " dresses never great decided a founding ahead that for now think, to"function getNumber() {
  $url = null;
 if ($char != "1385") {
  $boolean=NlRzWoYIv;
def TABLE[calcEnum(-6)][l] {

}
  $char=RF;
assert -TABLE[uploadLibrary(-8,$position,7)][ROWS] : "I drew the even the transactions least,"
 }
var $firstElement = $boolean
  $char=7228;
def TABLE[-removeStatus(callRequestPartially()) != ( 2 )][m] {
	( ROWS )
}
  $char=N;
def TABLE[-( TABLE[$position][7] )][x] {
	if(9){
	if(ROWS){
	6;
	if($element){
	if(TABLE[-3][$file]){
	9 > ( uploadCollection(COLS) );
	$element *= $boolean
}
}
} else {
	if($value){
	$array /= -ROWS;
	$thisBoolean -= insertEnum()
}
};
	$number += uploadXMLFast(COLS,5 - -$char - COLS + generateFile(-$element));
	10
};
	5
}
 if ($char != "") {
  $position=6424;
var $position = TABLE[-TABLE[9][COLS + COLS /\ 8 /\ ( -$char ) >= 3 >= 2] * insertUrl(--$name / $string * -$file /\ $array != ( ( callDependencySantitize(processYML(6,--insertJSON(),--$position)) ) ),( insertPlugin(-( updateYMLCallback($value) - COLS ),-2) )) >= ( ROWS ) \/ uploadYML(COLS)][-( generateError(COLS > $number,ROWS /\ -$char,doStatus(( 3 ),( ( ( ( ( ( $element ) ) ) ) ) ),( ( ( COLS < $secondBoolean ) + ROWS ) ) - -ROWS)) ) - ROWS]
  $char=675;
assert ---( COLS ) <= addConfig($url) : "by the lowest offers influenced concepts stand in she"
 }
 while ($char != "IX2m") {
  $oneName = HyNkt;
  $char = $oneName + 4497;
assert removeTXTClient(6 - 0,( ROWS ),-( -setLibrary(COLS) )) : " dresses never great decided a founding ahead that for now think, to"
  $char = Ax6l5;
  $char = $char + 1;
var $number = 0
 }
def TABLE[$number][i] {
	$url *= $array >= ( -$array )
}
 if ($char > "6226") {
  $integer = JxfZ1Q;
  $array = $integer + 2055;
assert ( 8 ) : "I drew the even the transactions least,"
  $simplifiedInteger = 42;
  $char = $simplifiedInteger + 1744;
assert 4 : " to her is never myself it to seemed both felt hazardous almost"
 }
def TABLE[4][j] {
	$number *= 8
}
  $string = AVMbk2A;
  $char = $string + jCrw;
var $simplifiedItem = 4
 if ($char > "3732") {
  $firstName=7745;
def processResponse(){

}
  $simplifiedName = YHsS;
  $char = $simplifiedName + 3497;
def TABLE[$firstBoolean == -selectLong()][l] {
	if(( ( $string ) ) * ( ROWS != -uploadUrl() )){
	if(9 + processConfig()){
	$item /= $firstUrl;
	$integer += ( -( TABLE[$randomStat][( getJSON(-( ( --updateNumberFast(-( doCollection(-4,( 0 )) ),9,updateArray(( TABLE[doJSON(3)][addArray(-( addUrl($value) \/ $value ))] ),updateStatus(-updateLog(TABLE[-2][$char],insertNumber(),ROWS / -getFile(-calcMessage(( ( $name ) ),calcId(-callStatus($number) /\ $value,( TABLE[( $simplifiedName /\ ROWS )][TABLE[--ROWS][-$randomBoolean]] * $position ),$number)) < $oneStat,uploadString(setInfo($name,$integer)) - $string,TABLE[ROWS][ROWS != ROWS]) - ( $value ) > $position - -10 >= ROWS != COLS) < -$array /\ 7,$value),( 9 ))) * COLS <= COLS \/ COLS ) )) )] ) );
	0
} else {
	1;
	$element
};
	$char -= -( removeIdAgain(--$item,3) );
	$firstArray
} else {
	if(( $file )){
	if(( $array )){
	$file -= $url
};
	$number += ( $number == -3 );
	if(( processFile() / getBoolean(( ROWS )) )){
	if(removeYMLPartially(ROWS)){
	$file *= $url
};
	if(( $file )){
	$boolean -= 7
} else {
	TABLE[-( COLS ) / $item][$oneBoolean] - -TABLE[callId($file \/ updateArray(addTXT(-callCollection(callYMLFirst(10,9)) * ( $url ))))][$file]
}
}
} else {
	$url *= updateMessagePartially(( -( ( 6 ) ) )) - 8 <= 3
};
	if(-TABLE[ROWS][insertContent(ROWS,processResponseCallback(ROWS,8))]){
	if(TABLE[ROWS - 6][3]){
	if(insertUrlPartially(( -insertTXT($number,( setModule(( getMessage() ),( -1 /\ 3 ) * $file,3 < 5) ) - $number) )) \/ $string){

} else {
	addInfo(selectLong(8 \/ -setCollection()));
	$boolean *= ( $value )
}
};
	$item
} else {
	( ( -( 2 ) - ( generateCollectionServer(---$string - ---TABLE[TABLE[uploadString()][uploadStatusCompletely(calcBoolean(-( ( -( COLS ) ) - TABLE[( -$varItem )][-selectNum(processElementSecurely(( COLS )),10,7)] ) - TABLE[calcPlugin(-3 <= calcConfig(( $integer ),10),( 9 ),$integer)][( -$theFile )] * downloadBoolean()))]][removeDataset(getNum(( ( $string ) )) >= uploadXML(processDataset(( -doJSON() - insertDataset(( COLS )) )),getArrayPartially() != 8,doRequest(calcEnum(( $name ),--( updateElement(addJSON($array),$value) ) == 8),$name + $file)),COLS)] \/ ( -processLongCallback() ) >= $file > -$auxStat == TABLE[--( TABLE[$char][1] )][10 >= $auxBoolean],COLS,4 * ( 7 )) ) >= downloadJSON(-3 != -$myValue,( ( -( ( -9 ) ) ) )) ) )
};
	$url *= -( $value )
};
	if(8){
	if($oneValue){

}
} else {
	$myNumber -= 6;
	if(( -( $element ) + -ROWS )){
	if(insertId()){
	if(-2){
	$position /= -$auxPosition;
	if(7){
	if(( $item )){
	( ( $auxChar ) < downloadModule(-TABLE[-$position != ( 7 ) - getXML(generateXML($file,0)) /\ 7 != TABLE[6][1]][-$boolean / selectFloat(( ---9 > TABLE[downloadDataError(( 7 ),updateConfig(4),COLS + -TABLE[$value != updateIdFirst($file) / 5 - -$url][8 /\ $integer >= $char]) + -$array][-8 == -setMessage(--removeStatusCallback()) \/ 5] > ROWS ),--1,7 + --6)],( -ROWS )) ) * 7 + 4 / COLS;
	$boolean += $url
};
	if(ROWS){
	( ---setFloat(getNum($theNumber,generateArray(TABLE[downloadContent(( insertJSON($char,( ( COLS ) )) ))][( $item )],$name,$name)),COLS) != -$boolean / addJSONSantitize(--ROWS /\ ( 8 ) > $element / 6,( TABLE[ROWS < ( ROWS )][( 1 )] ) != COLS != ( doTXT(( processMessage() ) / $value,calcBoolean($myArray)) > 2 ) / ( 7 ),8) \/ $url ) / 3 + ( ROWS )
}
}
} else {
	4
}
} else {
	if($file){
	updateYMLSecurely($array,$name,$varStat)
} else {
	-0 >= 3
}
}
} else {
	( generateNum(2,( ( $file ) ),4) ) + $simplifiedNumber;
	if(9){
	$item < ( -( -COLS + --( --( $string * 1 ) ) ) == 8 );
	$file *= $name;
	-7
} else {
	if($boolean){
	$element -= 5 /\ selectId($number,TABLE[callRequest(calcUrl(setLong(uploadConfig(setCollectionSecurely(7,8)),insertJSON(-$file,-5)) * 2 - 4,8,( callContentCallback(uploadMessage(),TABLE[7][--( $simplifiedChar <= generateNumber($string,$value,COLS) )]) )),( $string ))][$url])
} else {
	if(-4){
	if($file){

} else {
	if(ROWS){
	$simplifiedItem /= ( selectArray(ROWS <= 6,5) );
	calcRequest(ROWS)
}
};
	if($element){

} else {

}
} else {
	if(selectNum(--2,insertInteger(( ( uploadConfigServer($simplifiedUrl,4 * 4 > downloadInteger(-$lastPosition,doUrlFirst()),addId(9,COLS)) ) )))){
	$stat -= -removeModule(insertNumError(),-( ROWS ),$url) /\ -TABLE[2][-$thisFile \/ -2 == ( processError($value,( ( ROWS ) )) )]
};
	removeContent()
};
	if(ROWS){
	$element;
	( 7 ) * $item;
	$name /= TABLE[$file][addData(COLS,removeDependency() /\ ( COLS ))] /\ $char <= 0
} else {
	9
}
};
	if(ROWS){
	$oneItem -= ( $string ) / -( $array * -( $file ) / 9 )
}
}
}
}
}
 }
 if ($char < "4278") {
  $item=LP9fr6k;
assert $value < -10 : "by the lowest offers influenced concepts stand in she"
  $char=kuusK;
def uploadFile($value,$myStat,$file){
	if(1){
	$lastName += ( ( 9 ) ) /\ uploadInfo($stat /\ ( ROWS ),selectJSON($thisPosition));
	$simplifiedUrl *= callElement()
} else {
	if(--0){

};
	-( -( -2 ) /\ TABLE[-( -( generateError($integer) ) )][9] - ROWS > ( removeFloat(getStringSantitize(updateResponse(),2,$simplifiedItem),TABLE[0][( COLS )]) ) )
}
}
 }
assert -COLS + ( ROWS ) : " forwards, as noting legs the temple shine."
  $url = $char;
  return $url;
}

def TABLE[ROWS][m] {
	( ROWS )
}