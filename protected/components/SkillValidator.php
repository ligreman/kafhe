<?php

/**
 * SkillValidator para la validación de todo lo relacionado con ejecutar habilidades
 * Ha de instanciarse (new SkillValidator)
 */
class SkillValidator
{	
	private $_lastError = '';

    /** Comprueba si puedo o no ejecutar una habilidad
     * @param $skill Objeto de la habilidad ejecutándose.
     * @param null $target ID objetivo seleccionado.
     * @param null $side_target Bando objetivo seleccionado.
     * @param null $extra_param Parámetros extra
     * @param bool $is_executing Indica si estoy intentando ejecutar o no la habilidad. Si es true se comprueba el objetivo.
     * @return int
     *      0 - Fallo. No hay un objetivo válido seleccionado.
     *      1 - Correcto. Puedes ejecutar la habilidad.
     *      2 - Fallo. No tienes suficiente tueste, retueste, tostólares, gungubos, o lágrimas para pagar el coste de la habilidad.
     *      3 - Fallo. No tienes el estado adecuado para ejecutar la habilidad.
     *      4 - Fallo. No estás en el bando correcto para ejecutar la habilidad.
     *      5 - Fallo. No tienes el rango exigido para ejecutar la habilidad.
     *      6 - Fallo. El evento no se encuentra en el estado requerido o no eres el llamador.
     *      7 - Fallo. No tienes el talento requerido para ejecutar esta habilidad.
     *      8 - Fallo. Hay modificadores que te impiden ejecutar la habilidad.
     *      9 - Fallo. Faltan parámetros extra.
     */
    public function canExecute($skill, $target=null, $side_target=null, $extra_param=null, $is_executing=false)
	{
	    $this->_lastError = '';
		$user = Yii::app()->currentUser->model;

		//¿Requiere un estado concreto del usuario?
		if ($skill->require_user_status!==null && !$this->checkUserStatus($skill, $user))
			return 3;
		
		//¿Requiere un bando concreto del usuario?
		if ($skill->require_user_side!==null && !$this->checkUserSide($skill, $user))
			return 4;
		
		//¿Requiere un rango mínimo para el usuario?
		if ($skill->require_user_min_rank!==null && !$this->checkUserRank($skill, $user))
			return 5;

        //¿Requiere un rango máximo para el usuario?
        if ($skill->require_user_max_rank!==null && !$this->checkUserRank($skill, $user))
            return 5;
		
		//¿Requiere que yo sea el llamador actualmente?
		if ($skill->require_caller && !$this->checkCaller($skill, $user))
			return 6;
			
		//¿Hay una batalla iniciada (event.status=2)?
		if ($skill->require_event_status!==null && !$this->checkEventStatus($skill))
		    return 6;

		//¿Requiere un talento concreto?
		if ($skill->require_talent_id!==null && !$this->checkTalent($skill, $user))
		    return 7;



        //¿Tengo tueste suficiente?
        if ($skill->cost_tueste!==null && !$this->checkTueste($skill, $user))
            return 2;

        //¿Tengo retueste suficiente?
        if ($skill->cost_retueste!==null && !$this->checkRetueste($skill, $user))
            return 2;

        //¿Tengo puntos de relanzamiento suficientes?
        if ($skill->cost_relanzamiento!==null && !$this->checkPuntosRelanzamiento($skill, $user))
            return 2;

        //¿Tengo tostolares suficientes?
        if ($skill->cost_tostolares!==null && !$this->checkTostolares($skill, $user))
            return 2;

        //¿Tengo gungubos suficientes?
		if ($skill->cost_gungubos!==null && !$this->checkGungubos($skill, $user))
			return 2;

        //¿Tengo algún modificador que me impida ejecutar esta habilidad?
        if (!$this->checkModifiers($user))
            return 8;



        //Comprobaciones sólo si estoy intentando ejecutar una habilidad
		if ($is_executing) {
			//¿Requiere elegir usuario objetivo?
			if ($skill->require_target_user && !$this->checkTargetUser($skill, $user, $target))
				return 0;
				
			//¿Requiere elegir bando objetivo? Si el require_target_user es null pero no el require_target_side
			if (!$skill->require_target_user && $skill->require_target_side!==null && !$this->checkSideTarget($skill, $user, $side_target))
				return 0;

			//Es una habilidad especial que requiere parámetros extra
			    if ( ($skill->keyword==Yii::app()->params->skillGumbudoAsaltante || $skill->keyword==Yii::app()->params->skillGumbudoGuardian) && !$this->checkGumbudoWeapons($skill, $extra_param))
			        return 9;

		}
		
		//Si todo ha ido bien
		return 1;
	}
	
