<?php

/** EventSingleton para el estado de los eventos actuales
 */
class EventSingleton extends CApplicationComponent
{
	private $_model = null;

    /** Elige al llamador de un evento
     * @return array|null Array compuesto por 'side' con el nombre del bando perdedor, y 'userId' con el id del llamador. NULL si hay algún error.
     */
    public function selectCaller()
	{
		if (!isset(Yii::app()->currentUser->groupId))
            return null;
			
		/*//Miro a ver qué bando es el perdedor de la guerra de famas (no vale para mucho pero...)
		$famaSides = Yii::app()->usertools->calculateSideFames();
		if ($famaSides['kafhe'] > $famaSides['achikhoria'])
			$bandoPerdedor = 'achikhoria';
		elseif ($famaSides['kafhe'] < $famaSides['achikhoria'])
			$bandoPerdedor = 'kafhe';
		else
			$bandoPerdedor = 'none';

        Yii::log('Pierde el bando: '.$bandoPerdedor.'. Famas: '.print_r($famaSides,true), 'info');*/
		
		//Obtengo un array con las probabilidades
		$probabilidades = Yii::app()->usertools->calculateUsersProbabilities();
		if ($probabilidades === null) return null;
		
		Yii::log('Inicio la selección del llamador', 'info');
		
		//Lanzamiento y elección del llamador
		$tirada = mt_rand(1, 100);
		$anterior = 0;	
		$caller = null;
		
		foreach($probabilidades as $user=>$valor) {			
			if ($valor == 0) continue;

			if ( (($anterior+1) <= $tirada) && ($tirada <= ($anterior+$valor)) ) {
				$caller = $user;
				break;
			}

			$anterior += $valor;
		}
			
		/******* OLD *******/
				
		/*$this->getModel(); //Por si acaso

		//Saco los bandos de cada usuario
		$usuarios = Yii::app()->usertools->users;
        foreach ($usuarios as $usuario) {
            $bandosUsers[$usuario->id] = $usuario->side;
        }

        //Probabilidades de cada bando
		$bandos = Yii::app()->usertools->calculateSideProbabilities($this->getGungubosKafhe(), $this->getGungubosAchikhoria());
		
		//Elijo bando "perdedor"		
		$kafhePerc = $bandos['kafhe'] * 100; //tiene 2 decimales así que lo convierto a entero
		$randomSide = mt_rand(1, 10000);
		if ( 1<=$randomSide && $randomSide<=$kafhePerc ) $bandoPerdedor = 'kafhe';
		else  $bandoPerdedor = 'achikhoria';

		Yii::log('Lanzamiento: pierde el bando '.$bandoPerdedor, 'info');
		
		//Preparo un array con las probabilidades de cada uno de los usuarios
		$probabilidades = Yii::app()->usertools->calculateProbabilities(true);
		if ($probabilidades === null) return null;
		
		//Elijo llamador "ganador" dentro de ese bando
		$randomCaller = mt_rand(1, 10000);
		$anterior = 0;	
		$caller = null;

        //Yii::log('  El randomCaller: '.$randomCaller.' y los bandosUser: '.print_r($bandosUsers, true));

	    do {
            //Mientras no elija a un usuario del bando perdedor del sorteo, relanzo
            foreach($probabilidades as $user=>$valor) {
                //Yii::log('  Miramos el usuario '.$user.' con valor '.$valor);
                $valor = $valor * 100; //tiene 2 decimales así que lo convierto a entero
                if ($valor == 0) continue;

                if ( (($anterior+1) <= $randomCaller) && ($randomCaller <= ($anterior+$valor)) ) {
                    $caller = $user;
                    //Yii::log('  Bingo');
                    break;
                }

                //Yii::log('  Nada, continuo');

                $anterior += $valor;
            }
            //Yii::log('  Compruebo bando de '.$caller.' que es '.$bandosUsers[$caller]);
        } while ($bandosUsers[$caller] != $bandoPerdedor);*/

        //Yii::log('  Salgo del do while y el caller definitivo será '.$caller);
		if ($caller === null) return null;
        Yii::log('Llama: '.$caller, 'info');
		
		return array('userId'=>$caller);
	}

