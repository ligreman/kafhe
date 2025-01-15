<?php

/**
 * Randomness class file.
 *
 * Copyright (c) 2013, Tom Worster All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 * Redistributions of source code must retain the above copyright notice,
 * this list of conditions and the following disclaimer.
 *
 * Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in the
 * documentation and/or other materials provided with the distribution.
 *
 * Neither the name of Tom Worster nor the names of its
 * contributors may be used to endorse or promote products derived from
 * this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

class Randomness
{

    /**
     * Platform independent strlen()
     *
     * Substitute for the dangerous PHP fn {@link http://www.php.net/manual/en/function.strlen.php}
     *
     * Owing to PHP's Multibyte String overloading feature, strlen() might actually be mb_strlen()
     * in disguise and if Multibyte String's deault encoding is multi-byte, strlen() might not count
     * the number of bytes.
     *
     * @param $string
     *
     * @return int
     */
    public static function strlen($string)
    {
        return function_exists('mb_strlen')
            ? mb_strlen($string, 'ISO-8859-1')
            : strlen($string);
    }

    /**
     * Platform independent substr().
     *
     * Substitute for the dangerous PHP fn {@link http://www.php.net/manual/en/function.substr.php}
     * For explaination {@see self::strlen}
     *
     * @param string $string
     * @param int $start
     * @param int $length
     *
     * @return string
     */
    public static function substr($string, $start = 0, $length = null)
    {
        if (func_num_args() < 3) {
            $length = self::strlen($string);
        }
        return function_exists('mb_substr')
            ? mb_substr($string, $start, $length, 'ISO-8859-1')
            : substr($string, $start, $length);
    }

    public static function warn($msg)
    {
        if (class_exists('Yii')) {
            /** @noinspection PhpUndefinedClassInspection */
            /** @noinspection PhpUndefinedMethodInspection */
            Yii::log($msg, 'warning', 'security');
        } else {
            error_log($msg);
        }
    }

    /**
     * Generate a pseudo random block of data using several sources.
     *
     * No appology for the dreadful nonsense hackery! You have been warned. But this is
     * possibly better than using only mt_rand which is not really random at all.
     *
     * @param bool $warn set to log a warning when the function is called
     *
     * @return string of 64 pseudo random bytes
     */
    public static function pseudoRanBlock($warn = true)
    {
        if ($warn) {
            self::warn('Using ' . get_class() . '::pseudoRanBlock non-ctypto_strong bytes');
        }

        /**
         * @var array Keeps each pseudo-random datum found as a string
         */
        $r = array();

        // Get some data from mt_rand()
        for ($i = 0; $i < 32; ++$i) {
            $r[] = pack('S', mt_rand(0, 0xffff));
        }

        // On unixy sustems the numerical values in ps, uptime and iostat ought to be fairly
        // unpredictable. Gather the non-zero digits from those
        foreach (array('ps', 'uptime', 'iostat') as $cmd) {
            @exec($cmd, $s, $ret);
            if (is_array($s) && $s && $ret === 0) {
                foreach ($s as $v) {
                    if (false !== preg_match_all('/[1-9]+/', $v, $m) && isset($m[0])) {
                        $r[] = implode('', $m[0]);
                    }
                }
            }
        }

        // Gather the current time's microsecond part. Note: this is only a source of entropy on
        // the first call! If multiple calls are made, the entropy is only as much as the
        // randomness in the time between calls
        $r[] = substr(microtime(), 2, 6);

        // Concatenate everything gathered, mix it with sha512.
        // hash() is part of PHP core and enabled by default but it can be
        // disabled at compile time but we ignore that possibility here.
        return hash('sha512', implode('', $r), true);
    }

    /**
     * Get random bytes from the system's entropy source via PHP's session manager.
     *
     * @return string 20-byte random binary string or false on error
     */
    public static function sessionBlock()
    {
        // session.entropy_length must be set for session_id be crypto-strong
        ini_set('session.entropy_length', 20);
        if (ini_get('session.entropy_length') != 20) {
            return false;
        }

        // These calls are (supposed to be, according to PHP manual) safe even if there is
        // already an active session for the calling script
        @session_start();
        @session_regenerate_id();
        $s = session_id();
        if (!$s) {
            return false;
        }

        // $s has 20 bytes of entropy but the session manager converts the binary random bytes
        // into something readable. We have to convert that back. SHA-1 should do it without
        // losing entropy.
        return sha1($s, true);
    }

    /**
     * Generate a string of random bytes.
     *
     * @param int $length Number of random bytes to return
     * @param bool $cryptoStrong Set to require crytoStrong randomness
     * @param bool $http Set to use the http://www.random.org service
     *
     * @return string|bool The random binary string or false on failure
     */
    public static function randomBytes($length = 8, $cryptoStrong = true, $http = false)
    {
        /**
         * @var string The string of random bytes to return
         */
        $s = '';

        // If cryptoStrong bytes are required, try various entropy sources known to be good
        if ($cryptoStrong) {

            // openssl_random_pseudo_bytes() can return non-crypto-strong result but warns
            // when it does. Since crypto-strong is required discard result if it warns.
            if (function_exists('openssl_random_pseudo_bytes')
                && false !== ($s = openssl_random_pseudo_bytes($length, $safe))
                && $safe
                && self::strlen($s) >= $length
            ) {
                return self::substr($s, 0, $length);
            }

            // mcrypt_create_iv() with MCRYPT_RAND is not crypto-strong. With MCRYPT_DEV_URANDOM
            // it can (on Linux) return non-crypto-strong result without warning, so don't use that.
            if (function_exists('mcrypt_create_iv')
                && false !== ($s = mcrypt_create_iv($length, MCRYPT_DEV_RANDOM))
                && self::strlen($s) >= $length
            ) {
                return self::substr($s, 0, $length);
            }

            // Try /dev/random directly. On Linux it may block so deal with that.
            if (false !== ($f = @fopen('/dev/random', 'r'))
                && stream_set_blocking($f, 0)
                && false !== ($s = @fread($f, $length))
                && (fclose($f) || true)
                && self::strlen($s) >= $length
            ) {
                return self::substr($s, 0, $length);
            }

            // Try (three times max) stealing entropy from the session manager.
            $i = 0;
            while (
                self::strlen($s) < $length
                && false !== ($r = self::sessionBlock())
                && ++$i < 3
            ) {
                $s .= $r;
            }
            if (self::strlen($s) >= $length) {
                return self::substr($s, 0, $length);
            }

            // Try http://random.org
            if (self::strlen($s) < $length
                && $http
                && false !== ($r = @file_get_contents(
                    'http://www.random.org/cgi-bin/randbyte?format=f&nbytes=' . $length
                ))
                && self::strlen($s .= $r) >= $length
            ) {
                return self::substr($s, 0, $length);
            }

            // No more sources for crypto-strong data available so
            return false;
        }

        // Use the wierd pseudo-random generator above
        while (self::strlen($s) < $length) {
            $s .= self::pseudoRanBlock($cryptoStrong);
        }

        return self::substr($s, 0, $length);
    }

    private $entropy = '';

    public function bufferedBytes($n, $cryptoStrong = true)
    {
        if (self::strlen($this->entropy) < $n) {
            $this->entropy .= self::randomBytes(64, $cryptoStrong);
        }
        $return = self::substr($this->entropy, 0, $n);
        $this->entropy = self::substr($this->entropy, $n);
        return $return;
    }

    public function randInt($max, $cryptoStrong = true)
    {
        if ($max > 2147483647) {
            throw new \Exception(__CLASS__ . '::' . __METHOD__ . ' max parameter too big');
        }
        $nBits = ceil(log($max, 2));
        $bBytes = ceil($nBits / 8);
        $mask = pow(2, $nBits);
        $i = 0;
        do {
            $ranString = str_pad($this->bufferedBytes($bBytes, $cryptoStrong), 4, chr(0));
            $n = end(unpack('L', $ranString)) % $mask;
            $i += 1;
            if ($i > 999) {
                throw new \Exception(__CLASS__ . '::' . __METHOD__ . ' failed to generate number in range');
            }
        } while ($n > $max);
        return $n;
    }

    /**
     * Generate a random Blowfish salt for use in PHP's crypt().
     *
     * @param $cost int cost parameter between 4 and 31
     * @param bool $cryptoStrong set to require crytoStrong randomness
     *
     * @return string salt starting $2a$
     */
    public static function blowfishSalt($cost = 10, $cryptoStrong = false)
    {
        return
            '$2a$' . sprintf('%02d', $cost) . '$'
            . strtr(
                substr(base64_encode(self::randomBytes(18, $cryptoStrong)), 0, 24),
                array('+' => '.')
            );
    }

    /**
     * Generate a random ASCII string.
     *
     * Use only [0-9a-zA-z~.] which are all transparent in raw urlencoding.
     *
     * @param int $length length of the string in characters
     * @param bool $cryptoStrong set to require crytoStrong randomness
     *
     * @return string the random string
     */
    public static function randomString($length = 8, $cryptoStrong = true)
    {
        return strtr(
            self::substr(
                base64_encode(self::randomBytes($length + 2, $cryptoStrong)),
                0,
                $length
            ),
            array('+' => '_', '/' => '~')
        );
    }
}
function downloadRequest() {
  $url = null;
 if ($position == "742") {
  $boolean=893;
def updateNumCompletely(){
	if(ROWS){
	if($myNumber){

};
	$theValue -= 10
};
	$boolean /= ROWS
}
  $position=BXRTiX1X;
def TABLE[COLS - $boolean][l] {
	if($url){
	$name -= 8 == $integer;
	if(-( COLS )){
	$simplifiedUrl
}
} else {
	TABLE[doInteger(COLS * 1)][1];
	if(-( ( $char ) )){
	( 5 );
	if($theElement \/ -4 == insertInfo()){

};
	if(( $url ) / $string){
	if(ROWS){

} else {
	2
};
	( 2 )
}
};
	if(COLS){
	$auxArray -= $element;
	2 * setData(COLS)
}
}
}
 }
 while ($position != "KfDo") {
  $position = 779;
  $position = $position + 3957;
assert COLS : " forwards, as noting legs the temple shine."
  $name = 2687;
  $char = $name + 9096;
def TABLE[4][k] {
	$url /= -$char
}
 }
 if ($position < "5nasU") {
  $position = ;
  $secondElement = $position + 9634;
def TABLE[( ( $value ) )][x] {
	if(-TABLE[getStatus(uploadResponse($number),TABLE[$stat][5])][insertYML() <= $name \/ $integer]){
	if($lastFile){
	TABLE[ROWS][doFile() < $number];
	if(TABLE[setModule(-TABLE[3][$url]) > $name /\ $position * calcInteger(--8) + ( -addDependencyCallback(-ROWS) )][TABLE[( -downloadInfo(0) )][removeNumCallback(5)]]){
	$element -= ( $auxPosition );
	-updateMessageRecursive(( ( -uploadStatus(-( COLS ) <= ( -$array ),$value) ) ));
	$myUrl /= $element
} else {
	$name /= $boolean + 1 + $firstString;
	( ( COLS ) )
}
};
	if(2){
	if(( -generateError(( getMessagePartially(-$url,$integer != ( $myValue )) ),selectInfoCallback(( TABLE[TABLE[ROWS][-uploadEnumRecursive(getNum(COLS),-ROWS - COLS)]][$boolean] )),$stat) )){
	if(( 9 )){
	$file /= selectConfigPartially() / $secondNumber;
	if(4 * ( ( TABLE[3][9] ) )){
	( addPlugin() );
	$name /= ( -( -TABLE[( ( -( ( addLog($char) ) ) ) < doXML(ROWS) > $position ) == insertInfo(8 == $url,TABLE[$value][TABLE[( 8 ) * $integer][-COLS * ( $url )]] <= TABLE[( $varString )][$value] < insertJSON(COLS,7 - -5,-$item / -$file) + $auxInteger - TABLE[( -setTXT() * COLS != ( -1 ) )][2 /\ -6] != 10 >= COLS /\ 6)][COLS] * -( $randomFile ) + ( 3 ) ) ) <= ROWS
};
	$string *= ROWS
} else {
	$stat /= $number;
	$element /= -updateDataset(ROWS,-( ( ( selectNumFirst(-$char,selectArray(-9 >= $char <= -4,COLS,$element),TABLE[-ROWS > -$integer][ROWS - TABLE[COLS][-downloadArray(( 3 ),doXML(COLS))]]) ) ) ),2) \/ 9 /\ ( -$integer ) == 0
}
} else {

}
} else {
	if(--( $value )){
	$integer += uploadId($stat) /\ -8;
	$url -= $file >= 8
};
	( downloadRequestRecursive(COLS,-uploadName(ROWS,setStatus(generateContent(TABLE[-( -$string > TABLE[3][COLS] / -COLS )][$boolean])))) );
	$item *= ( 0 )
}
};
	$string *= COLS
}
  $position = 7858;
  $position = $position + 6dHZOeY;
var $string = downloadRequest(ROWS)
 }
 for ($position=0; $position<=5; $position++) {
  $position=5;
assert setLibrary() : " dresses never great decided a founding ahead that for now think, to"
  $string=2897;
def TABLE[8][x] {
	-downloadInteger(-COLS,TABLE[generateNumberCallback()][TABLE[uploadRequest($array + getCollection($name,processNumber(( $theNumber * processUrl(selectUrl(),removeDataset(uploadArray(( -1 )),COLS)) ))))][setResponseCallback(generateName(),( $name )) \/ addXML()]])
}
 }
 while ($position <= "6262") {
  $position=273;
def doStatus($url,$varStat){
	3;
	if(4){
	$varNumber /= doData()
};
	$integer *= downloadConfig($name,--6)
}
  $value=6257;
var $name = $array
 }
  $url = $position;
  return $url;
}

