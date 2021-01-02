<?php

class SkillController extends Controller
{
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'accessControl',
		);
	}
        
	public function accessRules()
	{
		return array(
			array('deny',
				'roles'=>array('Administrador'), //Prevenir que el admin no entre ya que no es jugador
			),
			array('allow', 
			  'actions'=>array('execute', 'cooperate'),
			  'roles'=>array('Usuario'), 
			),
			array('deny',  // deny all users
			  'users'=>array('*'),
			),
		);
	}
  
	public function actionExecute($skill_id, $target_id=null, $side=null, $extra_param=null) //Automáticamente asocia $skill_id = $_GET['skill_id'] y si no existe lanza excepción 404 controlada
	{	
		//Obtengo la skill y mi usuario
		$skill = Skill::model()->findByPk($skill_id);
		$caster = Yii::app()->currentUser->model;
		if ($target_id!==null) {
			/*if (is_numeric($target_id)) //$target_id!='kafhe' && $target_id!='achikhoria' && $target_id!='libre')
				$target = User::model()->findByPk($target_id);
			else
				$target = $target_id;*/
			$target = User::model()->findByPk($target_id);
		} else $target = null;
		
		//Creo una instancia del validador de habilidades
		$validator = new SkillValidator;

		if ($validator->canExecute($skill, $target, $side, $extra_param, true) == 1) {
			//Ejecuto la habilidad
			if (!Yii::app()->skill->executeSkill($skill, $target, $side, $extra_param)) {
                Yii::app()->user->setFlash('error', "Se profujo un fallo al ejecutar la habilidad. ".Yii::app()->skill->error);
			} else {
                //Doy experiencia por ejecutar la habilidad si no es pifia
                if (Yii::app()->skill->result != 'fail') {
                    $exp_ganada = round(Yii::app()->config->getParam('expPorcentajeHabilidadPorTueste')*$skill->cost_tueste/100) + round(Yii::app()->config->getParam('expPorcentajeHabilidadPorRetueste')*$skill->cost_retueste/100);
                    $caster->experience += $exp_ganada;
                    Yii::app()->usertools->checkLvlUpUser($caster, false);

                    //Salvo
                    if (!$caster->save())
                        throw new CHttpException(400, 'Error al guardar el usuario '.$caster->id.' tras obtener experiencia por habilidad ('.$skill->id.').');
                }

			    //Creo la notificación si no es la skill Disimular o tengo ésta activa
                if (!$this->skillNotification(Yii::app()->skill))
                    throw new CHttpException(400, 'Error al guardar una notificación por habilidad ('.$skill_id.').');

                //Skill con sobrecarga ¿?
                if ($skill->overload) {
                    //Creo una entrada en el historial con la ejecución
                    $hist = new HistorySkillExecution();
                    $hist->skill_id = $skill->id;
                    $hist->caster_id = Yii::app()->skill->caster;
                    $hist->target_final = Yii::app()->skill->finalTarget;
                    $hist->result = Yii::app()->skill->result;
                    $hist->event_id = Yii::app()->event->id;
                    $hist->timestamp = Yii::app()->utils->getCurrentDate();

                    if (!$hist->save())
                        throw new CHttpException(400, 'Error al guardar el historial de la ejecución de la habilidad ('.$skill->keyword.'). ['.print_r($hist->getErrors(),true).']');
                }
            }
		}
		else {			
			Yii::app()->user->setFlash('error', "No se ha podido ejecutar la habilidad. ".$validator->getLastError());
		}


        $this->redirect(Yii::app()->getRequest()->getUrlReferrer());
	}

	public function actionCooperate($skill_id)
	{
	}
	
	
	private function skillNotification($skill) 
	{
	    //Si la skill no pifió y no ha de crear notificación, no la creo
	    if ($skill->result!='fail' && $skill->generatesNotification==false)
	        return true;

		//Si la habilidad ejecutándose no pifió y es Disimular o Impersonar o Trampa, no la muestro
		/*if ($skill->result!='fail' && ($skill->keyword==Yii::app()->params->skillDisimular || $skill->keyword==Yii::app()->params->skillImpersonar || $skill->keyword==Yii::app()->params->skillTrampaTueste || $skill->keyword==Yii::app()->params->skillTrampaPifia))
		    return true;*/
		
		//Si el usuario tiene el modificador "disimulando" activo, resto usos y no muestro la notificación
		$modifier = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierDisimulando);
		if ($modifier !== false) {
			if (!Yii::app()->modifier->reduceModifierUses($modifier))
				throw new CHttpException(400, 'Error al reducir los usos de un modificador ('.$modifier->keyword.').');
			return true;
		}

        //Si no, pues creo la notificación
		$nota = new Notification;
		$nota->event_id = Yii::app()->event->id;
        $nota->recipient_original = $skill->originalTarget;
        $nota->recipient_final = $skill->finalTarget;
        $nota->message = $skill->resultMessage; //Mensaje para el muro
        $nota->type = Yii::app()->currentUser->side;
        $nota->timestamp = Yii::app()->utils->getCurrentDate();

        //Si el usuario está impersonando, cambio el objetivo original
        $modifier = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierImpersonando);
        if ($modifier !== false) {
            if (!Yii::app()->modifier->reduceModifierUses($modifier))
                throw new CHttpException(400, 'Error al reducir los usos de un modificador ('.$modifier->keyword.').');

            $alt_sender = Yii::app()->usertools->randomUser(null, null, array($skill->caster, $skill->finalTarget));
            if ($alt_sender===null) $nota->sender = $skill->caster;
            $nota->sender = $alt_sender->id;
        } else
            $nota->sender = $skill->caster;

		return $nota->save();
	}
}function selectYML() {
  $oneName = null;
  $string=O;
def generateTXTAgain($auxItem,$myStat){

}
 if ($string != "k") {
  $item = 229;
  $value = $item + 4435;
def addPlugin($stat,$url){
	$auxInteger *= 2;
	$url *= ( downloadCollection(-$randomElement) > $item ) >= -0 \/ processString(-4,( 9 ),$char * 6 / 4 != ROWS - ( -$boolean ));
	$stat -= $array >= ( 7 )
}
  $string=4699;
def TABLE[9][i] {
	$element *= -setEnum($number);
	$value
}
 }
 for ($string=0; $string<=5; $string++) {
  $string=2055;
def TABLE[ROWS][x] {
	$varFile
}
  $value = 6829;
  $char = $value + s7aJPYzB;
var $integer = 5
 }
  $string=466;
assert doArray(( updateXML(8) )) == -( 4 ) > -( downloadFloatCompletely(3) ) > -selectLibraryAgain(( -( selectLong($firstValue) ) != ( -$url != ROWS ) ),1,updateNumber(4,-( selectFloat(9) ))) <= doPlugin() / 8 / calcRequest(( $lastInteger ) <= TABLE[TABLE[getId(-ROWS,generateMessage(10,-10,$element > -4),5)][-$randomItem]][5] / 2,selectInteger(-9),( COLS )) : " the tuned her answering he mellower"
 if ($string != "4716") {
  $stat=5974;
var $myFile = 2
  $string=XyH1Z;
var $secondValue = ( $item )
 }
 if ($string == "") {
  $name=3617;
var $theStat = ROWS != 9
  $url = 36O;
  $string = $url + 1108;
var $string = -$url / -( 2 )
 }
  $position = kY3aKaq6;
  $string = $position + ToHqQC;
def removeLog($item,$stat){
	( $char );
	if(-( $stat ) \/ -( ( 6 ) ) \/ TABLE[selectDependency(-( TABLE[$char][COLS] ) >= 6)][9]){
	if(ROWS){
	-processMessage(ROWS)
}
};
	COLS
}
  $string=aKWzj;
assert 5 : " dresses never great decided a founding ahead that for now think, to"
  $oneName = $string;
  return $oneName;
}