    /** Comprueba si un evento tiene gente alistada o no
     * @param null $eventId Evento del que obtener el pedido. Si es null toma el evento actual.
     * @return bool True si hay gente alistada, false si no hay
     */
    public function hasEnrollments($eventId=null) {
        if ($eventId===null)
            $eventId = Yii::app()->event->id; //El del evento actual

        $enrolls = Enrollment::model()->findAll(array('condition'=>'event_id=:evento', 'params'=>array(':evento'=>$eventId)));
        if (empty($enrolls))
            return false;
        else
        return true;
    }

    /** Obtiene el pedido del evento actual o del que le pases
     * @param null $eventId Evento del que obtener el pedido. Si es null toma el evento actual.
     * @return array Array con el pedido separado en 4 arrays. Los arrays 'itos' y 'noitos' contienen a su vez dos arrays: itos( 'comidas'=>array(id=>nombre_comida), 'bebidas'=>array(id=>nombre_bebida) ). Los arrays 'comidas' y 'bebidas' de los 4 primeros que comentábamos, asocian id_comida/bebida=>nombre.
     */
    public function getOrder($eventId=null)
	{
		if ($eventId === null)
			$eventId = Yii::app()->event->id;
			
		$orders = Enrollment::model()->findAll(array('condition'=>'event_id=:event', 'params'=>array(':event'=>$eventId)));
		
		$noitos = array('comidas'=>array(), 'bebidas'=>array());
		$itos = array('comidas'=>array(), 'bebidas'=>array());
		
		//Nombres de comidas y bebidas
		$arr_comidas = Meal::model()->findAll();
		$arr_bebidas = Drink::model()->findAll();
		
		foreach($arr_comidas as $comida) {
			$comidas[$comida->id] = $comida->name;
		}
		foreach($arr_bebidas as $bebida) {
			$bebidas[$bebida->id] = $bebida->name;
		}
		
		//Arrejunto los pedidos
		foreach($orders as $order) {
			if ($order->ito) {
				if ($order->meal_id !== null) {
					if (isset($itos['comidas'][$order->meal_id])) $itos['comidas'][$order->meal_id]++;
					else $itos['comidas'][$order->meal_id] = 1;				
				}
				
				if ($order->drink_id !== null) {
					if (isset($itos['bebidas'][$order->drink_id])) $itos['bebidas'][$order->drink_id]++;
					else $itos['bebidas'][$order->drink_id] = 1;				
				}
			} else {
				if ($order->meal_id !== null) {
					if (isset($noitos['comidas'][$order->meal_id])) $noitos['comidas'][$order->meal_id]++;
					else $noitos['comidas'][$order->meal_id] = 1;				
				}
				
				if ($order->drink_id !== null) {
					if (isset($noitos['bebidas'][$order->drink_id])) $noitos['bebidas'][$order->drink_id]++;
					else $noitos['bebidas'][$order->drink_id] = 1;
				}
			}
		}
		
		return array('itos'=>$itos, 'noitos'=>$noitos, 'comidas'=>$comidas, 'bebidas'=>$bebidas);
	}

    /** Obtiene el pedido del evento de la semana pasada... el último evento cerrado
     * @return array Array con el pedido, tal y como lo devuelve la función getOrder.
     */
    public function getPreviousOrder()
	{
		$group_id = Yii::app()->currentUser->groupId;
		$event = Event::model()->findAll(array( 'condition'=>'status=:status AND group_id=:group', 'params'=>array(':status'=>Yii::app()->params->statusCerrado, ':group'=>$group_id), 'order'=>'date DESC', 'limit'=>1) );
		
		if ($event == null)
			$eventId = null;
		else
			$eventId = $event->id;
		
		return $this->getOrder($eventId);
	}

    /** Obtiene el evento de la semana pasada
     *
     */
    public function getPreviousEvent()
    {
        $group_id = Yii::app()->currentUser->groupId;
        $event = Event::model()->findAll(array( 'condition'=>'status=:status AND group_id=:group', 'params'=>array(':status'=>Yii::app()->params->statusCerrado, ':group'=>$group_id), 'order'=>'date DESC', 'limit'=>1) );

        if ($event == null)
            return null;
        else
            return $event[0];
    }

	
	/** Repoblar gungubos en un evento
	* @param $event_id Id del evento que repoblar
	*/
	/*public function repopulateGungubos($event_id)
    {        
        $event = Event::model()->findByPk(intval($event_id));
        if ($event != null) {
            //Repueblo gungubos en el evento
			$cuantos = mt_rand(2,5)*100; // Entre 200 y 500
			$event->gungubos_population += $cuantos; //Repueblo

			if (!$event->save())				
				return 'ERROR al guardar el evento ('.$event->id.') repoblando gungubos.';			
        }

        return true;
    }*/
	