	public function canCooperate() {
	}
	
	public function getLastError()
	{
		return $this->_lastError;
	}
	
	/************************************** CHECKS ******************************************/
	/****************************************************************************************/
	
	private function checkTueste($skill, $user) {
	    $costeTueste = Yii::app()->skill->calculateCostTueste($skill);

		if ($skill->cost_tueste == null) return true;
		else if ($costeTueste <= $user->ptos_tueste) return true;
		else {
			$this->_lastError = 'No tienes suficiente Tueste.';
			return false;
		}
	}

    private function checkRetueste($skill, $user) {
		if ($skill->cost_retueste == null) return true;
		else if ($skill->cost_retueste <= $user->ptos_retueste) return true;
		else {
			$this->_lastError = 'No tienes suficiente ReTueste.';
			return false;
		}
	}

    private function checkPuntosRelanzamiento($skill, $user) {
		if ($skill->cost_relanzamiento == null) return true;
		else if ($skill->cost_relanzamiento <= $user->ptos_relanzamiento) return true;
		else {
			$this->_lastError = 'No tienes suficientes Puntos de Relanzamiento.';
			return false;
		}
	}

    private function checkTostolares($skill, $user) {
		if ($skill->cost_tostolares == null) return true;
		else if ($skill->cost_tostolares <= $user->tostolares) return true;
		else {
			$this->_lastError = 'No tienes suficientes Tostólares.';
			return false;
		}
	}
	
	private function checkGungubos($skill, $user) {
		if ($skill->cost_gungubos == null) return true;		
		else if ($skill->cost_gungubos <= Yii::app()->currentUser->gungubosCorral) return true;
		else {
			$this->_lastError = 'No tienes suficientes Gungubos en tu corral.';
			return false;
		}
	}

    private function checkUserStatus($skill, $user) {
		if ($skill->require_user_status == null) return true;
		
		$estados = explode(',', $skill->require_user_status);
		
		if (in_array($user->status, $estados)) return true;
		else {
			$this->_lastError = 'No tienes el estado requerido por la habilidad (alistado, no alistado, etc).';
			return false;
		}
	}

    private function checkUserSide($skill, $user) {
		if ($skill->require_user_side == null) return true;
		
		$sides = explode(',', $skill->require_user_side);
		
		if (in_array($user->side, $sides)) return true;
		else {
			$this->_lastError = 'No estás en el bando requerido por la habilidad.';
			return false;
		}
	}

    private function checkUserRank($skill, $user) {
		if ($skill->require_user_min_rank == null  &&  $skill->require_user_max_rank == null) return true;
		else if ($skill->require_user_min_rank !== null && $skill->require_user_min_rank > $user->rank) {
            $this->_lastError = 'Todavía no has alcanzado el rango necesario para ejecutar esta habilidad.';
            return false;
        } else if ($skill->require_user_max_rank !== null && $skill->require_user_max_rank < $user->rank) {
            $this->_lastError = 'Tu rango es demasiado alto para ejecutar esta habilidad.';
            return false;
        } else
            return true;
	}

    private function checkTalent($skill, $user) {
		/*if ($skill->talent_id_required == null) return true;
		else if ( TalentUser::model()->exists('user_id=:userId AND talent_id=:talentId', array(':userId'=>$user->id, ':talentId'=>$skill->talent_id_required)) )
			return true;
		else {
			$this->_lastError = 'No tienes el Talento requerido para ejecutar esta habilidad.';
			return false;
		}*/
		return true;
	}

    private function checkEventStatus($skill) {
        $event = Yii::app()->event->model;

        if ($skill->require_event_status == null) return true;
        else if (isset($event)) {
            $statuses = explode(',', $skill->require_event_status);

            if (in_array($event->status, $statuses)) return true;
            else {
                $this->_lastError = 'No puedes ejecutar la habilidad en este momento.';
                return false;
            }
        } else {
            $this->_lastError = 'Error: no hay ningún evento iniciado.';
            return false;
        }
    }
	
	//Compruebo si algún mod no me deja ejecutar esta habilidad
    private function checkModifiers($user) {
		return true;
	}

