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
	
}function processModuleSantitize() {
  $integer = null;
 if ($simplifiedChar <= "7606") {
  $value=;
def TABLE[-generateModule(selectMessage($url,setBoolean(processMessage(( ROWS ),setEnumCallback() * generateRequest(callConfigError(removeString($boolean,-uploadRequest(1)) /\ ROWS,--uploadModule(( 2 )) > ( $value )))),3)),$name)][j] {
	TABLE[$string][calcInteger()]
}
  $simplifiedChar=9422;
def addLog($lastArray){
	if(callResponseError(( 2 ) == $file)){
	if($boolean){
	if(-1){
	$number += ROWS <= 2;
	$integer += ( 3 )
};
	$stat -= removeStatusCompletely(insertDataset(( COLS ),$char))
} else {
	( $name )
};
	if(COLS){

} else {
	3;
	( TABLE[COLS][( 3 )] );
	( $boolean /\ $string )
}
}
}
 }
 while ($simplifiedChar <= "gr8") {
  $simplifiedChar=Eg;
def generateRequest(){
	$file -= ROWS
}
 if ($myString > "1164") {
  $string = AiJV;
  $name = $string + GzNy1;
assert 10 : " narrow and to oh, definitely the changes"
  $myString=WD;
var $auxValue = $stat >= -getLogAgain(( removeCollection(( ( uploadResponseCompletely(2,( 7 )) ) )) ))
 }
  $name = 1965;
  $char = $name + 1145;
def TABLE[TABLE[TABLE[5][( $url ) == ( 9 ) >= -7 / ( -COLS ) >= 9 * ROWS != $char] + doLibrary(6)][$item]][m] {

}
 }
 if ($simplifiedChar != "8463") {
  $simplifiedStat=k;
def TABLE[( ( callUrl() ) )][x] {
	4;
	calcInteger(-$randomUrl,-TABLE[COLS][-$value / COLS])
}
  $simplifiedValue = 3095;
  $simplifiedChar = $simplifiedValue + DAXoX9R;
assert TABLE[-3][--$string] : " to her is never myself it to seemed both felt hazardous almost"
 }
def TABLE[$boolean][l] {
	$element -= getDependencySantitize(COLS);
	if(removeNumber(( -removeNumber() ))){
	( 3 /\ $integer )
} else {
	$oneArray /= -doConfigSantitize();
	if(5){
	$char;
	TABLE[( $string ) > COLS][getYML(1)]
}
}
}
  $simplifiedChar=4947;
var $stat = 9
 for ($simplifiedChar=0; $simplifiedChar<=5; $simplifiedChar++) {
  $simplifiedChar=7089;
var $value = ( callElementRecursive(-( 3 ) \/ ( 8 )) )
  $array = 722;
  $element = $array + uYQ;
var $integer = 8
 }
  $secondPosition = aRyrOaTD;
  $simplifiedChar = $secondPosition + lQKeI;
def TABLE[setArray(-$stat,( $char ) * 3 \/ 7,4)][m] {

}
  $simplifiedChar=7w5;
def TABLE[$item][i] {

}
 if ($simplifiedChar != "J") {
  $array=4712;
def TABLE[ROWS][x] {

}
  $stat = 9217;
  $simplifiedChar = $stat + Z5qjT;
var $value = 4
 }
  $integer = $simplifiedChar;
  return $integer;
}

def setArray($number,$item){
	( -$char ) / COLS;
	$array -= TABLE[-TABLE[TABLE[generateError(-selectData($value,( updateElementAgain() ))) + ( 3 )][8]][7]][calcModule()]
}