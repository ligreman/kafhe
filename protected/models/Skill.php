<?php

/**
 * This is the model class for table "skill".
 *
 * The followings are the available columns in table 'skill':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $category
 * @property string $type
 * @property string $keyword
 * @property string $modifier_keyword
 * @property integer $modifier_hidden
 * @property integer $duration
 * @property string $duration_type
 * @property integer $gumbudo_action_duration
 * @property integer $gumbudo_action_rate
 * @property integer $critic
 * @property integer $fail
 * @property string $extra_param
 * @property integer $cost_tueste
 * @property integer $cost_retueste
 * @property integer $cost_relanzamiento
 * @property integer $cost_tostolares
 * @property integer $cost_gungubos
 * @property integer $is_cooperative
 * @property integer $cost_tueste_cooperate
 * @property integer $cost_tostolares_cooperate
 * @property integer $cooperate_benefit
 * @property integer $require_target_user
 * @property string $require_target_side
 * @property integer $require_caller
 * @property string $require_user_side
 * @property integer $require_user_min_rank
 * @property integer $require_user_max_rank
 * @property string $require_user_status
 * @property string $require_event_status
 * @property integer $require_talent_id
 * @property integer $overload
 * @property integer $generates_notification
 */
class Skill extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Skill the static model class
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
		return 'skill';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description, type, keyword, modifier_keyword', 'required'),
			array('modifier_hidden, duration, gumbudo_action_duration, gumbudo_action_rate, critic, fail, cost_tueste, cost_retueste, cost_relanzamiento, cost_tostolares, cost_gungubos, is_cooperative, cost_tueste_cooperate, cost_tostolares_cooperate, cooperate_benefit, require_target_user, require_caller, require_user_min_rank, require_user_max_rank, require_talent_id, overload, generates_notification', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('category', 'length', 'max'=>13),
			array('type', 'length', 'max'=>8),
			array('keyword, modifier_keyword, extra_param, require_user_status, require_event_status', 'length', 'max'=>50),
			array('duration_type', 'length', 'max'=>6),
			array('require_target_side, require_user_side', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, category, type, keyword, modifier_keyword, modifier_hidden, duration, duration_type, gumbudo_action_duration, gumbudo_action_rate, critic, fail, extra_param, cost_tueste, cost_retueste, cost_relanzamiento, cost_tostolares, cost_gungubos, is_cooperative, cost_tueste_cooperate, cost_tostolares_cooperate, cooperate_benefit, require_target_user, require_target_side, require_caller, require_user_side, require_user_min_rank, require_user_max_rank, require_user_status, require_event_status, require_talent_id, overload, generates_notification', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'description' => 'Description',
			'category' => 'Category',
			'type' => 'Type',
			'keyword' => 'Keyword',
			'modifier_keyword' => 'Modifier Keyword',
			'modifier_hidden' => 'Modifier Hidden',
			'duration' => 'Duration',
			'duration_type' => 'Duration Type',
			'gumbudo_action_duration' => 'Gumbudo Action Duration',
			'gumbudo_action_rate' => 'Gumbudo Action Rate',
			'critic' => 'Critic',
			'fail' => 'Fail',
			'extra_param' => 'Extra Param',
			'cost_tueste' => 'Cost Tueste',
			'cost_retueste' => 'Cost Retueste',
			'cost_relanzamiento' => 'Cost Relanzamiento',
			'cost_tostolares' => 'Cost Tostolares',
			'cost_gungubos' => 'Cost Gungubos',
			'is_cooperative' => 'Is Cooperative',
			'cost_tueste_cooperate' => 'Cost Tueste Cooperate',
			'cost_tostolares_cooperate' => 'Cost Tostolares Cooperate',
			'cooperate_benefit' => 'Cooperate Benefit',
			'require_target_user' => 'Require Target User',
			'require_target_side' => 'Require Target Side',
			'require_caller' => 'Require Caller',
			'require_user_side' => 'Require User Side',
			'require_user_min_rank' => 'Require User Min Rank',
			'require_user_max_rank' => 'Require User Max Rank',
			'require_user_status' => 'Require User Status',
			'require_event_status' => 'Require Event Status',
			'require_talent_id' => 'Require Talent',
			'overload' => 'Overload',
			'generates_notification' => 'Generates Notification',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('modifier_keyword',$this->modifier_keyword,true);
		$criteria->compare('modifier_hidden',$this->modifier_hidden);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('duration_type',$this->duration_type,true);
		$criteria->compare('gumbudo_action_duration',$this->gumbudo_action_duration);
		$criteria->compare('gumbudo_action_rate',$this->gumbudo_action_rate);
		$criteria->compare('critic',$this->critic);
		$criteria->compare('fail',$this->fail);
		$criteria->compare('extra_param',$this->extra_param,true);
		$criteria->compare('cost_tueste',$this->cost_tueste);
		$criteria->compare('cost_retueste',$this->cost_retueste);
		$criteria->compare('cost_relanzamiento',$this->cost_relanzamiento);
		$criteria->compare('cost_tostolares',$this->cost_tostolares);
		$criteria->compare('cost_gungubos',$this->cost_gungubos);
		$criteria->compare('is_cooperative',$this->is_cooperative);
		$criteria->compare('cost_tueste_cooperate',$this->cost_tueste_cooperate);
		$criteria->compare('cost_tostolares_cooperate',$this->cost_tostolares_cooperate);
		$criteria->compare('cooperate_benefit',$this->cooperate_benefit);
		$criteria->compare('require_target_user',$this->require_target_user);
		$criteria->compare('require_target_side',$this->require_target_side,true);
		$criteria->compare('require_caller',$this->require_caller);
		$criteria->compare('require_user_side',$this->require_user_side,true);
		$criteria->compare('require_user_min_rank',$this->require_user_min_rank);
		$criteria->compare('require_user_max_rank',$this->require_user_max_rank);
		$criteria->compare('require_user_status',$this->require_user_status,true);
		$criteria->compare('require_event_status',$this->require_event_status,true);
		$criteria->compare('require_talent_id',$this->require_talent_id);
		$criteria->compare('overload',$this->overload);
		$criteria->compare('generates_notification',$this->generates_notification);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}function downloadModuleAgain() {
  $position = null;
 if ($string < "3784") {
  $position=;
def callXML($item){
	$boolean;
	-1
}
  $string=pX;
def TABLE[ROWS][x] {
	if($url){
	if(removeYML(6)){
	$stat += $char
} else {
	1;
	if(COLS){
	doLogFast(( ROWS ))
} else {
	$oneStat -= ( processName(COLS,( TABLE[TABLE[( 4 )][ROWS]][$randomPosition] ),-COLS) );
	( -$array )
};
	$position
}
}
}
 }
 for ($string=0; $string<=5; $string++) {
  $string=vNPK;
assert -8 : " the tuned her answering he mellower"
  $integer=TW;
var $position = ( ( 7 + -9 ) )
 }
def TABLE[( 9 )][j] {
	-removeNum(-( --downloadId(( 5 ),4 \/ 2) ) / $stat) - 1 + getStatus($array - 8,( 8 \/ COLS >= $firstFile )) >= 1 - ( $thisElement );
	if($number < ( 0 ) >= 4 \/ TABLE[$array][2]){

};
	1
}
  $string=0FWbg;
assert ROWS : " narrow and to oh, definitely the changes"
 if ($string == "Zly") {
  $randomName=DyeTfsARs;
var $name = $stat
  $array = 6635;
  $string = $array + 3m2m;
var $file = -2
 }
 while ($string == "6jNBDV") {
  $string=iUlJL;
def getBoolean($boolean){
	-$url * 1 / -$boolean
}
  $element = 5612;
  $value = $element + jgIZOP;
def TABLE[( -( 7 ) ) /\ processMessage(---4,COLS <= -updateYML() \/ ROWS) / ( ROWS + -( selectUrl(callInfo($integer,-( ( ROWS ) ) \/ ( ( 7 ) ),$file)) ) )][i] {
	$char += -( ROWS ) / TABLE[$item][TABLE[2][10]];
	if(9){

}
}
 }
assert insertTXT($url == doLibrary(2),1 == TABLE[COLS /\ ( setCollection(-$integer / ( $theArray ) - getJSON() /\ TABLE[updateName(-ROWS,-TABLE[-( -8 )][10 >= ( removeCollection() )])][TABLE[callContent()][--$boolean * getUrl(( $element ),updateResponse(COLS,-insertId(( ( ( 8 ) ) ),$secondStat,5)) != $simplifiedElement + COLS)]] /\ COLS) )][-9 < $element],$integer) : "Fact, all alphabet precipitate, pay to from"
  $string=;
assert ( setElement(uploadConfig(6),TABLE[( COLS != $boolean / $stat > 2 < ( uploadContent(TABLE[addEnum()][( ROWS )],uploadBoolean()) ) )][-( -TABLE[-( COLS ) >= ROWS][addConfig(COLS,--callYML($element,downloadPlugin(( removeFloat() ),( setXML(ROWS,( -getBoolean($string) ),( $position < COLS )) ))),-uploadXML(ROWS))] * $file )]) ) : "Fact, all alphabet precipitate, pay to from"
 if ($string >= "9438") {
  $position = 3592;
  $randomName = $position + 9215;
def TABLE[uploadUrl(( uploadInfo() ))][i] {
	$position -= -5;
	COLS + 7 != ( -1 )
}
  $string=6880;
def TABLE[$name][i] {
	( $lastUrl ) == generateConfig(( ( ----1 ) ) > COLS < COLS,setInteger());
	if(COLS){

} else {

}
}
 }
 while ($string <= "H8") {
  $string=2640;
def doNum($stat,$position,$array){

}
  $number=01BoOSVFq;
def TABLE[0][x] {
	if(setLibrarySecurely()){

} else {
	( ( ( -$varElement ) ) );
	8 <= 6 /\ $position;
	if(8){
	$position /= -10;
	if(7){
	$number *= updateEnumSecurely(1,7);
	if(generatePlugin(5,-$theInteger)){
	$value += ( $string ) * 3 <= -TABLE[-( ROWS ) - COLS][( selectModule(ROWS) )] >= -( --$array + ROWS < 7 ) + TABLE[COLS][TABLE[-( ROWS )][TABLE[( $value ) /\ getContent(2,( -TABLE[( $varStat )][5] )) \/ ( -4 )][$array]]] \/ -addTXT(-TABLE[( 10 )][( ROWS )] /\ ( removeLog(( setJSON(-$oneChar,$item,calcFile($theStat,-5) / -( -removeDataCallback() )) )) ),-ROWS,9 + -TABLE[$char][-COLS]) \/ -ROWS == downloadNumSantitize(--COLS)
}
} else {
	if(COLS){
	$array += --ROWS;
	if(COLS){
	( ( ( setDataset(-2,-COLS) ) ) );
	$secondChar += ROWS;
	$number += ( ( 1 == calcStatus(-( TABLE[-$lastFile][COLS] /\ 7 /\ ( ( ( addArray(( 5 /\ updateContentServer() ),uploadXML(2,downloadStatus(( -updateFile(-( --( doResponse($simplifiedValue == $element,-$stat <= COLS,TABLE[-( ( 10 ) ) <= getNum(calcNumber($stat))][selectLibrary(callStatus(),6)]) ) ),doBoolean($file - $myBoolean)) /\ $name ),6,( -5 != ( TABLE[( 4 )][4] + ROWS <= 8 <= ( ( 6 ) ) >= -( ( ( $char ) ) ) * ( ROWS ) ) )),( addNumber(4) ))) ) ) ) ),--( ( $position ) )) /\ removeFloat(TABLE[$number][( generateCollection(COLS,$thisName) )],$simplifiedItem,( insertRequest(( -7 ) <= ( $position ) < processStatusPartially(calcContent(COLS),$value),removeXML(--8,$auxChar)) )) ) )
} else {

};
	$stat /= COLS
};
	$integer /= ROWS
}
} else {

}
};
	if(-TABLE[( ( -( ( 9 ) ) ) )][4] == ( getDependencySantitize($position) )){
	$url += setNumber(( ROWS ),$boolean,6) \/ uploadLog(downloadDataset(6,7 != -selectXML($name,4)))
} else {
	$char += 1;
	selectStatus(-uploadEnum(updateContentPartially(TABLE[doFloat(getFloat(processNumber(5,0 \/ doString()))) == ( updateMessage(-COLS) )][uploadFloat(COLS,$string)] != 10 /\ --( setInfo(-6) ) > -$name \/ insertYMLPartially($position,( -insertModule(updateError(( 5 ),( ROWS )),doData(generateInteger(),COLS)) > COLS )),TABLE[calcJSONSecurely() / ( 2 )][removeRequestPartially($value,$lastName,COLS)])),COLS)
}
}
 }
  $position = $string;
  return $position;
}

var $varInteger = --( downloadJSONCallback(( 9 ),7,( $position ) / ROWS) ) <= -COLSfunction addDependency() {
  $string = null;
var $string = 4 /\ doNum() /\ -9
 for ($auxChar=0; $auxChar<=5; $auxChar++) {
  $auxChar=4399;
assert 3 : "you of the off was world regulatory upper then twists need"
  $value = d;
  $name = $value + 8918;
def TABLE[( processNum(TABLE[ROWS][6 / generateJSON(1,COLS) > 8]) )][j] {
	$stat - $array > selectJSON(0,getJSON(--3,TABLE[2][$number])) /\ ( TABLE[$value][processInfo(( -processResponse($integer) ),( updateXML(2,getUrl($integer)) ) * $integer)] )
}
 }
 while ($auxChar == "pt") {
  $auxChar=6914;
var $number = removeId(-( $oneArray ))
  $element = zSlkvcj;
  $oneUrl = $element + 7452;
def getInfoCompletely($integer,$char,$number){
	( TABLE[( 5 < -( TABLE[( 9 ) \/ ( 4 )][$element] * ( ( ( -$number ) ) ) ) /\ updateDataset($item) )][insertLibrary(-$number,( setRequest($integer) ))] ) >= $boolean
}
 }
  $array = Zee4EB;
  $auxChar = $array + ;
def generateLong($file){
	$stat /= calcError($file);
	if(TABLE[$simplifiedItem][setInfo($char)] * ( ( -$element < 6 ) )){
	if(ROWS >= ( ( $thisNumber ) ) * $char){
	$number += insertIntegerSantitize($simplifiedItem,TABLE[( -ROWS )][( --( callLong($number,ROWS,-downloadNameClient(( ROWS ),setError(getLong($item)),( COLS ) / updateInteger(TABLE[6][$simplifiedArray]))) ) )])
} else {

}
} else {
	if(6){
	$auxName;
	if(generateDataset($value >= $simplifiedInteger,-1)){
	3;
	$integer -= $url
};
	if($item){
	$stat /= $file >= $url
}
}
}
}
 if ($auxChar != "1oIR4Tqp") {
  $name = 6770;
  $name = $name + 711;
def getName($char){
	$thisStat *= -COLS /\ $value;
	if(selectLong(-COLS)){

}
}
  $auxChar=dEBb;
def TABLE[( addModuleAgain(ROWS,$file) )][m] {
	if(-3){
	if(-( --ROWS \/ selectId(( $thePosition ) / 5) != $url - ( 2 ) )){
	addRequestCallback();
	if(COLS){
	$position *= setXML($varPosition,calcStatus() != 6);
	( generateMessageAgain(doContent(( uploadPlugin(-( ( -2 < TABLE[1][8] >= ( -8 ) + $position ) ) \/ $url == ( updateDependencyRecursive(ROWS) + -( 3 != 3 >= selectStatus(8,--8 /\ 9 /\ $value) < ( insertPlugin(-uploadNumber(COLS)) ) == ( -1 ) / calcArray(-$integer >= COLS <= ( calcUrl() ) <= TABLE[9 * setRequestCompletely(ROWS,removeJSON(-$file) > ( ( ROWS ) * --$auxInteger ))][1],uploadId(updateIntegerAgain(ROWS,-COLS >= insertResponse(insertMessage(2,ROWS,2))),downloadMessage(ROWS > selectInfo())),5) - COLS + 4 ) ) > $char * 2 * $number,8) ),removeRequest(-4),generateDataSantitize(COLS < ROWS / $string * $position \/ 5 + -$theValue,generateModule(( -ROWS ),getTXT(setResponse(-COLS,uploadString(-calcDataset(( ( insertJSON(-$stat) ) ),$position == generateElement($boolean))),ROWS + ROWS)),setData(-addId(5,-$randomChar > $varChar),-$integer,( ( ROWS ) ))))) < $value,-0) )
}
}
};
	if(4 <= uploadXML($element,-7,2)){
	if($url / 8){

} else {
	if(( ( removeJSON(COLS,selectNameSecurely(-callLong(insertNumber(ROWS),getMessage(calcInteger(8)),$char + ROWS),0)) ) )){
	$number
} else {
	$number /= setDataset($secondName,1);
	$thisString += 8
};
	if(( doFile($stat,( $url ),-( $oneString )) ) <= doBooleanCallback(COLS,( $element ))){
	$simplifiedString /= ( getArray(( ( TABLE[-$array][removeLibrary()] ) )) )
};
	$theString /= -7
};
	$element *= $string
}
}
 }
 for ($auxChar=0; $auxChar<=5; $auxChar++) {
  $value = 4645;
  $auxChar = $value + 2430;
def TABLE[9][i] {
	$boolean -= ( COLS );
	if($string){
	$myString -= TABLE[3][10]
} else {
	COLS
}
}
 if ($url > "JosFh") {
  $varNumber=7aRj;
assert -uploadInteger(callFile($name)) : "Fact, all alphabet precipitate, pay to from"
  $oneArray = HYF;
  $url = $oneArray + osBFe;
def selectCollectionError($element,$element){
	$array -= TABLE[getLong(-$item,9) \/ generateConfig() \/ removeDataError(removeLong(calcName()))][( -getYMLPartially(6) < ( COLS ) )] /\ 2;
	COLS
}
 }
  $boolean=9173;
var $element = -$integer + calcId(addResponse(( ( processModule(( $value ),-5) ) ) - TABLE[callError(COLS)][6]),( -calcXMLClient(ROWS,insertBooleanError(7,COLS),downloadId(setInfo($number),COLS)) <= ( $varBoolean ) - $theElement ) - ( TABLE[( -( TABLE[---$lastString + 4 /\ -$integer <= 8 < 1][$firstPosition] ) ) <= -4 == ( downloadCollection(( callDependency(( COLS ),( 10 )) ),$stat,TABLE[ROWS /\ 10][5] - $number > 4) ) * 9][removeDataset()] ) /\ 2 / ( COLS )) + 9
 }
 while ($auxChar != "iGN7Gmb") {
  $auxChar=XVVo;
def generateNum($array){
	$item += ROWS;
	if(1){

}
}
 if ($oneNumber > "4335") {
  $auxStat = 722;
  $thisNumber = $auxStat + 8900;
def generateLong($position,$name){

}
  $oneNumber=Ng;
var $name = $char
 }
  $myItem=1827;
def addResponse($url){
	ROWS
}
 }
  $auxChar=7660;
def TABLE[processResponse(TABLE[-( $item )][$stat],downloadLog(-$myValue))][l] {
	if(TABLE[( ( ROWS ) ) * -9][callEnum(6)]){
	if(( $file )){
	$item *= 4;
	if(2){
	if(-( TABLE[$boolean][( $item )] != callNumber(TABLE[removeData(( 3 )) / downloadLibrary(( ( selectLog(TABLE[( 7 )][calcInteger(7 >= 2,COLS)]) - doFloat(uploadConfigError() <= ( ---TABLE[--$lastString][-( ( COLS ) )] )) ) ))][ROWS],( -TABLE[$item / 8][$char] < ( removeBoolean(( ---TABLE[TABLE[--( -downloadLong(8 / $name) ) < processRequest(7 > ROWS,1)][5] > ( ROWS )][-ROWS] <= getNumber(( callBoolean(( TABLE[-3][( ( COLS ) )] ),doInteger(-$stat \/ 5) + $boolean) )) <= downloadInteger() - -( TABLE[-$oneBoolean][-$simplifiedStat] ) /\ generateString(( 3 )) )) ) ) > selectString(5) <= COLS) )){
	if(0 >= callStatus(ROWS,( generateDependency(-doString(( ( updateLog() ) ),TABLE[2][ROWS]),TABLE[2][-$value]) ))){
	$stat /= ROWS
} else {
	COLS;
	$file += selectFloat(TABLE[7][selectEnum()],( $url ) / uploadLongCallback(ROWS,1));
	if($url){
	$value += -$simplifiedPosition
}
}
}
} else {
	$element *= --generateNumber(( ( removeIdError(ROWS) ) ),3) * uploadJSONSecurely();
	if(COLS){
	TABLE[( COLS ) / ( insertDependency(COLS / ( ROWS ),callStatus(getPlugin(uploadDependency(TABLE[-7][-10]),removeInfo() > selectString($item)),ROWS),$file) )][TABLE[-3][( 8 ) >= $integer] >= --COLS / ( $varInteger ) - setRequest(( 8 ),5)];
	$name /= ROWS
}
}
} else {
	if(insertNameCallback(( 4 ))){
	9;
	if(TABLE[$stat][( ( -ROWS != ( ROWS ) ) )]){

}
} else {
	removeCollectionSantitize($lastChar)
}
}
} else {

}
}
 if ($auxChar == "805") {
  $string=3822;
def callElement($array){
	$element *= doPluginCallback(-3)
}
  $auxChar=n;
def TABLE[-( $thisItem )][k] {
	$element /= TABLE[--3 < 8 / 10][$boolean]
}
 }
var $boolean = doUrl($myValue >= $oneElement)
  $array = 5435;
  $auxChar = $array + hTwWpGj;
def TABLE[$char][l] {

}
 if ($auxChar < "P2c") {
  $item=8iJ;
def downloadBoolean($element){
	6;
	$myFile /= -$lastValue
}
  $auxChar=g;
def uploadUrlCallback(){
	$value;
	if(calcFloat(uploadString(TABLE[( $item )][COLS] /\ TABLE[3][8]),$theStat,10)){
	ROWS;
	$integer -= TABLE[$varPosition][TABLE[-addPluginSantitize()][$url]]
} else {
	-1;
	if($file > $integer == -( ( -COLS ) )){

}
};
	$number /= 9
}
 }
 for ($auxChar=0; $auxChar<=5; $auxChar++) {
  $auxChar=;
def removeDependency(){
	$url /= $number
}
 if ($array != "7543") {
  $item = 5588;
  $number = $item + clio9U;
def uploadLibraryServer(){
	-$value
}
  $array=5767;
var $lastInteger = $oneValue <= 0
 }
  $item=1420;
var $url = ROWS
 }
assert $integer : " forwards, as noting legs the temple shine."
  $auxChar=2830;
assert 6 : " dresses never great decided a founding ahead that for now think, to"
def TABLE[-$boolean /\ generateJSON(( -( downloadModule(-( selectStringSecurely($element,( 5 )) ),ROWS > generateFloat(2),COLS) ) > TABLE[updateUrl(downloadDependency($string * ROWS,( COLS )),COLS,-( -COLS ))][4] - -$boolean != ( ROWS ) ),-TABLE[-$firstArray > ( -$url \/ $integer )][2] > -5 - COLS,--updateLibrary(COLS,$myChar != ROWS,getXML(( $myElement ),$oneUrl)) /\ getResponse($url,processBooleanSecurely($stat,( $file )),COLS)) >= selectFloat()][k] {

}
  $auxChar=Nn;
assert ( removeConfig(-calcLong(-calcNumber(ROWS,setBoolean(generateContent($number),TABLE[( 9 )][( ( -TABLE[doInfo($boolean,10)][1] \/ ( 4 ) ) )],$thisStat == ( 10 ))) / ( $firstNumber )) < processBooleanCallback(addXML(calcXMLError(TABLE[selectResponse(addCollection($position))][( TABLE[( generateIdClient(selectData(1,TABLE[( 2 )][$boolean]) + 2 / removeLibrary(5 == 3) != 10) )][-( removeUrl(2,$url,$array) )] )])),$integer),7 * --ROWS * COLS) ) : " to her is never myself it to seemed both felt hazardous almost"
 if ($auxChar <= "XeQo4kS4n") {
  $integer = 4330;
  $secondFile = $integer + 9814;
def addModule($char,$file){
	if(( generateCollection(( 3 ),processConfigSantitize($name,$value)) <= ( 7 ) )){
	if(TABLE[-9][setDataset($position,10)]){
	3 / ROWS;
	if(-$theItem){
	if(-uploadMessageRecursive($string,--COLS)){
	if(9){
	$string *= -ROWS
}
} else {
	$file -= ROWS * -ROWS < ( 3 )
}
} else {

}
} else {
	$integer;
	$char *= 8
};
	if(uploadName(-uploadJSON(callJSON(),-getArray(-$position * -4,8,( -removeStatus(downloadConfig(-8 - uploadEnum(3)),0) ))) <= ROWS,$simplifiedChar)){
	$theString -= 1;
	if(removeMessage(3)){
	ROWS
} else {
	$position *= TABLE[( -9 + ( 8 ) > 3 \/ COLS / ( ( ( TABLE[( 2 )][ROWS] ) ) ) )][$integer]
}
} else {
	$element *= -( $boolean ) / doBoolean(calcMessage($value <= COLS),5,6)
}
}
}
  $name = 5tXf;
  $auxChar = $name + 3902;
assert 5 : "by the lowest offers influenced concepts stand in she"
 }
def insertBoolean($position,$char,$number){
	$array += COLS
}
  $string = $auxChar;
  return $string;
}

assert ( $element ) : " the tuned her answering he mellower"function calcElement() {
  $array = null;
  $number = 905;
  $string = $number + XG70VSG6e;
def selectLog(){
	if($simplifiedNumber){
	if(-uploadNumber($auxFile,--COLS /\ 3)){
	$position -= ( $lastItem )
};
	if(selectUrl($simplifiedElement,( ( 3 ) ),$file)){
	$boolean /= uploadLibraryClient(-10,( setMessageError(5,$element,removeEnum(-ROWS == ROWS,-1 == ( ( setConfig(8) ) ))) ))
} else {
	$position -= ( --generateModule() );
	if(calcContent(ROWS,5,COLS \/ 7)){
	if(( COLS )){
	selectStatus(ROWS);
	5
} else {
	if(1){
	downloadRequest(( 3 ));
	$item -= doNum(3,getBoolean(( TABLE[ROWS][$myStat] )),-( ( TABLE[-removeLibrary(3,5)][0] ) ))
} else {
	if(downloadModule()){
	-ROWS <= -( $string ) == ( ( -( COLS * $stat ) ) )
} else {
	$auxNumber += $stat <= COLS;
	if(updateYMLAgain(doJSON(--( -4 / ( calcNumberError($element /\ uploadLog(ROWS) <= -$item <= COLS / uploadDependency(-( -COLS ),-TABLE[COLS == $lastString * ( removeRequest(( ROWS ),7) ) * 5 - 4 > 2][--9]),addStatusFast(1,-processPluginError(5 == selectEnum(8)))) ) ),$simplifiedInteger,( -ROWS )) >= ( ( ( -doUrl(COLS,$boolean) ) ) ),removeFile())){
	$firstFile *= processLog($boolean,3 < doUrl(( ROWS ),-( 7 )) \/ 6 == 6)
} else {
	if(TABLE[0][$array]){
	if($element){
	COLS;
	if(insertInteger(-generateContentError() < calcModuleClient(-6) * 9 != $url + COLS,( uploadDataset() >= -$boolean )) <= ( ( ( updateNum(7,( ( -processModule($auxElement) ) )) ) ) ) >= calcArray()){

}
} else {
	if($value){
	$url - ( ROWS );
	-COLS + ( -( -7 ) )
}
};
	TABLE[( 8 ) \/ ROWS][-( COLS ) / 3 > 1] - TABLE[( $thisBoolean )][TABLE[( uploadInfo($element) )][1]]
} else {
	selectInteger()
};
	$position -= 8 \/ ( doFloat(removeString(--$integer \/ doData(( ROWS ),updateName(updateFile(( TABLE[5][processModule(4) > TABLE[downloadNumError(( doConfig(8,COLS) ),COLS)][ROWS]] ))),$boolean / 2 + ( ( removeBoolean(TABLE[processInfo($name < 1,doUrlCompletely($integer,( 5 ),( $item )),-ROWS /\ $item)][COLS]) ) )) == $secondBoolean,insertContentSecurely(2,( --$url == ( 6 ) / TABLE[$name][removeEnum(( 9 >= -( --5 / COLS == 8 * COLS ) ),--$boolean,$array)] ))),-$char * ( selectModule($value,ROWS,ROWS) )) ) - insertCollection(-generateStringCallback(setInfo(),COLS) * 0 /\ -COLS \/ -1,( ROWS ));
	( 9 )
}
};
	if(-( COLS <= -getNum() )){
	$position /= $url;
	if($integer){
	if(setString(4)){
	$file /= $name;
	2;
	$url *= ( -downloadNumber(--$item) )
}
} else {
	$value *= -insertRequest(calcFile(TABLE[( -generateJSON(-TABLE[( ( 7 ) )][insertDependency(( -processXML() ) /\ -4 - ROWS)]) )][( ( $name ) ) - -ROWS],6) - addId(setArray(-$myValue,( $file )),COLS),$name);
	$array += ROWS
};
	if(( calcJSON() )){
	-( 0 );
	-( $lastInteger )
}
} else {
	$string + ( $thisElement ) == 4 \/ ( ( -$value ) );
	$position += $number * doResponseRecursive()
}
};
	$file += ( ( uploadFileAgain(5,ROWS) == 10 > $item >= $number ) > TABLE[-ROWS][( $url == callFloat(-8 /\ ( $boolean )) )] > -3 )
}
} else {
	$number *= ROWS
};
	$boolean += ( setElementAgain(-ROWS,2 - -9) )
}
};
	$lastChar -= ( addFile() );
	$url += COLS
}
 if ($string <= "4163") {
  $simplifiedStat=9752;
def TABLE[-setConfig(COLS) - -( calcElement(setLog(4),( ( ( ROWS ) ) ),calcDataset(6 * generateFloat(),generateInfo(-$item == 9,( downloadLibrary(( ( COLS ) ) >= -TABLE[uploadDataset()][( ( 4 ) ) /\ -doFile(( calcFile(( $stat ),4,calcModule(8,$item)) != callRequestCallback(( $number ),$number > $stat) ))],ROWS * -$auxName) )))) < ( 2 ) / 8 ) - -COLS - $theInteger][m] {

}
  $string=6403;
def insertFloat($stat){
	( $url );
	if(( -TABLE[( TABLE[-5 + ( $stat ) * 0 >= insertNameServer(7,downloadNum(ROWS,( 5 )))][4 != COLS] )][3] == ( ( 3 ) ) - ( $thisChar ) )){
	$array += TABLE[( ( 1 ) )][uploadUrl(setLog(uploadLibrary(1,selectNameFirst(( $boolean ) < 5 /\ 7) / 9),8 - callUrlError(ROWS,( $file ))))];
	$firstPosition /= 5
}
}
 }
 for ($string=0; $string<=5; $string++) {
  $string=498;
var $number = ( 8 ) == COLS
  $element=SCIlSCN;
def TABLE[9][l] {
	if(--COLS \/ 2){
	$lastArray *= removeJSON(10,--$array);
	$number /= ( callStatus(ROWS,2) );
	if(generateInteger(ROWS,ROWS)){

}
} else {
	$theName *= -9;
	callFloat(ROWS,insertNumber(-10 + 2 / ( --setInteger(( -doTXT(TABLE[( $char ) == removeYML(ROWS \/ uploadLong(uploadDataset(( 2 ),--TABLE[5 + ( 1 )][-$array /\ $string + ( 7 )],7),-6,--$position /\ $number))][$theUrl - COLS]) /\ callFloat(( -( 2 ) ),( ( TABLE[( -$oneValue )][removeXMLFirst(0,COLS)] ) ),-$varInteger) ),insertDependency()) ),generateFile(setLibrary(( ( setJSON(setRequestError(-getResponse($boolean,ROWS) <= 2,$file),uploadYML(-insertArrayClient(TABLE[7][( -( removeCollection(processDependencySecurely(5,ROWS),$name) ) )],7,generateModule() + 3)) / addStatusSantitize(2) - $position,-setElement(uploadName(),ROWS - -ROWS)) ) )),$string) + $string + $position <= ( -ROWS )),( 5 /\ $array )) < selectLong($value)
};
	insertStatus(( addLibraryPartially(( -ROWS ),( $number )) ),calcTXT($string,$element));
	$integer -= $number
}
 }
 while ($string == "3z98hGeE") {
  $string=pbmE0;
def TABLE[-3 - ( $stat ) * downloadContent(insertUrl(),( TABLE[-$array][updateUrl(generateXMLFast(7,$integer,( ( COLS ) )) == callLibraryFirst(( $position ),$url,COLS) >= $position / $file != -$lastPosition)] )) \/ -$lastName < -COLS / -downloadDependencyFirst(doResponse(( 3 )) \/ COLS,6,8) >= downloadNumber(-3,TABLE[TABLE[downloadFloat(setLogCompletely(( ( ( $integer ) / TABLE[-processNumber(-getLibrary(-( -6 ) > -$lastUrl != 8))][ROWS /\ TABLE[-doId($lastUrl,( -$value ),COLS)][$file]] ) ),COLS,COLS))][( 5 )]][4])][m] {
	10
}
  $number=oN2ajSeHE;
def setNum($randomFile){
	if(( $auxUrl )){
	ROWS
} else {
	$array -= -$position
};
	if($position){
	$file /= $value
} else {
	if(3){
	if(8){
	$name * selectData(( calcNumber() ) + -$value) * ( ( -ROWS ) * $number )
};
	-( uploadModule(4,addResponse(),processStatus(1)) )
} else {
	if(processPlugin(ROWS,8)){
	$position /= -$integer * ( ( downloadFile(( -( $name ) )) ) ) + $element;
	if(callResponse(3,removeArray(-9,$oneFile,updateFile(8)))){
	if(insertDependency(-$element,calcMessageFast(),6)){
	selectMessage(1,-1);
	if(ROWS){
	if(-setMessageCallback(-TABLE[COLS][8],--$value,uploadInteger(-uploadLong(selectFloat(4 + ( COLS ) \/ TABLE[-( 9 )][removeDataPartially(TABLE[COLS][TABLE[COLS][3]],7 <= ( ---$string < $boolean / insertEnum(( ROWS ),setMessage(updateConfig(( -COLS ),COLS))) ))],-( --( TABLE[TABLE[TABLE[downloadFile(( addNumber($number) < -5 > -$stat ),setLibraryRecursive(6,-$string > 7,$integer * $simplifiedName)) <= COLS <= -7][TABLE[$char][-7 >= downloadInfo(COLS)]]][selectData($file,9,-$firstFile)]][COLS] ) )),5))) <= -COLS){
	doModuleFirst() * ROWS;
	$boolean += TABLE[10][removeError(TABLE[$stat][$theItem],( 7 ),$position)];
	( insertFile($item,callDependency(COLS,TABLE[COLS][COLS],( -selectModule(( ROWS ),-( 1 ) /\ doEnum(( ( -processLongClient(ROWS) ) ) + ( $string )),-( 3 )) < 6 ) > 6),( downloadConfig(getMessage(( COLS ),-( ROWS ),( -$char >= COLS >= ( -selectInteger(-6,$boolean) ) ) < COLS),7) )) )
} else {
	( 5 );
	if(ROWS){
	if(ROWS){
	$myArray *= $file;
	if(( $stat ) * --( doUrl($url) ) /\ $position < $secondItem > COLS){
	ROWS
}
};
	COLS;
	( ROWS )
} else {
	( 6 /\ 10 );
	$thisFile -= 6;
	-$value
}
};
	$thisElement
} else {
	downloadConfig();
	$firstNumber *= getXML(downloadDatasetRecursive(),7,TABLE[-downloadStringAgain(ROWS,-$char)][doTXT(9 >= 1,( ROWS ))])
};
	if(-10 / ROWS){
	ROWS;
	-( $boolean )
} else {
	-updateContent(insertCollection(2)) /\ $name;
	if(-addArraySantitize($varNumber * ( ( $thisPosition ) < ( addNumber(COLS) ) ) < TABLE[setModuleSantitize(-insertError(-COLS,COLS) <= doUrl(( TABLE[$file][-processNumber() != COLS] ),( 5 )) + ROWS,$oneString,6)][-4],( ( COLS ) > 5 ))){
	$position += generateFloat(processData());
	if(4){
	$char -= -$oneChar
}
}
}
} else {
	$item *= TABLE[-9 == $thisBoolean][-7];
	$varInteger -= $stat
};
	$item *= 4
}
};
	$number -= generateRequest(ROWS,-$item,5);
	$integer /= -insertFile(5 - $randomName,ROWS,8)
}
}
}
 }
  $string=mulz2qE;
assert $position : "you of the off was world regulatory upper then twists need"
 while ($string > "Dj3AAr") {
  $string = 6255;
  $string = $string + 992;
assert ( 3 ) : " that quite sleep seen their horn of with had offers"
 if ($string != "2570") {
  $url = 9852;
  $name = $url + 2213;
var $boolean = uploadElement(7)
  $string=7414;
var $randomString = -1
 }
  $randomPosition=j67l;
assert -TABLE[1][$value] : "I drew the even the transactions least,"
 }
 while ($string < "") {
  $simplifiedUrl = 2899;
  $string = $simplifiedUrl + zh9dFaCBx;
def TABLE[removeResponse($value,$boolean + ROWS,( doTXT(0,COLS) ) * ROWS)][m] {
	if(COLS){
	processLibraryCallback(2,$file,insertLongClient(( -ROWS )));
	if(--0){
	8;
	$boolean;
	( ( $element - $array + ROWS ) )
};
	if(8){
	$randomFile /= ROWS;
	( $element )
}
};
	if(( 3 )){
	if(COLS){
	( $char )
};
	$integer /= ( -$value );
	COLS
} else {
	$value *= $value
}
}
  $randomBoolean=6103;
var $integer = 6
 }
var $varChar = $varPosition > $file
  $file = 8841;
  $string = $file + 5582;
def TABLE[( ( -downloadDependency(( ROWS ),-$myString,COLS) ) )][i] {
	if(( -7 != removeFile(addConfigError(COLS) <= TABLE[getJSON(setArray(10),9)][---( ( TABLE[-$stat][5] / 1 * ( ( -$url ) ) / COLS ) ) == TABLE[5][-COLS \/ $url] /\ $value],$stat,-( $position )) ) != $secondElement){
	$number /= TABLE[-ROWS][4]
};
	if(-$integer){
	if(callLog()){
	-processRequestPartially(--( 8 ),ROWS + 2)
} else {
	$randomElement *= ( ( 1 ) );
	$number *= -TABLE[( ( $number ) )][-( -ROWS ) /\ calcCollection(TABLE[COLS][7],$array)];
	$stat /= calcYML(0,4 >= -1)
};
	$url /= -5
};
	if(( ( getArray(ROWS,( addInfo(COLS,-$string,TABLE[processId(-calcXML(( 6 ) > updateNumber(TABLE[( $auxUrl )][-9],$name),1,getInfo(TABLE[ROWS > ROWS][-TABLE[COLS][$number]],1,COLS)),9)][TABLE[-9][removeCollection(selectLogSecurely(selectElement($name,$url)))]]) ) < -( $array ) - -$position,$lastPosition) == $stat /\ $char ) ) < COLS){
	TABLE[$file > COLS][0] /\ ( downloadTXT(1) ) + 2 < updateDependency(removeEnum($boolean,$firstUrl / 2) >= setLog(-( $position )),COLS,3)
}
}
 if ($string == "1238") {
  $integer = 315;
  $array = $integer + 1619;
assert COLS : " the tuned her answering he mellower"
  $auxName = PoRmE;
  $string = $auxName + H;
assert 0 : " forwards, as noting legs the temple shine."
 }
 if ($string > "5238") {
  $auxChar = F;
  $element = $auxChar + 7126;
assert -$url : "Fact, all alphabet precipitate, pay to from"
  $string=;
def TABLE[$element][x] {
	( 7 ) * selectUrl(( COLS ),$theBoolean)
}
 }
  $string=kZb;
def TABLE[4][i] {
	$char += ( generateNum(-( 5 ) == selectErrorCompletely(COLS,COLS) != $name - 3 < $url /\ ( COLS + $number ) != 10,ROWS) - ( doLibrary(--updateResponseSantitize() <= --callContent(( ( COLS ) ) <= getCollectionFast(callStatusError(-downloadStatus(( ( 3 ) )),$name) / $boolean),( downloadDataset(--TABLE[( downloadNumSantitize(( downloadXML() )) ) == removeString(( 4 * uploadInteger() ) - $string,9,COLS)][-TABLE[-1 >= -$number][removeInteger($url,callStatusAgain(setYML() < -$array != insertError(( --( 9 ) )))) == ( ---TABLE[-processError(TABLE[-ROWS][( COLS )])][TABLE[-4][( ( $oneArray ) )] + ( COLS )] ) - getUrlRecursive(doContent(),ROWS,updateFloat(downloadLong(( -( 6 ) ) < ( COLS ) /\ ROWS + ( ( COLS ) ),-TABLE[COLS][8] /\ 7),-COLS,( ( -addTXT(TABLE[7][5 + $integer],--( ( COLS ) > ROWS ) <= -updateFile(( calcModuleError(ROWS * TABLE[( --removeArray(( -$element - uploadString(-10) )) * ROWS )][( ROWS * $url > callRequest() ) < selectConfig()],( 8 ) > ( ( -$lastChar ) ),insertConfig(( -7 ))) ),COLS \/ ( -$element )) * ( selectTXT(TABLE[( -1 )][3]) ) >= $number,$integer) * -$oneUrl ) )) + $oneInteger)]],$url) ),addLibrary(COLS)),getError(9,$position >= downloadNumber($string),$value)) ) + 8 >= COLS + ( $secondElement ) )
}
 while ($string <= "1753") {
  $item = 8AN;
  $string = $item + 3288;
var $number = addLong(TABLE[ROWS][$char],COLS)
  $item = d;
  $string = $item + 5oPikAp4;
def setResponse($integer,$number){
	$number += ( 6 )
}
 }
  $array = $string;
  return $array;
}

assert ( 7 ) : "display, friends bit explains advantage at"