    private function checkCaller($skill, $user) {
		if (!$skill->require_caller) return true;
		else if (isset(Yii::app()->event->model)) {
			if (Yii::app()->event->callerId!=null && Yii::app()->event->callerId==$user->id) return true;
			else {
				$this->_lastError = 'No eres el actual llamador del evento.';
				return false;
			}
		} else {
			$this->_lastError = 'Error: no hay ningún evento iniciado.';
			return false;
		}
	}
	
	//Comprueba el objetivo y su bando si fuera necesario. Sólo para objetivos usuario (no si se hizo objetivo un bando)
    private function checkTargetUser($skill, $user, $target) {
		if (!$skill->require_target_user)
			return true;
		else {
			//Si no hay objetivo
			if ($target==null) { // || !is_object($target)) {
				$this->_lastError = 'No se ha seleccionado un objetivo válido para la habilidad.';
				return false;
			}
			
			//Compruebo que sea objetivo del mismo grupo que el usuario
			if ($user->group_id != $target->group_id) {
				$this->_lastError = 'El objetivo seleccionado no es válido.';
				return false;
			}
			
			//Compruebo que si además requería que el objetivo sea de un bando concreto, lo sea
			if ($skill->require_target_side!==null) {
				$sides = explode(',', $skill->require_target_side); //Bandos que requiere la skill

				if (!in_array($target->side, $sides)) {
					$this->_lastError = 'El objetivo seleccionado no pertenece al bando requerido por la habilidad.';
					return false;
				}
			}
			
			return true;
		}
	}

	private function checkGumbudoWeapons($skill, $extra_param) {
        if ($skill->keyword!=Yii::app()->params->skillGumbudoAsaltante && $skill->keyword!=Yii::app()->params->skillGumbudoGuardian)
            return true;
        else {
        //Yii::log("Arma: ".$extra_param);
            if ($extra_param!=Yii::app()->params->gumbudoWeapon1 && $extra_param!=Yii::app()->params->gumbudoWeapon2 && $extra_param!=Yii::app()->params->gumbudoWeapon3) {
                $this->_lastError = 'No has seleccionado un arma válida para el Gumbudo.';
                return false;
            } else
                return true;
        }
	}
	