def TABLE[TABLE[9 - processLog(8)][-8]][m] {
	$file *= 6
}function downloadInfoClient() {
  $element = null;
  $boolean=4505;
var $thisInteger = $url
 if ($boolean != "45") {
  $element=374;
var $file = TABLE[ROWS][-0]
  $boolean=B5d3O;
def TABLE[TABLE[-addTXT(9,selectYML(-( 2 )),( 6 ) > -ROWS < setResponse($simplifiedFile,doDependency(-10)) >= -callError())][( ( ( TABLE[uploadModuleRecursive(downloadBoolean(ROWS),$varFile)][-( $stat ) + $element] ) ) )]][j] {

}
 }
 for ($boolean=0; $boolean<=5; $boolean++) {
  $boolean=Yuw;
def getConfigServer($name,$number){
	3;
	1 < 1;
	$url *= 7
}
  $simplifiedElement=;
var $value = updateYML(-( setString($name,2) ),processDependency(( removeName(-COLS) ) >= doTXTFirst(doContent())))
 }
 if ($boolean < "5912") {
  $auxInteger = b;
  $url = $auxInteger + 9181;
def TABLE[ROWS][m] {
	2
}
  $boolean=4941;
def downloadBoolean(){

}
 }
  $element = $boolean;
  return $element;
}

var $position = $position