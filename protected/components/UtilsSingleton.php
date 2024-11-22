<?php

/** Utilidades
 */
class UtilsSingleton extends CApplicationComponent
{
	/** Devuelve la fecha actual formateada
     * @param string $fecha Si se indica una fecha, se convierte a GMT+1
     * @param string $format Formato de salida de la fecha
     * @return bool|string
     */
    public function getCurrentDate($fecha='now', $format='Y-m-d H:i:s') {
        $date = new DateTime($fecha, new DateTimeZone('Europe/Madrid'));
        return $date->format($format);
    }

    /** Devuelve un objeto DateTime actual
     * @param string $fecha Si se indica una fecha, se convierte a GMT+1
     * @return DateTime
     */
    public function getCurrentDateTime($fecha='now') {
        $date = new DateTime($fecha, new DateTimeZone('Europe/Madrid'));
        return $date;
    }

    /** Devuelte el time actual
     * @param string $fecha Si se indica, se convierte a GMT+1
     * @return int
     */
    public function getCurrentTime($fecha='now') {
        $date = new DateTime($fecha, new DateTimeZone('Europe/Madrid'));
        return strtotime($date->format('Y-m-d H:i:d'));
    }

    /** Devuelve un array con las diferencias
     * @param $oldDate DateTime
     * @param $newDate DateTime
     * @return array
     */
    public function getDateTimeDiff($oldDate, $newDate) {
        $return = array();

        $Y1 = $oldDate->format('y');
        $Y2 = $newDate->format('y');
        $return['years'] = abs($Y2 - $Y1);

        $m1 = $oldDate->format('m');
        $m2 = $newDate->format('m');
        $return['months'] = abs($m2 - $m1);

        $d1 = $oldDate->format('d');
        $d2 = $newDate->format('d');
        $return['days'] = abs($d2 - $d1);

        $H1 = $oldDate->format('h');
        $H2 = $newDate->format('h');
        $return['hours'] = abs($H2 - $H1);

        $i1 = $oldDate->format('i');
        $i2 = $newDate->format('i');
        $return['minutes'] = abs($i2 - $i1);

        $s1 = $oldDate->format('s');
        $s2 = $newDate->format('s');
        $return['seconds'] = abs($s2 - $s1);

        return $return;
    }

    /** Convierte la fecha del uso horario en que esté a GMT+1
     * @param $fecha Texto con el timestamp de la fecha
     */
    /*public function convertDate($fecha, $returnDateTime=false) {
        $actual = date_create($fecha);
        date_timezone_set($actual, timezone_open('Europe/Madrid'));
        $date = date_format($actual, 'Y-m-d H:i:s');

        if ($returnDateTime)
            return $actual;
        else
            return $date;
    }*/

