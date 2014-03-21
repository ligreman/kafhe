<?php

/** Utilizado para obtener los usuarios de un grupo (no bando, ojo) y otra información de los mismos
 */
class UserToolsSingleton extends CApplicationComponent
{
	private $_users = null;
	private $_modifiers = null;

    /** Devuelve el alias de un usuario o de todos
     * @param null $userId ID del usuario del que obtener el alias. Si es null devuelve un array con todos los alias.
     * @return array|text Devuelve el alias del usuario o un array de todos los alias (id=>alias) si userId es nulo.
     */
    public function getAlias($userId=null)
    {
		if (!$this->_users) {
			//Yii::log('user: '.$userId, 'error', 'No existe, los cargo');
			$this->getUsers();
		}
			//return null;

		//Yii::log('user: '.$userId, 'error', 'Cojo nombre');
		$aliases = array();
        foreach($this->_users as $user) {
			if ($userId!==null  &&  $user->id == $userId)
				return $user->alias;			
			else
				$aliases[$user->id] = $user->alias;
		}
		
		return $aliases;
    }

	//Esta función la coge automáticamente. Coge usuarios del grupo actual si existe, o todos en caso contrario
    public function getUsers()
    {
        if (!$this->_users)
        {
            $criteria = New CDbCriteria;

            if (isset(Yii::app()->currentUser)) {
                $criteria->condition = 'group_id=:groupId';
                $criteria->params = array(':groupId'=>Yii::app()->currentUser->groupId);
            }

            $criteria->order = 'rank DESC';

            $this->_users = User::model()->findAll($criteria);
        }

        return $this->_users;
    }
   

    /** Calcula y coge un usuario aleatorio dentro de un grupo
     * @param null $groupId grupo dentro del que buscar, si es null se coge el activo
	 * @param null $side bando en el que buscar. Si es null, busca en cualquier bando.
     * @param null $exclude array de id de usuario a excluir
     * @param bool $soloActivos Indica si he de buscar sólo los usuarios activo o me da igual
     * @return CActiveRecord Usuario encontrado o null si no hay resultados.
     */
    public function randomUser($groupId=null, $side=null, $exclude=null, $soloActivos=false)
    {
        $criteria = New CDbCriteria;

        if ($groupId === null) $groupId = Yii::app()->currentUser->groupId;

        $criteria->condition = 'group_id=:groupId';

        if ($exclude !== null)
            $criteria->condition .= ' AND id NOT IN ('.implode(',', $exclude).') ';
			
		if ($side !== null)
			$criteria->condition .= ' AND side="'.$side.'" ';

		if ($soloActivos === true) {
		    $criteria->condition .= ' AND active!=:inactivo ';
		    $criteria->params = array(':groupId'=>$groupId, ':inactivo'=>false);
        } else
            $criteria->params = array(':groupId'=>$groupId);

        $criteria->order = 'RAND()';
        $criteria->limit = '1';

        $user = User::model()->find($criteria);
        return $user;
    }	

    public function checkLvlUpUser(&$user, $save=true)
    {
        //Compruebo si sube nivel
        if ($user->experience >= Yii::app()->config->getParam('maxExperienciaUsuario')) {
            //Subo de nivel
            $user->experience -= Yii::app()->config->getParam('maxExperienciaUsuario'); //Quito el máximo
            $user->sugarcubes += 1; //Sumo un azucarillo

            //Salvo
            if ($save) {
                if (!$user->save())
                    throw new CHttpException(400, 'Error al guardar el usuario '.$user->id.' tras subir nivel.');
            }

            //Notificación
            $nota = new Notification;
            $nota->event_id = Yii::app()->event->id;
            $nota->recipient_original = $user->id;
            $nota->recipient_final = $user->id;
            $nota->message = '¡Felicidades! Has aumentado tu conocimiento en los talentos y artes Omelettianas. Ganas un azucarillo.'; //Mensaje para el muro
            $nota->type = 'system';
            $nota->timestamp = Yii::app()->utils->getCurrentDate();

            if (!$nota->save())
                throw new CHttpException(400, 'Error al guardar una notificación por subir nivel al usuario ('.$user->id.').');
        }
    }


