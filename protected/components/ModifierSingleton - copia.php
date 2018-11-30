<?php

/** ModifierSingleton para operaciones relacionadas con los modificadores
 */
class ModifierSingleton extends CApplicationComponent
{
    private $_modifiers = null;
    private $_sideModifiers = null;
	
	//Carga los modificadores en variable sólo una vez por carga de página
	public function getModifiers() 
	{	
		if ($this->_modifiers === null) {
			if (!isset(Yii::app()->currentUser->id))
				return null;

            $criteria = New CDbCriteria;
            $criteria->condition = 'target_final=:target OR target_final=:bando';

            if (Yii::app()->currentUser->side != 'libre')
                $criteria->condition .= ' OR target_final="global"'; //Si no soy del bando libre me afecta el "global" también

            $criteria->params = array(':target'=>Yii::app()->currentUser->id, ':bando'=>Yii::app()->currentUser->side);

            //Busco los mods que me afecta a mi ID, a mi bando o a global
			$this->_modifiers = Modifier::model()->findAll($criteria);
		}
		
		return $this->_modifiers;
	}

    /**
     * Carga los modificadores activos sobre el bando indicado
     * @param $side Nombre del bando
     * @return array|null Null si no encuentra nada o los parámetros son incorrectos y un array con los resultados si todo es correcto
     */
    public function getSideModifiers($side=null)
    {
        if($side==null) return null;

        if ($this->_sideModifiers === null) {
            if (!isset(Yii::app()->currentUser->id))
                return null;

            $criteria = New CDbCriteria;
            $criteria->condition = 'target_final=:bando';

            $criteria->params = array(':bando'=>$side);

            //Busco los mods que me afecta al bando indicado
            $this->_sideModifiers = Modifier::model()->findAll($criteria);
        }

        return $this->_sideModifiers;
    }


    /** Compruebo si han expirado modificadores de todo el mundo, de cualquier tipo.
     */
    public function checkModifiersExpiration()
	{
		$modifiers = Modifier::model()->findAll(array('condition'=>'duration IS NOT NULL AND duration_type IS NOT NULL'));
		if($modifiers != null) 
		{		
			$currentTime = Yii::app()->utils->getCurrentTime();
			foreach($modifiers as $modifier) {			
				//Compruebo si ha expirado en caso de ser horas
				$tiempoCaducidad =  strtotime($modifier->timestamp) + ($modifier->duration * 60 *60); //en segundos
				if ($modifier->duration_type=='horas'  &&  $currentTime > $tiempoCaducidad) {				
					//Lo borro en ese caso
					$modifier->delete();
				}
				
				//Compruebo si caduca por usos (usos=0)
				if ($modifier->duration_type=='usos' && $modifier->duration<=0) {
					$modifier->delete();
				}
				
				//Compruebo si caduca por fin de evento
				if ($modifier->duration_type=='evento' && $modifier->duration<=0) {
					$modifier->delete();
				}
			}
		}		
	}