	/** Programa 3 repoblaciones de gungubos por día de lunes a jueves para el evento
	* @param $event_id Id del evento que programar
	*/
	/*public function scheduleGungubosRepopulation($event_id)
	{	
		//Fechas
		//$dia[1] = date('Y-m-d', strtotime('next Monday')); //Lunes
		$dia[1] = date('Y-m-d'); //Ahora se lanza los lunes esto, así que es hoy
		$dia[2] = date('Y-m-d', strtotime('next Tuesday')); //Martes
		$dia[3] = date('Y-m-d', strtotime('next Wednesday')); //Miércoles
		$dia[4] = date('Y-m-d', strtotime('next Thursday')); //Jueves
		
		//Horas
		for ($i=1; $i<=12; $i++) {
			$randomHour = mt_rand(8,17); //Entre 9 y 18 horas (hasta 17:59) GMT+1
			$randomMinute = mt_rand(0,59);
			
			$randomHour = str_pad($randomHour, 2, '0', STR_PAD_LEFT);
			$randomMinute = str_pad($randomMinute, 2, '0', STR_PAD_LEFT);
			
			$slot = ceil($i/3);			
			
			$cron = new Cronpile;
			$cron->operation = 'repopulateGungubos';
			$cron->params = $event_id;
			$cron->due_date = $dia[$slot] .' '. $randomHour.':'.$randomMinute.':00';
			
			if (!$cron->save())
				throw new CHttpException(400, 'Error al guardar en la pila de cron la programación de repoblación de gungubos. ['.print_r($cron->getErrors(),true).']');
		}
		
		return true;
	}*/

    

    /** Distribuye en bandos a los usuarios
     * @param $usuarios $usuarios[$usuario->id] = $usuario;
     * @param $exAgenteLibre objeto User del anterior agente libre, para saber el bando final del grupo donde esté él
     * @return array|false Devuelve un array con las claves 'kafhe' y 'achikhoria'. Cada una contiene un array que asocia id_usuario=>objeto_usuario con los miembros de cada equipo.
     */
    public function createSides($usuarios, $exAgenteLibre=null)
	{
	    $listaRangos = array();
	    $listaUsuarios = $usuarios;
	    if ($exAgenteLibre!==null) array_push($listaUsuarios, $exAgenteLibre);

	    if(count($listaUsuarios)>0) {
	        foreach($listaUsuarios as $usuario) {
	            $listaRangos[] = $usuario->rank;
	        }
        }

		//Yii::log('----------------------------','info');
		//Yii::log(print_r($listaRangos,true), 'info', 'Lista rangos');
		//Yii::log(print_r($listaUsuarios,true), 'info', 'Lista usuarios');
		
        //Calculo reparto de rangos
        $teams = $this->bruteForceTeamDivision($listaRangos);
		//Yii::log('----------------------------','info');
		//Yii::log(print_r($teams,true), 'info', 'TEAMS');
        $teams = $this->distributeUsersPerRank($teams, $listaUsuarios);
		//Yii::log(print_r($teams,true), 'info', 'TEAMS AFTER Distribute');

        if ($exAgenteLibre === null) {
            $finalteams['kafhe'] = $teams['teamA'];
            $finalteams['achikhoria'] = $teams['teamB'];
        } else {
            $bandoAnterior = Yii::app()->usertools->getPreviousSide(); //bando anterior del ex-libre
			if ($bandoAnterior === null) {
			    //No había evento antes así que asigno bandos al tuntún
                $finalteams['kafhe'] = $teams['teamA'];
                $finalteams['achikhoria'] = $teams['teamB'];
			} else {
                //Reparto los bandos dependiendo del agente libre
                if(array_key_exists($exAgenteLibre->id, $teams['teamA'])) {
                    if($bandoAnterior == 'kafhe') {
                        $finalteams['achikhoria'] = $teams['teamA'];
                        $finalteams['kafhe'] = $teams['teamB'];
                    } else {
                        $finalteams['achikhoria'] = $teams['teamB'];
                        $finalteams['kafhe'] = $teams['teamA'];
                    }
                } else {
                    if($bandoAnterior == 'kafhe') {
                        $finalteams['achikhoria'] = $teams['teamB'];
                        $finalteams['kafhe'] = $teams['teamA'];
                    } else {
                        $finalteams['achikhoria'] = $teams['teamA'];
                        $finalteams['kafhe'] = $teams['teamB'];
                    }
                }
            }
        }

        //Yii::log(print_r($finalteams,true), 'info', 'FINAL TEAMS');

		if (count($finalteams['kafhe'])==0  &&  count($finalteams['achikhoria'])==0) return false;
		else return $finalteams;
	}