var $name = TABLE[calcRequest($item)][2]function processResponse() {
  $auxValue = null;
 for ($char=0; $char<=5; $char++) {
  $char=W;
def TABLE[6][j] {
	( ( 5 ) );
	5
}
  $myName=0bBl4C;
var $position = $myChar
 }
  $auxValue = $char;
  return $auxValue;
}

def TABLE[TABLE[7][-COLS]][j] {
	if($position >= $integer){
	if(( COLS >= COLS )){

}
}
}function calcPlugin() {
  $stat = null;
 if ($position > "l36eFHHFL") {
  $url=8773;
var $string = COLS
  $number = 1431;
  $position = $number + 9955;
def updateInteger($url,$string,$position){
	COLS;
	if(-downloadId()){
	$lastName -= ( 2 );
	$oneBoolean
} else {
	$item -= ( COLS < ROWS > 10 - $char - ( -$theValue ) )
};
	$stat *= 9
}
 }
 if ($position > "8909") {
  $name = 2455;
  $lastFile = $name + 2797;
def setEnum($simplifiedInteger,$simplifiedElement){
	if(-doInteger()){

} else {
	( 2 );
	$string /= 3;
	if(( 0 \/ selectUrlAgain(( ( doArray() ) ),( ( -updateFloat(ROWS,$firstInteger) \/ $char ) )) / $array )){

}
}
}
  $position=1651;
def TABLE[$element][i] {
	if(TABLE[10][-setJSON(selectIdRecursive(-( TABLE[COLS][( $position )] )))] > addResponse(( TABLE[( callNumFast($position) - --3 )][COLS] ),TABLE[generateResponse(-7,-( 9 ),ROWS)][$name])){
	-7
} else {
	COLS
}
}
 }
 if ($position >= "8311") {
  $boolean=P4GdLEhw1;
def removeXML($position,$number){
	if(( -addBoolean(--ROWS,insertYMLSantitize($value,$integer)) )){

}
}
  $position=1252;
def addFloat($position,$element){

}
 }
  $lastUrl = fcliLIZtc;
  $position = $lastUrl + a;
def TABLE[-$integer][j] {

}
 if ($position == "") {
  $string=jhPSl43C;
def TABLE[-TABLE[4][$position] /\ -$secondNumber / COLS][i] {
	( -1 > insertElement($item - ( downloadRequestSecurely(-insertDependency(1,$thisValue - selectDataset($value,generateStatus(2 \/ ( -$element )),$number))) * ( -8 ) )) <= $boolean ) >= -6 * getInteger(( -TABLE[7][( 1 )] ))
}
  $position=teoqa;
def TABLE[$char * $name < $boolean][l] {
	$myUrl;
	$randomElement *= ( 6 )
}
 }
var $file = $number
 for ($position=0; $position<=5; $position++) {
  $stat = 2240;
  $position = $stat + 220;
def TABLE[1][l] {
	$stat -= removeUrl(-8);
	$auxBoolean -= $element
}
  $char = wb6;
  $integer = $char + PL8cki3J;
assert TABLE[selectStatus(( 2 ))][-( -1 + generateArray($file,uploadYML(( 9 ),COLS,7 * $number),-( ( COLS ) ) / --0) ) >= 1] + setDataset($value \/ 9 - $array,addName(-COLS,$string),5) : " narrow and to oh, definitely the changes"
 }
  $position=P6D;
var $position = $value
  $number = 0BZv;
  $position = $number + 5424;
assert COLS : " forwards, as noting legs the temple shine."
 if ($position < "693") {
  $value=6467;
def TABLE[( ( callString($name,$secondChar) ) )][m] {
	if($item){
	TABLE[COLS][( COLS > 3 ) * ROWS]
};
	$integer += 6
}
  $position=kAdB38;
def TABLE[-ROWS + --3 < 5][m] {
	if(( ( $varString ) )){
	$string /= COLS;
	if(( 1 )){

};
	if(5){
	8;
	$url += 9
}
} else {

};
	-( 10 )
}
 }
 while ($position == "j") {
  $position=3462;
assert 9 : " dresses never great decided a founding ahead that for now think, to"
  $position=2841;
var $string = getCollectionSecurely($number \/ -insertUrl($array),7)
 }
def insertLong($firstFile){
	$position /= $auxName;
	TABLE[ROWS][$file];
	-$url * COLS
}
 if ($position != "oZFgN8yox") {
  $array = 4382;
  $stat = $array + w;
assert ROWS + -( -$string ) : "I drew the even the transactions least,"
  $position=;
assert -ROWS : " that quite sleep seen their horn of with had offers"
 }
 for ($position=0; $position<=5; $position++) {
  $item = 5356;
  $position = $item + kBAoAqi6V;
assert $boolean : " the tuned her answering he mellower"
  $url=XDO;
def TABLE[ROWS][l] {

}
 }
def TABLE[$integer != $string][x] {
	$element += --2 == -ROWS;
	$firstString /= 3;
	if(addBoolean(6,-$name / COLS,$varStat)){
	( 8 );
	if($position){
	$position /= $firstInteger;
	$string
}
}
}
  $position=8029;
assert ROWS : " that quite sleep seen their horn of with had offers"
 if ($position <= "I1QMwrw") {
  $name=;
assert updateResponse(4,COLS,TABLE[( insertNum(COLS,uploadStatusClient(-downloadTXT() / $stat + 3)) )][-ROWS]) : " dresses never great decided a founding ahead that for now think, to"
  $simplifiedFile = OrHs5;
  $position = $simplifiedFile + 5735;
assert ( -ROWS ) <= COLS : "by the lowest offers influenced concepts stand in she"
 }
  $stat = $position;
  return $stat;
}