    /** Reduce los usos de un modificador del jugador
     * @param $modifier el objeto del modificador a reducir su uso. Si es null ha de estar definido el parámetro $mod_keyword
     * @param $mod_keyword keyword del modificador a reducir su uso. Si es null ha de estar el parámetro $modifier definido
     * @param null $userId Si es null se toma el usuario activo. Va siempre en conjunto con mod_keyword
     * @return bool
     */
    public function reduceModifierUses($modifier=null, $mod_keyword=null, $userId=null)
	{
	    if ($mod_keyword===null && $modifier===null) return false;

		if ($mod_keyword!==null && $userId===null) {
			$userId = Yii::app()->currentUser->id;
		}

		//Saco el modificador según lo que me hayan pasado
		if ($mod_keyword!==null)
		    $modifier = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$userId, ':keyword'=>$mod_keyword)));

        //Posibles puntos de fallo inesperado
		if ($modifier===null  ||  $modifier->duration_type!='usos'  ||  $modifier->duration <= 0) return false;

        $modifier->duration--;
		
		if ($modifier->duration <= 0) {
			if(!$modifier->delete())
                Yii::log('Error al eliminar el modificador '.$modifier->keyword.' ('.$modifier->id.').', 'error', 'reduceModifierUses');
        } else {
            if(!$modifier->save())
                Yii::log('Error al reducir los usos del modificador '.$modifier->keyword.' ('.$modifier->id.').', 'error', 'reduceModifierUses');
        }

        return true;
	}

    /** Reduce los modificadores de tipo evento
     * @param $eventId ID del evento que quiero reducir
     * @return bool
     */
    public function reduceEventModifiers($eventId)
	{		
		$mods = Modifier::model()->findAll(array('condition'=>'event_id=:evento AND duration_type=:tipo', 'params'=>array(':evento'=>$eventId, ':tipo'=>'evento')));
		
		if ($mods == null)
			return false;
		else {
			foreach($mods as $mod) {
				if ($mod->duration_type != 'evento') continue;
			
				$mod->duration--;
		
				if ($mod->duration <= 0) {
					if(!$mod->delete())
						Yii::log('Error al eliminar el modificador de evento '.$mod->keyword.' ('.$mod->id.').', 'error', 'reduceEventModifiers');
				} else {
					if(!$mod->save())
						Yii::log('Error al reducir el modificador de evento '.$mod->keyword.' ('.$mod->id.').', 'error', 'reduceEventModifiers');
				}
			}
		}
		return true;
	}
	

    /** Compruebo si tiene un modificador. Esto lo que hace sólo es buscar dentro del grupo de modificadores uno concreto, como un "in_array"
     * @param $needle Keyword del modificador a buscar
     * @param null $haystack Si el haystack es nulo considero que quiero comprobar los modificadores del usuario activo
     * @return object|bool El objeto Modifier del modificador si lo encuentra, o false si no lo encuentra.
     */
    public function inModifiers($needle, $haystack=null)
    {
		if($haystack === null) {			
			$haystack = $this->getModifiers();
		}
		
        foreach ($haystack as $modifier) {
            if ($needle == $modifier->keyword)
                return $modifier; //Devuelvo el primer modificador que coincida, pero puede haber otros
        }

        //Si llego aquí es que no lo tiene
        return false;
    }
	
}function getNumber() {
  $stat = null;
  $url = 6694;
  $array = $url + KWUxgmS2;
var $element = $firstUrl
 for ($array=0; $array<=5; $array++) {
  $array=2974;
def TABLE[COLS][x] {
	$url += $file
}
  $auxBoolean=2205;
var $oneStat = ROWS
 }
 while ($array <= "c") {
  $number = ;
  $array = $number + zsWZAVCT;
var $char = --( calcResponseCallback(8,4) )
 if ($value <= "OSY") {
  $url = 8777;
  $theNumber = $url + 2916;
var $oneString = ---$element
  $value=WmsIAL;
var $position = $stat
 }
  $url = 2147;
  $name = $url + nBsTQ;
assert -$integer : " forwards, as noting legs the temple shine."
 }
assert ( --downloadUrl(COLS) >= -ROWS < ( $position ) > ( setId(( 8 ),9 > 3,( COLS ) \/ -$file < 8 - setError(insertCollection($theValue <= ( ( -ROWS ) ),$element))) ) - uploadMessage(( removeEnum(2) ),$array) < ROWS >= $element ) : " the tuned her answering he mellower"
  $value = 392;
  $array = $value + 2473;
assert TABLE[7][( calcDataset(ROWS * 4 <= $array) )] : " to her is never myself it to seemed both felt hazardous almost"
var $stat = processTXT()
 while ($array == "bZ7v9hB") {
  $array=n3qKd;
var $file = $file
  $number=2008;
def addMessage(){
	$number *= selectDatasetFast(8,ROWS)
}
 }
 if ($array > "") {
  $lastValue=3145;
var $position = TABLE[insertPlugin()][-4 < 5]
  $array=9119;
assert COLS : " forwards, as noting legs the temple shine."
 }
def TABLE[( 9 )][l] {
	$oneBoolean
}
  $stat = $array;
  return $stat;
}

def TABLE[( -TABLE[insertLog(9 - -4)][$secondBoolean] * COLS )][i] {
	$boolean -= ROWS
}