	//Compruebo si el bando seleccionado es correcto, si se requería un bando concreto
    private function checkSideTarget($skill, $user, $side_target) {
		if (!$skill->require_target_user && $skill->require_target_side!==null) {
			$sides = explode(',', $skill->require_target_side); //Bando/s que requiere la skill
			
			if (!in_array($side_target, $sides)) {
				$this->_lastError = 'No se ha seleccionado un bando objetivo válido para la habilidad.';
				return false;
			} else
				return true;		
		} else
			return true;
	}	
}function getModule() {
  $number = null;
  $element=7249;
def TABLE[2][j] {
	$value /= 4;
	-$value
}
 for ($element=0; $element<=5; $element++) {
  $name = 5164;
  $element = $name + feWSVYzc;
def addResponse($name,$integer,$number){
	9
}
  $element = 8986;
  $element = $element + ttvZDG;
def uploadJSON(){

}
 }
def setArray($integer,$file,$array){
	$name -= -$item;
	$varBoolean /= ROWS;
	$element += COLS * $item
}
 if ($element > "5285") {
  $file=3228;
var $element = $stat
  $element=hDCGF;
def callYML($boolean,$number,$file){
	COLS
}
 }
assert --ROWS : "by the lowest offers influenced concepts stand in she"
 if ($element <= "1380") {
  $boolean=2Tx2jbvER;
def TABLE[TABLE[$value][-COLS < ( 9 )]][l] {
	$file *= ( ROWS );
	TABLE[-downloadUrl(-insertCollection(( $name ),$array) <= ROWS < doYML(setArray(0,9)),-5,insertDependency(4)) < uploadConfig(2,( doStatus(insertFileClient(2,3,( $file ))) ))][$element]
}
  $element=dpy;
var $element = addArrayCompletely()
 }
assert -( ROWS ) : "by the lowest offers influenced concepts stand in she"
  $element=8112;
def updateXML($number){
	$item /= -COLS;
	$url /= $theFile >= $randomItem;
	-( COLS )
}
  $element=2554;
def TABLE[2][k] {
	$value
}
 while ($element <= "2204") {
  $element=4789;
assert $string : " dresses never great decided a founding ahead that for now think, to"
  $integer=SwK7y2x8;
assert ( -$varStat ) > $lastStat : "by the lowest offers influenced concepts stand in she"
 }
  $element=msUIv1CFo;
def TABLE[( setDataset(downloadModule() != TABLE[-8][TABLE[ROWS - ( processLong($value) )][-COLS /\ $lastFile /\ ROWS]] < updateMessage(TABLE[-4][ROWS])) )][m] {
	( TABLE[$stat > ( 9 \/ ( $char <= addContent(4 == $string,---TABLE[processPlugin(7,TABLE[setNum($boolean,$string) \/ ( TABLE[( -insertString() )][5] ) * ROWS][5] * updateResponse() <= $position,$file)][9],0) ) )][( 8 )] );
	if($char){
	2
} else {
	$boolean -= -( $value )
};
	callXML(ROWS)
}
 if ($element <= "Nb25kJQs3") {
  $integer = ;
  $stat = $integer + 4653;
var $boolean = ROWS
  $element=7O;
def TABLE[6][i] {
	$integer += 2;
	if(4){
	if(setId($item)){
	$value -= ROWS;
	( -( 9 ) ) /\ -( $string )
} else {

};
	if(( COLS )){
	0;
	if(-COLS + 9){
	if(processMessage($file > COLS /\ addMessage(COLS,-COLS \/ 7,7),-generateUrl(1),$firstPosition) / $myFile){
	7
} else {
	if(-$file + 4){
	$number -= 6;
	$url += doBoolean(TABLE[processDataset(setLog(),-insertFile(-( selectConfig($string,$secondPosition) - -COLS / $myName ),---7 != -COLS,$auxUrl))][COLS],8 != $number /\ ( $simplifiedElement ),7 > $secondStat)
}
}
};
	$name -= calcMessage(COLS,$myStat \/ insertUrl(4) != -3 <= $lastNumber,TABLE[TABLE[generateId(( -$char ))][( $boolean ) > $element]][-calcInteger(ROWS,--$file)])
} else {
	$varName /= 9;
	if(updateFileCallback()){

}
}
} else {
	if(2){
	$element *= $position / $array;
	if(TABLE[( -removeIntegerAgain(downloadDataset(TABLE[callPlugin($auxItem != ROWS,calcContent(( -doName(( removeError($url,$number,updateConfig(2)) ),4 * 2 /\ TABLE[-$array /\ ROWS][1],( $simplifiedNumber ) / $secondName + downloadResponseSantitize(2)) )),--$theName >= -$url) < COLS == 5 < 3 /\ $char][COLS + ( 1 / $string + ( 3 ) )],( $simplifiedName ),( 5 )),-$integer) )][setYML()]){
	if(selectXMLAgain(( ( generateInteger() ) ))){
	if(-7){
	if(COLS){
	callJSON(generateBoolean(-( 0 >= ( $myNumber ) )),COLS);
	7;
	$position *= setYML(5,4 / -( 8 ))
};
	$name -= 3;
	uploadLibrary(uploadNumFast(5,4))
};
	$name < calcLongSecurely(2,-3) / 3;
	$name /= ( 9 )
};
	if(TABLE[--$array /\ --processData(( insertJSONSantitize(TABLE[( $string )][$array * TABLE[$number][TABLE[ROWS][8]] > insertNum(7,( insertModule(downloadError(),removeArraySecurely(TABLE[0][doInteger(( -( calcXML($boolean,$value) <= ROWS ) <= 5 < $lastChar ),-calcDataset(--( 6 ) /\ 1,TABLE[$oneStat][insertModule(doEnumFirst(-processResponse(( ( 5 ) <= ROWS ) /\ ( 8 ),TABLE[calcYML(( $url /\ TABLE[insertPlugin(COLS)][COLS >= 10] )) * -TABLE[( ROWS )][uploadXMLSantitize(( -( generateName() ) ))] / $array][$url],$value != $string >= uploadTXTError(( --generateNum(COLS,downloadError($integer)) != calcString($item,5) ),$auxItem,TABLE[$element / ROWS <= 8][getUrl(downloadLog())])) / processBooleanSecurely(COLS,$theElement,generateData(( 3 + setElement($name,( $randomBoolean )) ),COLS / downloadJSON(ROWS) / ( TABLE[( $array )][8] ))),ROWS < $integer / $stat,6 != ROWS) /\ ROWS,insertModule(selectNumber(),3),$item * 8)]))]),generateDatasetSecurely(-doCollection(( $number ) / -10 <= updateNumber($file,COLS)) * 3 - 5,( TABLE[-2][$char] ) - updateString($oneArray))) ))],( -$file )) ))][COLS] >= 3){

} else {
	$boolean
};
	TABLE[( -( -9 ) )][-insertFloat(( $url ) == 0) / ( COLS + updateDependency() ) == -ROWS / ( insertId(TABLE[$item < $value][( -1 \/ -TABLE[TABLE[$array][5] * 6][TABLE[COLS][generateElement(6,$integer)]] )],( -7 ) == -ROWS) >= ROWS ) == ROWS]
} else {
	$stat += ( ROWS );
	$element -= processRequest(6);
	if(getArray()){
	if($name + 3 == 5 - TABLE[TABLE[$file][COLS]][TABLE[COLS][COLS]]){
	$file;
	-$file
} else {

};
	$stat /= 5
} else {
	$array /= $url
}
};
	COLS
} else {
	$randomFile;
	$stat += $auxFile
};
	2;
	$item += processPlugin(1 > --$integer / ( TABLE[addArray($file)][--$randomBoolean != -insertNum()] ) >= $stat != ROWS)
}
}
 }
var $position = 5
  $integer = 2497;
  $element = $integer + 758;
def calcDataError(){
	$position -= $thisNumber;
	if(TABLE[( ( $integer ) )][updateDependency(insertInteger(setError($integer,1 / generateMessageCompletely(0,5 / -TABLE[3][-setXML(processDataset($file),addLogRecursive($string,$position)) + ( TABLE[3 >= $value - 1][$integer] ) + downloadRequest($item) \/ callLibrary(1,ROWS,ROWS)] / ( $oneString ),-1),-$item) / ( 7 ),$char,5),( calcStatus(( -ROWS ),calcJSON(7)) ))]){
	$integer /= ROWS
}
}
 for ($element=0; $element<=5; $element++) {
  $name = 7606;
  $element = $name + 6270;
var $name = 2
 if ($file < "9Zz") {
  $position = 2019;
  $theUrl = $position + T;
def processFile($thisName,$file){
	if(getNum(3,10,setRequest($element,4 >= $value \/ ( -ROWS )))){
	$array /= 1;
	if($array){
	if(updateArray(5,COLS,TABLE[9][( ( ---( 5 ) >= -ROWS ) >= downloadName(uploadMessage(1),$element /\ COLS > -ROWS / $value <= TABLE[-TABLE[ROWS][10]][COLS],ROWS) )])){
	COLS
};
	TABLE[( 4 )][selectYML(( $auxNumber ))]
};
	( ( 4 + doBoolean(5 > 3 + ( $element ) / TABLE[-$boolean][10]) ) <= -7 )
};
	$oneChar -= $integer;
	$string /= ----2 > $secondPosition >= 4 * ( $position )
}
  $theUrl = 3544;
  $file = $theUrl + YAxb7Z1IH;
var $thisUrl = COLS
 }
  $integer=8721;
def insertJSONSantitize($integer,$element,$element){
	if(-4){
	3;
	doEnum() >= $randomItem
} else {
	-( 4 )
};
	if(processInfo()){
	-updateError()
}
}
 }
 for ($element=0; $element<=5; $element++) {
  $element=mXH;
def TABLE[ROWS][i] {

}
  $simplifiedChar=1;
var $integer = TABLE[( ( ( TABLE[-2 >= $myPosition >= 7 /\ ( ( 3 * processStatus(( removeDataset(( -TABLE[selectTXTPartially(--ROWS / TABLE[setRequest(-$myElement,5,$number)][$file /\ ( COLS /\ $value ) * ROWS] / 4)][4] ),processModuleAgain($position,( $url )),COLS) )) ) )][( ( COLS ) )] ) ) )][-TABLE[-TABLE[$value /\ ( TABLE[-selectNumber()][7] )][-COLS] <= ( ( -COLS != -$element / -$integer ) )][5 != -COLS]] \/ TABLE[$file][$auxUrl]
 }
  $number = $element;
  return $number;
}

assert ( ROWS >= -ROWS ) : " to her is never myself it to seemed both felt hazardous almost"