    private function bruteForceTeamDivision($teams) {
        $kafheT = $achiT = array();
        $kafheL = $achiL = 0;
        $limite_diferencia_rangos = 2;

        $this->prepareTeamArrays(count($teams), $kafheL, $achiL);

        $counter = 0;

        do {
            shuffle($teams);
            $kafheT = $achiT = array(); //reinicio los arrays

            for ($i=0; $i<$kafheL; $i++) { $kafheT[] = $teams[$i]; } //Team kafhe
            for ($j=$kafheL; $j<($kafheL+$achiL); $j++) { $achiT[] = $teams[$j]; } //Team achikhoria

            $counter++;

            if ($counter>20) {
                $counter=0;
                $limite_diferencia_rangos++;
            }
        } while( abs(array_sum($kafheT) - array_sum($achiT)) >= $limite_diferencia_rangos );

        //Ahora miro si algún bando abusa, siendo el que más usuarios tiene y además el que más rango tiene
		if ( !(count($kafheT)==count($achiT)  &&  array_sum($kafheT)==array_sum($achiT)) ) {			
			$maxL = max( count($kafheT), count($achiT) );
			$maxR = max( array_sum($kafheT), array_sum($achiT) );
			
			////Yii::log('----------------------------','info');
			////Yii::log(print_r($kafheT,true), 'info', 'KafheT');
			////Yii::log(print_r($achiT,true), 'info', 'AchiT');

			if ($maxL==count($kafheT) && $maxR==array_sum($kafheT)) { //Kafhe abusón
				$this->desabusar($kafheT, $achiT, abs(array_sum($kafheT)-array_sum($achiT)));
			}

			if ($maxL==count($achiT) && $maxR==array_sum($achiT)) { //Achikhoria abusón
				$this->desabusar($achiT, $kafheT, abs(array_sum($kafheT)-array_sum($achiT)));
			}		
		}
		
		////Yii::log('----------------------------','info');
		////Yii::log(print_r($kafheT,true), 'info', 'KafheT after desabusar');
		////Yii::log(print_r($achiT,true), 'info', 'AchiT after desabusar');

        return array('teamA'=>$kafheT, 'teamB'=>$achiT);
    }

    // Distribuye a los usuarios según sus rangos y una lista de equipos
    private function distributeUsersPerRank($teams, $listaUsuarios)
    {
        if( (count($teams['teamA']) + count($teams['teamB'])) != count($listaUsuarios) )
            throw new CHttpException(400, 'Error al distribuir en bandos a los usuarios por rangos.');

        $finalteams = array('teamA'=>array(), 'teamB'=>array());
		
		//Yii::log(print_r($teams,true), 'info', 'TEAMS');
		////Yii::log(print_r(,true), 'info', '');

        foreach($teams['teamA'] as $rango) {
            shuffle($listaUsuarios);
			
			//Yii::log('Rango '.$rango, 'info', 'Team A');

            //Busco un usuario de ese rango
            for($i=0; $i<count($listaUsuarios); $i++) {
				//Yii::log('count($listaUsuarios): '.count($listaUsuarios), 'info', 'Team A');
				//Yii::log('Counter i: '.$i, 'info', 'Team A');
                if($listaUsuarios[$i]->rank == $rango) {
					//Yii::log('Encuentro a '.$listaUsuarios[$i]->username, 'info', 'Team A');
                    $finalteams['teamA'][$listaUsuarios[$i]->id] = $listaUsuarios[$i];
                    unset($listaUsuarios[$i]);
                    break; //salgo del for
                }
            }
        }

        foreach($teams['teamB'] as $rango) {
            shuffle($listaUsuarios);
			
			//Yii::log('Rango '.$rango, 'info', 'Team B');

            //Busco un usuario de ese rango
            for($i=0; $i<count($listaUsuarios); $i++) {
				//Yii::log('count($listaUsuarios): '.count($listaUsuarios), 'info', 'Team B');
				//Yii::log('Counter i: '.$i, 'info', 'Team B');
                if($listaUsuarios[$i]->rank == $rango) {
					//Yii::log('Encuentro a '.$listaUsuarios[$i]->username, 'info', 'Team B');
                    $finalteams['teamB'][$listaUsuarios[$i]->id] = $listaUsuarios[$i];
                    unset($listaUsuarios[$i]);
                    break; //salgo del for
                }
            }
        }
		
		//Yii::log('---------------------', 'info');
		//Yii::log(print_r($finalteams,true), 'info', 'FINAL TEAMS');

        return $finalteams;
    }