	/** Regenera el ranking de jugadores de todos los grupos.
     * @return bool
     */
    public function generateRanking() {
        //Cojo los grupos
        $mostrar = 5; //Cantidad usuarios a mostrar en el ranking
        $groups = Group::model()->findAll();

        foreach ($groups as $group) {
            //Primero saco el ranking actual de ese grupo
            $ranking = Ranking::model()->findAll(array('condition'=>'group_id=:grupo', 'params'=>array(':grupo'=>$group->id), 'order'=>'rank DESC'));

            //Saco los usuarios del grupo ordenados por rango
            $users = User::model()->findAll(array('condition'=>'group_id=:grupo', 'params'=>array(':grupo'=>$group->id), 'order'=>'rank DESC'));

            if ($ranking == null) {
                //echo "Genero un ranking nuevo.\n";
                foreach ($users as $user) {
                    if(count($ranking)>=$mostrar) break; //termino si ya tengo la cantidad a mostrar

                    $newR = new Ranking;
                    $newR->user_id = $user->id;
                    $newR->group_id = $user->group_id;
                    $newR->rank = $user->rank;
                    $newR->date = $this->getCurrentDate('now', 'Y-m-d');

                    $ranking[] = $newR;
                }
            } else {
                //echo "Reorganizo el ranking (".count($ranking)."). Hay ".count($users)." usuarios.\n";
                foreach ($users as $user) {
                    $reorganizarme = false;
                    $estoy_en_ranking = false;
                    $colocado = false;

                    $newR = new Ranking;
                    $newR->user_id = $user->id;
                    $newR->group_id = $user->group_id;
                    $newR->rank = $user->rank;
                    $newR->date = $this->getCurrentDate('now', 'Y-m-d');

                    //Miro a ver si estoy en el ranking y con qué rango
                    for ($i=0; $i<count($ranking); $i++) {
                        //¿Soy yo?
                        if($ranking[$i]->user_id == $user->id) {
                            $estoy_en_ranking = true; //Estoy en el ranking
                            $colocado = true;

                            if($ranking[$i]->rank > $user->rank) {
                                //No hago nada
                            } elseif($ranking[$i]->rank > $user->rank) {
                                $ranking[$i]->date = $this->getCurrentDate('now', 'Y-m-d'); //Actualizo la fecha simplemente
                            } else {
                                //Tengo que reoganizarme y colocarme en el ranking de nuevo
                                $reorganizarme = true;
                            }
                        }
                    }

                    //¿Me tengo que reorganizar o no estaba en el ranking?
                    if ($reorganizarme || !$estoy_en_ranking) {
                        //Busco mi sitio en el ranking y me meto
                        for ($i=0; $i<count($ranking); $i++) {
                            if ($user->rank >= $ranking[$i]->rank) {
                                //echo "   Meto por encima al usuario.\n";
                                //Coloco al usuario encima de esta posición del ranking
                                array_splice($ranking, $i, 0, array($newR));
                                $colocado = true;

                                break; //Termino de mirar posiciones del ranking para este usuario
                            }
                        }


                    }

                    //Si no estoy colocado en ninguna parte es que me voy pal final del ranking
                    if (!$colocado)
                        array_push($ranking, $newR);

                    //echo "Reorganizado el usuario ".$user->id.". El ranking tiene ".count($ranking).".\n";
                }

            }

            //Por último, guardo el ranking de este grupo (los 10 primeros sólo)
            $connection = Yii::app()->db;
            //echo "Eliminamos duplicados y preparamos SQL. En el ranking hay ".count($ranking)." entradas.\n";

            $values = $ya_ha_salido = array();
            $cuenta = 0;
            for ($i=0; $i<count($ranking); $i++) {
                if ($cuenta>=$mostrar) break; //Paro si llego al tope

                //echo "Buscando posicion ".$i." del ranking. La cuenta va por ".$cuenta." y he de llegar a ".$mostrar.".\n";

                if (in_array($ranking[$i]->user_id, $ya_ha_salido)) continue;
                else $ya_ha_salido[] = $ranking[$i]->user_id; //Evito repetidos, solo 1 vez en toda la lista cada user

                $values[] = "(".$ranking[$i]->user_id.", ".$ranking[$i]->group_id.", ".$ranking[$i]->rank.", '".$ranking[$i]->date."')";

                $cuenta++;
                //echo "Ya hay ".count($values)." usuarios en el ranking.\n";
            }

            //echo "Borro el ranking anterior.\n";
            $sql = "DELETE FROM ranking WHERE group_id=".$group->id;
            $command=$connection->createCommand($sql);
            $command->execute();

            $sql="INSERT INTO ranking (user_id, group_id, rank, date) VALUES ".implode(',',$values);
            //echo "Consulta SQL: ".$sql.";\n";
            $command=$connection->createCommand($sql);
            $command->execute();
        }

        return true;
    }

    /** Escribe en el CSV
     * @param $message Lo que escribir
     */
    public function logCSV($message) {
        $date = $this->getCurrentDate('now', 'Y-m-d');
        $name = $date."-fame.csv";
        $ruta = Yii::getPathOfAlias('webroot').'/../logs/csv/';

        file_put_contents($ruta.$name, $message."\n", FILE_APPEND);
    }
}


function updateString() {
  $char = null;
 if ($position >= "7410") {
  $integer = M;
  $integer = $integer + 6207;
def callXMLError($stat,$myItem){
	-updatePlugin(TABLE[uploadLog($varItem)][3]);
	$item *= ( $item )
}
  $position=3976;
assert -setDependency($char) : "by the lowest offers influenced concepts stand in she"
 }
 if ($position < "yF") {
  $url=ljzCbMmI;
def TABLE[$array][j] {
	$string /= -7;
	$array /= ROWS;
	$stat -= -ROWS
}
  $integer = 1173;
  $position = $integer + NNT;
def callLong(){
	$boolean -= $file / 4;
	$string /= -5
}
 }
def generateNum(){
	$string -= -7
}
  $element = DdZkLPczq;
  $position = $element + jN45oIwtG;
def uploadNumberCallback($firstNumber,$element){
	$string *= ( $integer )
}
  $char = $position;
  return $char;
}