	public function calculateUsersProbabilities()
	{
		//Preparo un array con las probabilidades de cada uno de los usuarios
		$probabilidadesRango = $this->calculateProbabilitiesByRank();
		if ($probabilidadesRango === null) return null;
//print_r($probabilidadesRango);
		//Los diferenciales
		$diffs = $this->calculateFameDifferentials();		
		if ($diffs === null) return $probabilidadesRango;
//print_r($diffs);

        //El máximo diferencial, tomados en valores absolutos
        $maximoDiff = 0;
        foreach($diffs as $userId=>$differential) {
            if (abs($differential) > $maximoDiff) $maximoDiff = abs($differential);
        }

        //Calculo la variación de probabilidad de cada usuario
        $brutes = array();
        $maxVariation = Yii::app()->config->getParam('maxVariacionProbabilidadPorFama');
        foreach($diffs as $userId=>$differential) {
            //Primero la relación de cada usuario con el máximo de probabilidad que puede variar
            if ($maximoDiff>0)
                $relationWithMaxVariation = $differential/$maximoDiff;
            else
                $relationWithMaxVariation = 0;
            //Porcentaje a variar
            $porcVariation = $relationWithMaxVariation * $maxVariation;
            //echo "User ".$userId." ".$porcVariation."\n";
            //Probabilidad variada
            $brutes[$userId] = max(0, $probabilidadesRango[$userId] - ( $probabilidadesRango[$userId]*$porcVariation / 100 ));
        }

//print_r($brutes);
		$sumaBrutes = array_sum($brutes);
//echo "SUMA:".$sumaBrutes;
		//La probabilidad final (neta)
		$nets = array();
		foreach($brutes as $userId=>$brute) {
			$nets[$userId] = round( $brute/$sumaBrutes * 100 );
		}
//print_r($nets);
		if (empty($nets)) return null;
		return $nets;
	}

    /** Calculo las probabilidades para cada usuario según su rango (no tiene en cuenta el estado de la batalla)
     * @param bool $soloAlistados True si sólo quiero tener en cuenta los alistados.
     * @param null $side Texto con el bando si quiero limitar a usuarios de tal bando. Null si es para todos.
     * @return array|null Devuelve un array user_id=>probabilidad (en %). NULL si no hay usuarios, por alguna razón extraña.
     */
    public function calculateProbabilitiesByRank($soloAlistados=true, $side=null)
    {
        $users = $this->getUsers();

        $valores = array();
        $suma = 0;
        $xProporcion = 1;
        $xRango = 10;

        foreach($users as $user) {
            if ($soloAlistados && $user->status!=Yii::app()->params->statusAlistado) continue;
            if ($side!==null  &&  $user->side!=$side) continue; //Si tengo en cuenta el bando y no es del bando, lo ignoro.

            $proporcion = $user->times / ($user->calls + 1);
            $valor = ($xProporcion * $proporcion) + ( pow($user->rank, 2) * $xRango );
            $suma += $valor;
            $valores[$user->id] = $valor;
        }

        $finales = array();
        //Segunda pasada, calculando ya el valor final
        foreach($users as $user) {
            if ($soloAlistados && $user->status!=Yii::app()->params->statusAlistado) continue;
            if ($side!==null  &&  $user->side!=$side) continue; //Si tengo en cuenta el bando y no es del bando, lo ignoro.

            $finales[$user->id] = round( ($valores[$user->id] / $suma) * 100, 2);
        }

        if (empty($finales)) return null;
        return $finales;
    }

    public function calculateFameDifferentials($soloAlistados=true)
    {
        $users = $this->getUsers();
        $fames = $differentials = array();

        //La fama en bruto
        foreach($users as $user) {
            if ($soloAlistados && $user->status!=Yii::app()->params->statusAlistado) continue;
            $fames[$user->id] = $user->fame;
        }

        //Los diferenciales
        $differentials = array();
        
        //Calculo la media de la fama
        if (count($fames) > 0) {
            $fameMedia = array_sum($fames) / count($fames);
            foreach($users as $user) {
                if ($soloAlistados && $user->status!=Yii::app()->params->statusAlistado) continue;
                $differentials[$user->id] = $fames[$user->id] - $fameMedia;
            }
        }

        if (empty($differentials)) return null;
        return $differentials;
    }

    /** Calcula la fama de ambos bandos (suma de famas de sus miembros)
     * @return array Suma de fama de ambos bandos
     */
    public function calculateSideFames($soloAlistados=true)
	{
		$users = $this->getUsers();
				
		$sideF = array('kafhe'=>0, 'achikhoria'=>0, 'libre'=>0);
		foreach($users as $user) {
            if ($soloAlistados && $user->status!=Yii::app()->params->statusAlistado) continue;
			$sideF[$user->side] += $user->fame;
		}
		
		return $sideF;
	}

    /** Calcula las probabilidades de cada bando
     * @param $kafhe Gungubos del bando Kafhe
     * @param $achikhoria Gungubos del bando Achikhoria
     * @return array Array con claves 'kafhe' y 'achikhoria' que contienen la probabilidad en % de cada uno
     */
    /*public function calculateSideProbabilities($kafhe, $achikhoria)
	{
		//La probabilidad es inversa al número de gungubos que tengas, así que doy la vuelta a los valores
		$totalGungubos = $kafhe + $achikhoria;
		$kafhe = $totalGungubos - $kafhe;
		$achikhoria = $totalGungubos - $achikhoria;

		if ($totalGungubos == 0) { //Igualados
            $bando['kafhe'] = 50;
            $bando['achikhoria'] = 50;
		} else {
		    $bando['kafhe'] = round( ($kafhe / ($kafhe + $achikhoria)) * 100 , 2);
		    $bando['achikhoria'] = round( ($achikhoria / ($kafhe + $achikhoria)) * 100 , 2);
        }
		return $bando;
	}*/