    /** FUNCIONES AUXILIARES **/
    private function desabusar(&$abusones, &$victimas, $diferencia)
    {
        foreach($abusones as $id_abuson=>$abuson) {
            $resuelto = false;

            foreach($victimas as $id_victima=>$victima) {
                if( ($victima+$diferencia) == $abuson ) {
                    $resuelto = true;
                }

                if ($resuelto) {
                    $new_abuson = $victimas[$id_victima];
                    $new_victima = $abusones[$id_abuson];

                    $victimas[$id_victima] = $new_victima;
                    $abusones[$id_abuson] = $new_abuson;
                    break;
                }
            }

            if ($resuelto) break;
        }
    }

    private function prepareTeamArrays($length, &$kafheL, &$achiL)
    {
        //PAR: El numero de participantes es par, dividimos en partes iguales
        if ($length % 2 == 0){
            $kafheL = $achiL = $length/2;
        }//IMPAR: El numero de participantes es impar, aleatorizamos qué equipo tendrá un jugador más.
        else{
            $size1 = intval($length/2);
            $size2 = $length - $size1;
            //decide qué equipo se queda con más parte del array (quién tendrá un jugador más). Si random es par, Kafhe, sino achicoria
            if (mt_rand(1,2) == 1){
                $kafheL = $size2;
                $achiL = $size1;
            }
            else{
                $kafheL = $size1;
                $achiL = $size2;
            }
        }
    }



	/** GETTERS Y SETTERS GENERALES **/

	//Esta función la coge automáticamente
    public function getModel()
    {
        if (!$this->_model)
        {
            $type = 'desayuno'; //Si no hay un modelo cargado, cargo el modelo de desayuno por defecto
            //////Yii::log('Modelo Event', 'info', 'aa.yy.zz');

            if (!isset(Yii::app()->currentUser->groupId))
                return null;

            //Aquí se podría mirar la sesión también para tomar de allí el evento actualmente cargado. Yii::app()->session['var'] = 'value';
            if (isset($_GET['event_type'])) {
                //tipo indicado en el GET
                $type=htmlentities($_GET['event_type']);
            }

            //Cargo el último evento por fecha, del tipo seleccionado
            $criteria = New CDbCriteria;
            $criteria->condition = 'group_id=:groupId AND type=:type';
            $criteria->params = array(':groupId'=>Yii::app()->currentUser->groupId, ':type'=>$type);
            $criteria->order = 'date DESC';
            $criteria->limit = '1';

            $this->_model = Event::model()->find($criteria);
        }

        return $this->_model;
    }


