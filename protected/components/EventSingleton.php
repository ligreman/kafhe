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
				
		$this->getModel(); //Por si acaso
			
		//Probabilidades de cada bando
		$bandos = Yii::app()->usertools->calculateSideProbabilities($this->getGungubosKafhe(), $this->getGungubosAchikhoria());
		
		//Elijo bando "perdedor"		
		$kafhePerc = $bandos['kafhe'] * 100;
		$randomSide = mt_rand(1, 10000);
		if ( 1<=$randomSide && $randomSide<=$kafhePerc ) $bandoPerdedor = 'kafhe';
		else  $bandoPerdedor = 'achikhoria';
		
		//Preparo un array con las probabilidades de cada uno de los usuarios del bando perdedor
		$probabilidades = Yii::app()->usertools->calculateProbabilities(true, $bandoPerdedor);
		if ($probabilidades === null) return null;
		
		//Elijo llamador "ganador" dentro de ese bando
		$randomCaller = mt_rand(1, 10000);
		$anterior = 0;	
		$caller = null;
	
		foreach($probabilidades as $user=>$valor) {
			$valor = $valor * 100; //tiene 2 decimales así que lo convierto a entero
			if ($valor == 0) continue;
			
			if ( (($anterior+1) <= $randomCaller) && ($randomCaller <= ($anterior+$valor)) ) {
				$caller = $user;
			}
			
			$anterior += $valor;
		}
		
		if ($caller === null) return null;
		
		return array('side'=>$bandoPerdedor, 'userId'=>$caller);
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
    public function getPastOrder()
	{
		$group_id = Yii::app()->currentUser->groupId;
		$event = Event::model()->findAll(array( 'condition'=>'status=:status AND group_id=:group', 'params'=>array(':status'=>Yii::app()->params->statusCerrado, ':group'=>$group_id), 'order'=>'date DESC', 'limit'=>1) );
		
		if ($event == null)
			$eventId = null;
		else
			$eventId = $event->id;
		
		return $this->getOrder($eventId);
	}


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
                    $newR->date = date('Y-m-d');

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
                    $newR->date = date('Y-m-d');

                    //Miro a ver si estoy en el ranking y con qué rango
                    for ($i=0; $i<count($ranking); $i++) {
                        //¿Soy yo?
                        if($ranking[$i]->user_id == $user->id) {
                            $estoy_en_ranking = true; //Estoy en el ranking
                            $colocado = true;

                            if($ranking[$i]->rank > $user->rank) {
                                //No hago nada
                            } elseif($ranking[$i]->rank > $user->rank) {
                                $ranking[$i]->date = date('Y-m-d'); //Actualizo la fecha simplemente
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

		////Yii::log('----------------------------','info');
		////Yii::log(print_r($listaRangos,true), 'info', 'Lista rangos');
		////Yii::log(print_r($listaUsuarios,true), 'info', 'Lista usuarios');
		
        //Calculo reparto de rangos
        $teams = $this->bruteForceTeamDivision($listaRangos);
		////Yii::log('----------------------------','info');
		////Yii::log(print_r($teams,true), 'info', 'TEAMS');
        $teams = $this->distributeUsersPerRank($teams, $listaUsuarios);
		////Yii::log(print_r($teams,true), 'info', 'TEAMS AFTER Distribute');

        if ($exAgenteLibre === null) {
            $finalteams['kafhe'] = $teams['teamA'];
            $finalteams['achikhoria'] = $teams['teamB'];
        } else {
            $bandoAnterior = Yii::app()->usertools->getPreviousSide(); //bando anterior del ex-libre
			if ($bandoAnterior === null)
				throw new CHttpException(400, 'Error al obtener el bando anterior del agente libre del evento '.Yii::app()->event->id.'.'); //Tiene que existir por narices si existe un agente libre

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
    public function getGungubosPopulation() { return $this->model->gungubos_population; }
	public function getGungubosKafhe() { return $this->model->gungubos_kafhe; }
	public function getGungubosAchikhoria() { return $this->model->gungubos_achikhoria; }
}