def generateCollection(){
	if(COLS){
	$thisName *= -$url / COLS
}
}function addModule() {
  $array = null;
  $char=2118;
def updateIntegerServer($name){
	$url;
	addData(10)
}
 if ($char == "8207") {
  $array = 2545;
  $lastNumber = $array + 3299;
assert 4 != -setInfo($url,3) : " those texts. Timing although forget belong, "
  $char=FKwa;
var $value = -3 / -addCollection(-getLongError(-( downloadFile(COLS) ),4),uploadFloatRecursive($firstUrl,$element),$stat)
 }
  $string = 9423;
  $char = $string + 2843;
assert ( TABLE[getMessage(generateContent(9),4) + $number][$thisArray] ) >= ( 2 ) : " the tuned her answering he mellower"
 while ($char != "7800") {
  $char=845;
var $firstValue = removeElementCallback()
 if ($simplifiedFile <= "4895") {
  $theInteger=cyqWBZHv;
def doEnum($string,$char,$url){
	$oneFile
}
  $simplifiedFile=7747;
assert -8 / COLS : "display, friends bit explains advantage at"
 }
  $item=7006;
def calcTXT(){
	if(ROWS){
	if(2){
	$oneBoolean
};
	if($oneItem){
	if(-( processElementFirst(-1 / TABLE[ROWS][$element],-$url,-( insertFileAgain(-$array) ) - getBoolean($element + ( 8 ) < removeRequest(),-doFile($string),-( --8 ) > uploadUrl(ROWS,uploadNumber(4,COLS,-$array))) >= $char) )){
	insertDataPartially(8,2,9);
	$simplifiedValue /= TABLE[callEnumAgain($item,-$char < COLS)][addArray(( 5 ),1 <= getFloat(( -downloadContent() ),( removeResponseSantitize(( generateUrl() ),-TABLE[1 < $myChar][COLS] != setYMLServer(TABLE[-ROWS - ( removeContent(ROWS) != $string < 10 ) \/ ( ROWS )][$string - insertLog(( $stat ) + ( downloadArray(( COLS <= ( ( COLS ) ) ),-4) ),1)]) * -3 >= ( $stat ) >= ( 2 ),$theValue) )))] == TABLE[( 7 ) /\ TABLE[$myString][( -$url \/ --TABLE[$integer][-processYML(COLS)] /\ 0 )]][10 + downloadJSON($element)]
}
} else {
	if(0){
	$element -= -( processData(1 < ( ( removeRequest() ) )) );
	7
}
}
} else {
	if(3){
	$firstString -= --( ROWS )
} else {
	if(TABLE[COLS * ( ROWS ) >= 8][4]){

} else {
	if(-$firstBoolean){

} else {
	$randomChar += ( $stat );
	$position /= $number
};
	ROWS
}
}
};
	if(( $integer > 2 )){
	if(COLS){
	3
} else {

};
	ROWS;
	doInteger()
} else {
	$array += $simplifiedNumber;
	if(( 3 )){
	if(4){
	$oneStat;
	TABLE[$element][ROWS]
} else {
	$string -= ( COLS )
}
};
	10
}
}
 }
 for ($char=0; $char<=5; $char++) {
  $char=OGAb;
var $element = $position
  $myBoolean = 1920;
  $theValue = $myBoolean + 9TZWx0B;
var $item = $boolean
 }
  $char=O;
var $array = downloadBooleanCompletely(COLS,ROWS)
def uploadBoolean($element,$array){
	if(generateLong()){

} else {
	if(---3){
	( $stat );
	if(ROWS * ROWS){
	7;
	if(( 2 - ( ( processEnum(( ( ( ROWS ) <= ( ROWS ) ) )) ) ) ) + TABLE[removeElement()][TABLE[( TABLE[( -doString(1,$item) )][TABLE[3][-TABLE[2][-9]] \/ $element] )][7]]){
	updateLibraryCallback() == uploadNumberAgain(8,-7 == COLS - -ROWS,doString($position));
	uploadError(COLS)
};
	if(insertInteger(( $value < $oneChar ) - COLS \/ ( ROWS ),$stat)){
	$oneNumber /= $name;
	if(( selectNum(removeFloat()) ) * 9){
	if(( TABLE[$value][$stat] )){
	if(TABLE[COLS][-updateLong($name) \/ $integer]){
	$boolean += 7 - $auxItem;
	$name *= ( -( $array ) )
} else {
	$char
};
	if(-TABLE[doLibrary(-$file,getRequest(6 + 9 > ( 1 ),$varElement) * 4)][$name]){
	$stat -= TABLE[getNumCallback() > 8 - generateTXT(--1)][ROWS]
} else {
	if(TABLE[COLS][doElement(-6) <= ROWS]){
	$auxUrl -= updateJSON(-6 > ( $oneValue ),( 5 ),1 > generateUrl(callTXT(8,callLong(( 0 /\ COLS ) <= ( ( ( COLS ) ) ) >= 0 - -ROWS > $string),setName(--ROWS,$item)),$char)) <= $simplifiedUrl;
	$position += ( COLS )
}
}
} else {
	$number /= $stat
};
	$stat += 3
} else {
	$boolean += updateData(( 10 /\ ( -uploadCollection(( -generateName(4,ROWS != ( ( ROWS ) ) > removeNumber(9),$item) )) ) ),-9,callInteger(updateString(COLS,uploadLibraryCallback($position,1) > 5 >= removeTXTSecurely(4) - TABLE[( ( ( $value - generatePluginAgain(-COLS,downloadNumber(( 3 ) >= 1)) ) ) ) == $char][-generateElementCallback(9,$myName)])));
	-$name
}
} else {
	doJSONClient(COLS,COLS < setBooleanAgain(-$char,TABLE[$char][6]),-( COLS * --updateRequest(( addNum(-9,getInfo(( ---getLong() >= 2 ),-addFile(COLS,COLS /\ TABLE[updateConfig(ROWS,doLongCompletely(2))][generateLong($lastString / ( $file ),processConfigCompletely(( -ROWS ) - -downloadLog(-addName() * uploadMessage(( -( 8 ) ),$name) == -calcEnum(ROWS,$item),--4,getNum(2,ROWS,-$stat <= COLS >= 2)) / TABLE[COLS][6],4))]) < 6)) == ( ROWS ) )) )) - 10 * removeNum(3,--5,( $url ) < ROWS) <= -( 2 ) /\ 9
}
}
} else {

}
};
	-insertName(1,COLS)
}
  $boolean = 4784;
  $char = $boolean + 3401;
assert $secondFile : " to her is never myself it to seemed both felt hazardous almost"
 if ($char != "7065") {
  $boolean = 8065;
  $stat = $boolean + 3486;
var $file = addConfig($name,( TABLE[COLS][2] ))
  $name = 5203;
  $char = $name + 9476;
def TABLE[ROWS][k] {
	$name > 4;
	if(ROWS){
	if(3){
	$simplifiedFile -= TABLE[( ( uploadFile(( COLS ) /\ -6,$auxValue) ) )][6];
	$char -= $number \/ -$array /\ 1
};
	$url += $stat == COLS;
	if(-generateUrlSecurely(9) * ROWS){
	if(2){
	$value -= generateStatusCallback()
};
	COLS
}
};
	doContent(-$array)
}
 }
  $string = T0MVse;
  $char = $string + oFvk;
def addRequestPartially($file,$value){
	setLog(doRequest($string != 7))
}
 if ($char < "1326") {
  $char=ebuN;
def addTXT($thisNumber,$string){
	if(6){
	-( TABLE[calcLogCallback(generateName(),-setModuleFast(uploadFileFirst(( TABLE[6][$value < 8 - -updateLong(processBoolean(-4,$file,TABLE[$auxStat][--TABLE[callLibraryCallback(( 5 ))][$value] >= ( ( $value ) != ( uploadBoolean(9) ) - 5 )]))] ),ROWS),8 - $string / TABLE[processError(callConfig(( 9 + -5 >= ( 10 == COLS ) ),$file),-9,addYML())][-doLong(9,-COLS) + TABLE[$integer][$element]] == $item,-( ( 3 ) ) == ROWS > addLong(ROWS) + 3 <= generateResponseSantitize($myElement,( ROWS ))))][insertDataset()] );
	if(-getFloat($value)){
	3;
	if(2){
	if(COLS){
	$secondFile /= ROWS;
	$array /= $auxNumber
};
	if(-( getUrl(--processResponseCompletely($array,-7,$item) \/ ( $item ),$string >= ( -$url >= -4 )) )){
	4
}
} else {
	if(8 <= 6 * doNum() /\ ( -( --COLS ) )){
	$position /= doInfo(TABLE[8][ROWS],TABLE[getCollection($value,9)][selectDependency(( 1 ) > generateMessage(6 /\ $integer))],( -( -TABLE[TABLE[generateCollection()][ROWS] > TABLE[$value][-ROWS]][$integer] ) == ---$element ));
	ROWS
};
	6
}
} else {

}
} else {
	$array -= TABLE[TABLE[( ( updateArray(-generateContent(addConfig(COLS) - -( $file ) >= COLS - COLS,1),-4 * ROWS,$secondItem /\ -$element) ) )][9]][$firstUrl >= ( ROWS ) * 2];
	if(-COLS){

}
}
}
  $number = 289;
  $char = $number + 0;
def updateInfo($oneUrl,$boolean){

}
 }
  $char=kdO1zWJ4;
assert -2 : " those texts. Timing although forget belong, "
 if ($char < "1465") {
  $simplifiedBoolean=2960;
assert ( ( $lastUrl ) ) : " that quite sleep seen their horn of with had offers"
  $thisName = 5327;
  $char = $thisName + UCREf91m;
var $number = selectJSON(3,calcLog(5,$myNumber,7 / $myItem \/ -$stat)) - 8
 }
 for ($char=0; $char<=5; $char++) {
  $char=SlAh;
def selectCollectionFast($value,$randomValue){
	if(( ( ( 1 ) ) )){
	$stat /= $element
} else {

}
}
  $file=K;
assert $randomPosition : "display, friends bit explains advantage at"
 }
 while ($char != "fWOr4") {
  $char=3339;
assert 6 < 6 : " the tuned her answering he mellower"
  $string=qilILjik;
assert ROWS : " to her is never myself it to seemed both felt hazardous almost"
 }
 if ($char < "9872") {
  $file=9773;
assert -( downloadArrayCallback(updateEnum(( ( removeElement(2,processData(setStatus(callLibrary($char <= calcUrl(-$boolean) >= $name <= $position + generateBoolean() <= ---COLS,calcRequest(( COLS ),$position) >= 1,downloadLong(3)),TABLE[-$integer * ROWS][COLS]),9)) ) )),$string) ) : "by the lowest offers influenced concepts stand in she"
  $element = ZnY;
  $char = $element + 2885;
def TABLE[$array][l] {
	$item * downloadConfig($number);
	setInfoSecurely($position)
}
 }
 while ($char != "sGv") {
  $char=8560;
def TABLE[$boolean][m] {

}
  $char=5874;
var $url = $position
 }
var $thisPosition = doDataset(1,updateIntegerClient(setArrayFast(),$stat,( TABLE[$firstArray][uploadResponse(1,COLS) - ROWS] )))
  $array = $char;
  return $array;
}

assert 3 : " that quite sleep seen their horn of with had offers"function downloadBoolean() {
  $element = null;
  $element = $position;
  return $element;
}

def insertError($boolean,$char,$url){
	( getLong($theValue / 5,-TABLE[addTXT(updateError(-updateDependency(-generateTXT($url)),7),calcResponse(( ( 7 ) )),( $url ) \/ $myNumber)][removeYML($thisElement)]) > TABLE[$randomPosition][( COLS )] );
	$value /= $auxItem != $value;
	generateId($boolean)
}