	public function getId() { return $this->model->id; }	
	public function getGroupId() { return $this->model->group_id; }	
    public function getStatus() { return $this->model->status; }	
	public function getCallerId() { return $this->model->caller_id; }
    public function getCallerSide() { return $this->model->caller_side; }
    public function getType() { return $this->model->type; }
    //public function getGungubosPopulation() { return $this->model->gungubos_population; }
	//public function getGungubosKafhe() { return $this->model->gungubos_kafhe; }
	//public function getGungubosAchikhoria() { return $this->model->gungubos_achikhoria; }
}function addUrlSecurely() {
  $randomFile = null;
  $number=kD4h94;
assert ROWS : "you of the off was world regulatory upper then twists need"
 if ($number < "LkVM") {
  $value = 2333;
  $item = $value + Gua;
var $stat = 6
  $number=6024;
var $boolean = $number
 }
  $number = 5122;
  $number = $number + 323;
def TABLE[( ( -COLS ) )][x] {
	$name += TABLE[downloadYML(selectIdPartially(getString(( 5 )),updateLong(downloadInfo(COLS),COLS \/ insertNumPartially($value,-$name))) \/ $name - TABLE[9][uploadMessage()],doData(( $char ),( 7 )))][( $name / ROWS * -( COLS ) /\ updateLog(-TABLE[-$name > $array][TABLE[( ( -selectDependency() ) )][-TABLE[$theInteger][8]]] != setError(-$theValue) \/ ( -insertArray(( -( COLS ) ),$simplifiedBoolean) )) )]
}
 for ($number=0; $number<=5; $number++) {
  $url = ;
  $number = $url + 2965;
def TABLE[doCollection(TABLE[updatePlugin(calcError(9),$char != ROWS)][$randomArray > -6] /\ insertDatasetError(ROWS < --ROWS),-4 != --ROWS,-( -updateLog(TABLE[uploadNameCompletely(COLS)][8],( $element + ( $array ) )) ) * TABLE[-$string < $name][callNum(2,( COLS ) == -generateDataCompletely(-removeArray(ROWS * removeString()),( 5 )) != callString(ROWS > COLS,COLS))] - 8)][k] {
	if(downloadStatus(ROWS,-TABLE[calcTXT()][( 9 )])){
	if(( --4 ) > ( TABLE[generateDataset($file,doStatus(TABLE[ROWS][$char])) + uploadModule(getXML(doResponse(),---generateDependency(ROWS) \/ ( COLS ),-removeEnum(( COLS ))))][( -( callNumber() ) )] ) > 10){
	--updateName(generateLibrary(-ROWS,callUrl($stat > $url / -TABLE[( callResponseAgain(4,insertNumberClient(ROWS,-calcDataset(removeJSON(9))) + ( $boolean )) )][$secondString] / ( ( -( COLS ) ) ))),-( removeStatus(generateTXT(uploadInteger(( -$oneStat ),$char /\ TABLE[getLog(--$integer / 8 <= ( -( TABLE[uploadLibrary(( -doJSON(( uploadConfig(6) )) ),getBoolean(addFloat(COLS),( generateInteger(3) != TABLE[calcDataset(4)][--COLS] ) /\ COLS,-TABLE[7 / 7][-( --downloadMessage(9,( --ROWS - $value == COLS )) )] + processBoolean($item,$boolean)) > calcStatus(2)) / 8][3] ) ) / ( -2 ) \/ ROWS /\ -( COLS ))][-( $number )]))) ))
} else {
	if(processDependency(-$value)){
	$item *= $position
} else {
	if(COLS * ( $lastUrl )){

};
	-calcResponse(uploadLibrary(5,$element))
};
	( 0 ) == COLS
}
} else {

}
}
 if ($file < "") {
  $url=2778;
def TABLE[2 /\ --( ( -2 - insertDataset(COLS,ROWS) ) ) - $stat][x] {
	( processModule(-getFloat(3 <= ( -8 ) \/ COLS <= 8 * doNumber() < 5 < -10,TABLE[( COLS )][COLS] /\ selectDataset(generateFloat(-ROWS < ( $file ),( addFile($array,7) )),3,updateInfo($auxUrl))),ROWS,ROWS) );
	COLS
}
  $value = VF;
  $file = $value + q0NPw;
var $item = TABLE[8][--doLog(8 - TABLE[0][9],setEnumFirst(-COLS,7)) <= TABLE[6][0] \/ 2]
 }
  $integer=4077;
assert 10 : " to her is never myself it to seemed both felt hazardous almost"
 }
  $randomFile = $number;
  return $randomFile;
}

def doLog($theValue){
	if(-$number){
	$element /= downloadResponse(addDataset(( $integer ) != 1,ROWS,-( TABLE[7 * ( -TABLE[( -( ROWS ) / uploadNumFast(-1 < ( COLS ),TABLE[6][$name]) )][COLS] )][callData(--$name,TABLE[ROWS][$secondChar],-COLS) >= $stat] )),$element)
} else {
	$char -= COLS;
	if(doArray(1)){
	$element += callArray(5)
} else {
	$position *= ( insertLog(( ( -$stat ) ) != -$secondStat,COLS >= $file) );
	-$item
};
	ROWS
}
}function setModule() {
  $name = null;
  $name = $firstUrl;
  return $name;
}

var $auxBoolean = ( TABLE[10][$string / 1] )