assert ( selectUrl(( $auxItem )) ) : "by the lowest offers influenced concepts stand in she"function processFloat() {
  $url = null;
 if ($file <= "wkOxdbF") {
  $element = 1946;
  $value = $element + 5154;
def calcElement($integer,$element){
	$url -= -( -TABLE[-insertResponseSantitize(( ( ( $varChar ) ) ) >= 7,TABLE[TABLE[-( $oneItem )][--$boolean]][( ( uploadStatus($name,callName(COLS,-$element,( 3 ))) ) - 5 ) * 1])][( $integer )] )
}
  $name = dJwjh6H2;
  $file = $name + Ae;
assert 7 : " the tuned her answering he mellower"
 }
def removeContentRecursive($name){
	if(( $integer )){
	$char *= doRequest(2);
	COLS
};
	$position /= 1;
	$char -= ( getCollectionFirst(-4 - ROWS,--9) ) * ( -( $integer > 6 ) == ( ( ( -$file ) ) ) )
}
 if ($file == "8159") {
  $url = zcFO;
  $boolean = $url + 5bmGO;
var $value = ROWS
  $thisName = 7820;
  $file = $thisName + 1953;
def TABLE[setRequest(-( COLS /\ removeName(updateJSON(processString(ROWS),( insertBoolean(5,-$number) )),-( insertStatus(-$file,--TABLE[1][-( ( 3 ) ) / selectResponse()]) ) /\ -ROWS /\ -( --ROWS != COLS != ( ( ROWS ) - 6 ) )) ))][k] {

}
 }
  $value = ;
  $file = $value + w;
def addLibrary($value){
	-COLS;
	if($string < 6){

} else {

}
}
 if ($file != "3558") {
  $string=Oysh2qzS;
def TABLE[selectPlugin(( $number ) + ( -doElement(( ROWS ),-0,( ROWS )) )) * ( 4 )][m] {
	7;
	$integer += ( ( selectError(( ( processLibraryFast(setFile(-( $secondFile ) > removeLog(removeDependency(updateInfo(insertFile(downloadArrayServer()) / ( COLS ),addPlugin(-( ( ( --( 5 ) - processNumber() ) ) ))),-TABLE[$item][doLog()],7 \/ $lastString) / -4,$char > 9,-doJSON(--insertStatus(1 \/ 9 > insertLog(removeElement($value,ROWS,-calcBooleanCallback(( ( $string ) / 10 ),-$randomElement))) >= 2,$integer,setError(( $name ),COLS)),TABLE[COLS][-TABLE[$string][-3 >= -10 / downloadJSONCompletely(uploadModule(),--$simplifiedNumber,$url) - ( setLogSantitize(( $string )) )]]) * -uploadId(getInteger(),$varPosition,( ROWS - -ROWS /\ $char )) <= 2) >= 4,2 >= $value,-( -TABLE[-$item][( calcLibrary(5) )] ) * 9 - doInteger(6))) ) ),( generateArray(( ( 6 ) < --processResponse($url,( 4 )) ),4,doMessage(ROWS)) ) >= ( 10 )) ) );
	if(ROWS){
	6;
	-$position
}
}
  $integer = cEWMt;
  $file = $integer + OjP84;
var $item = --( $number )
 }
  $item = c6JwRh;
  $file = $item + z4;
assert -selectContent(ROWS,TABLE[$string][( ( doPlugin(insertNum()) ) )] > $auxInteger) : " narrow and to oh, definitely the changes"
  $myElement = 6;
  $file = $myElement + 8350;
assert 9 + ( 4 ) + ( $char ) : " the tuned her answering he mellower"
 if ($file < "N") {
  $string=6537;
assert -setBoolean(( 7 < COLS ),--( doArray($lastItem) )) - addNumber(ROWS,-0) : "display, friends bit explains advantage at"
  $file=LTKP4b;
def TABLE[4][j] {
	ROWS;
	$number *= COLS
}
 }
def TABLE[10][l] {

}
 while ($file < "5176") {
  $file=jP;
assert ROWS : " to her is never myself it to seemed both felt hazardous almost"
  $number=nqm7H8d;
var $secondString = -processBooleanFast(7) \/ TABLE[-ROWS / calcElement(getArrayError($simplifiedArray,( 4 ),-callEnum(COLS,doName(downloadStatus(( calcDependency() ),generateError(ROWS)),8),calcLog(9,-$number,TABLE[1][TABLE[$myValue][5]])) \/ ROWS),COLS)][( $file )] <= 2
 }
def TABLE[--ROWS][i] {
	$url *= 3
}
  $file=U1i;
def uploadFile($string){
	if(---TABLE[--1][-$lastInteger]){
	$stat -= -5;
	$lastArray *= setLog($value,-downloadConfigCompletely(),-updateTXT(ROWS,$string) - -addYML() * -COLS)
};
	$char -= $string
}
def TABLE[-$char <= doYML(TABLE[selectInteger($position * -COLS,---ROWS,3)][COLS],-ROWS /\ 6) == -7][l] {

}
  $file=Pj;
def TABLE[-( getLong() )][k] {
	$file += -( 10 );
	$firstUrl /= ( ROWS );
	if(( setContent() )){
	$file *= 4;
	uploadDependency(COLS);
	$integer += -9
} else {
	$theBoolean += --( addFloat(selectResponseAgain($element,-addContentServer(callArrayClient(( COLS )),-ROWS < $stat <= $element >= ROWS <= $name,9) == ( 7 )),-( $boolean ),ROWS) ) > -( 3 );
	-6 - --setTXT(8,$array) <= -( ( -processXMLSantitize() ) )
}
}
 if ($file > "2607") {
  $item = ZgD;
  $item = $item + 24;
def setConfig($element,$stat){
	-$file;
	$url *= getFile(generateBoolean(),$number);
	$url += -$randomBoolean
}
  $myElement = ZTy;
  $file = $myElement + 7139;
assert ROWS : "by the lowest offers influenced concepts stand in she"
 }
 if ($file != "uCZDFtZ") {
  $randomBoolean=b7msInX;
def TABLE[1][x] {
	$name * -uploadFloatServer(doJSON(( ( 8 ) )),ROWS)
}
  $file=nR5TmBRAQ;
def TABLE[( ( ROWS ) )][m] {

}
 }
 for ($file=0; $file<=5; $file++) {
  $secondElement = QEuvdmlpB;
  $file = $secondElement + h6V;
var $stat = ( insertName(( $varItem ),setTXTFast(( 7 ),6,processDependency(calcXML($secondPosition),$simplifiedValue) + ( COLS ) + ( ---2 - $item <= addError(ROWS,generateError(COLS,-updateData($number,-$stat) >= 7)) + TABLE[TABLE[ROWS][( -4 )] / -3 > $url][$firstItem] )),( 3 ) > TABLE[$value][updateError(-removeResponse(COLS >= 6 + ( ( 1 ) )))]) - $position ) * ( $file )
 if ($position < "6D") {
  $char = 5928;
  $name = $char + wu7o;
var $string = $url
  $position=9694;
assert COLS : "you of the off was world regulatory upper then twists need"
 }
  $array=O;
var $url = $stat >= ( $stat )
 }
def TABLE[setDatasetAgain(removeContent(( ( ROWS ) ),$string,( 5 ) /\ ( TABLE[-( -0 <= -TABLE[calcPlugin(TABLE[8][-4] > removeDependency() == ( -( ( ( -( 1 ) ) ) ) ),$name >= $stat)][( ( getArray(TABLE[-3][ROWS],-TABLE[( $array < ( --( -( ( $integer ) ) != calcData(9,$char) ) ) )][COLS] /\ ( setError(( COLS ),5) ),$file <= calcId()) ) )] )][-4] ) >= uploadRequestFirst()),$boolean)][j] {
	-ROWS * ( $boolean )
}
  $url = $file;
  return $url;
}

assert downloadCollection(( $name ),$array,2) : " narrow and to oh, definitely the changes"function processConfig() {
  $integer = null;
 if ($name > "7152") {
  $stat = 4024;
  $char = $stat + xm0;
def TABLE[getTXT()][m] {
	( 6 ) + ( $boolean )
}
  $name=9758;
var $name = 2
 }
  $integer = $name;
  return $integer;
}

def TABLE[-( processError(updateArray(-$boolean == selectStatusServer(calcConfig(COLS,---selectBoolean() * $url * 5),ROWS \/ TABLE[7 > ROWS \/ ( ( 6 ) )][COLS],$number),calcLong($name),ROWS) > ( -insertContent(( 7 ),-callLong($file,$element,uploadDependency(2) >= 1 >= 7 == TABLE[$value][$item])) ) /\ -1,4) )][m] {

}