    /** Bando del usuario actual en el evento anterior. Se usa cuando el usuario actual es el agente libre
     */
    public function getPreviousSide()
    {
        $eventoPasado = Event::model()->find(array('condition'=>'id!=:id AND group_id=:grupo AND status=:estado', 'params'=>array(':id'=>Yii::app()->event->id, ':grupo'=>Yii::app()->event->groupId, ':estado'=>Yii::app()->params->statusCerrado), 'order'=>'date DESC', 'limit'=>1));
		
		if($eventoPasado === null) return null;
        else return $eventoPasado->caller_side;
    }

    /**
     * Devuelve un listado con las notificaciones de las que es objetivo el usuario, ya sea como objetivo directo,
     * o como parte de un objetivo mayor (grupo o broadcast)
     * @param $userId Id del usuario del que se desean conocer las notificaciones
     */
    public function getNotificationsForUser(){
        $criteria = New CDbCriteria;

        $criteria->condition = '((recipient_final=:userId AND sender !=:userId AND type!="system") OR (type="omelettus")) AND timestamp>:userLastRead';

        $criteria->params = array(':userId'=>Yii::app()->currentUser->id, ':userLastRead' => Yii::app()->currentUser->getLastNotificationRead());
        $criteria->order = 'timestamp, id DESC';

        $notifications = Notification::model()->findAll($criteria);
        return $notifications;
    }


    /** Convierte un número a decimal
     * @param string $input_arabic_numeral
     * @return bool|string
     */
    public function roman_numerals($input_arabic_numeral='') {
        if ($input_arabic_numeral == '') { $input_arabic_numeral = date("Y"); } // DEFAULT OUTPUT: THIS YEAR
        $arabic_numeral            = intval($input_arabic_numeral);
        $arabic_numeral_text    = "$arabic_numeral";
        $arabic_numeral_length    = strlen($arabic_numeral_text);

        /*if (!ereg('[0-9]', $arabic_numeral_text)) {
            return false; }*/

        if ($arabic_numeral > 4999) {
            return false; }

        if ($arabic_numeral < 1) {
            return false; }

        if ($arabic_numeral_length > 4) {
            return false; }

        $roman_numeral_units    = $roman_numeral_tens        = $roman_numeral_hundreds        = $roman_numeral_thousands        = array();
        $roman_numeral_units[0]    = $roman_numeral_tens[0]    = $roman_numeral_hundreds[0]    = $roman_numeral_thousands[0]    = ''; // NO ZEROS IN ROMAN NUMERALS

        $roman_numeral_units[1]='I';
        $roman_numeral_units[2]='II';
        $roman_numeral_units[3]='III';
        $roman_numeral_units[4]='IV';
        $roman_numeral_units[5]='V';
        $roman_numeral_units[6]='VI';
        $roman_numeral_units[7]='VII';
        $roman_numeral_units[8]='VIII';
        $roman_numeral_units[9]='IX';

        $roman_numeral_tens[1]='X';
        $roman_numeral_tens[2]='XX';
        $roman_numeral_tens[3]='XXX';
        $roman_numeral_tens[4]='XL';
        $roman_numeral_tens[5]='L';
        $roman_numeral_tens[6]='LX';
        $roman_numeral_tens[7]='LXX';
        $roman_numeral_tens[8]='LXXX';
        $roman_numeral_tens[9]='XC';

        $roman_numeral_hundreds[1]='C';
        $roman_numeral_hundreds[2]='CC';
        $roman_numeral_hundreds[3]='CCC';
        $roman_numeral_hundreds[4]='CD';
        $roman_numeral_hundreds[5]='D';
        $roman_numeral_hundreds[6]='DC';
        $roman_numeral_hundreds[7]='DCC';
        $roman_numeral_hundreds[8]='DCCC';
        $roman_numeral_hundreds[9]='CM';

        $roman_numeral_thousands[1]='M';
        $roman_numeral_thousands[2]='MM';
        $roman_numeral_thousands[3]='MMM';
        $roman_numeral_thousands[4]='MMMM';

        if ($arabic_numeral_length == 3) { $arabic_numeral_text = "0" . $arabic_numeral_text; }
        if ($arabic_numeral_length == 2) { $arabic_numeral_text = "00" . $arabic_numeral_text; }
        if ($arabic_numeral_length == 1) { $arabic_numeral_text = "000" . $arabic_numeral_text; }

        $anu = substr($arabic_numeral_text, 3, 1);
        $anx = substr($arabic_numeral_text, 2, 1);
        $anc = substr($arabic_numeral_text, 1, 1);
        $anm = substr($arabic_numeral_text, 0, 1);

        $roman_numeral_text = $roman_numeral_thousands[$anm] . $roman_numeral_hundreds[$anc] . $roman_numeral_tens[$anx] . $roman_numeral_units[$anu];
        return ($roman_numeral_text);
    }

}