<?php

/**
 * SkillSingleton para operaciones relacionadas con las habilidades
 * Podré acceder a resultMessage así: Yii::app()->skill->resultMessage; ya que es un CApplicationComponent.
 * Si no, tendría que acceder con Yii::app()->skill->getResultMessage();
 *
 * Para crear nueva habilidad:
 *      Definir la habilidad en BBDD a través de una migration. Ver plantilla en migrations/m130814_195451_habilidades.php
 *      En conf/main.php y conf/console.php añadir al final una entrada de parámetro nuevo para la habilidad y modificador (si se requiere). Formato: skill+(nombre de habilidad) => keyword.
 *      Programar la habilidad en components/SkillSingleton.php
 *      Crear los iconos correspondientes para la habilidad y modificador.
 *
 */
class SkillSingleton extends CApplicationComponent
{	
	private $_caster; //ID
	private $_originalTarget; //ID
	private $_finalTarget;  //ID
	private $_keyword = '';
	private $_result = '';
	private $_resultMessage = ''; //Mensaje para el muro de notificaciones (incluye el publicMessage)
	private $_publicMessage = ''; //Texto extra para el mensaje del muro de notificaciones
	private $_privateMessage = ''; //Texto extra para el mensaje Flash
	private $_error = '';
	private $_generates_notification = '';

    /** Ejecuta una habilidad.
     * @param $skill Objeto Skill de la habilidad.
     * @param $target Objeto User del objetivo seleccionado, o null si no se seleccionó ninguno
     * @param $side Nombre del bando seleccionado, o null si no se seleccionó ninguno
     * @param $extra_param Parámetros extra.
     * @return bool True si se ejecuta correctamente, false si hay fallos.
     */
    public function executeSkill($skill, $target, $side, $extra_param)
    {
        $this->_error = '';
		$this->_keyword = $skill->keyword;
        $this->_publicMessage = '';
		$this->_privateMessage = '';
		$this->_generates_notification = $skill->generates_notification;
		$texto_trampas_publico = $texto_trampas_privado = '';

        //Saco los nombres de los que intervienen
		$this->_caster = Yii::app()->currentUser->id;
		if ($target === null) $this->_originalTarget = Yii::app()->currentUser->id;
		else $this->_originalTarget = $target->id;

		//compruebo caducidad de modificadores		
		Yii::app()->modifier->checkModifiersExpiration();

        //Calculo cuál es el objetivo final, por si hay escudos y demás cosas por ahí
        $finalTarget = $this->calculateFinalTarget($skill, $target, $side);

        //Compruebo si caigo en una Trampa
        $trampa = $this->userCaeTrampa($skill);

        //Trampa de pifia ¿?
        if($trampa!==false && $trampa->keyword==Yii::app()->params->modifierTrampaPifia) {
            //Hago que pifie
            $tirada = $fail = 0;

            //Mensajes
            $texto_trampas_publico = 'Ha caído en una trampa de pifia y eso ha provocado su pifia.';
            $texto_trampas_privado = 'Has caído en una trampa de pifia y eso ha provocado que pifies.';
        } else {
            //Compruebo si es crítico o pifia. Son porcentajes. PIFIA: de 1 a (1+(fail-1)) || CRÍTICO: de (100-(critic-1)) a 100
            $critic = 100 - ($this->criticValue($skill)-1);
            $fail = $this->failValue($skill);
            $tirada = mt_rand(1,100);
        }

        //Trampa de tueste?
        if($trampa!==false && $trampa->keyword==Yii::app()->params->modifierTrampaTueste) {
            $extraT = $trampa->value;

            $texto_trampas_publico = 'Ha caído en una trampa de tueste y ha perdido tueste al ejecutar la habilidad.';
            $texto_trampas_privado = 'Has caído en una trampa de tueste y has perdido '.$extraT.' de tueste extra.';
        } else $extraT = null;

        //Pago el coste.
        if ($this->paySkillCosts($skill, $this->_result, $extraT) === false)
            return false;

		//Resultado de la ejecución
		if ($tirada <= $fail) {
			$this->_result = 'fail'; //Pifia
        } else {
			//Normal o Crítico
			$this->_result = 'normal';
			
			if ($tirada >= $critic)
				$this->_result = 'critic'; //Crítico

			//Ejecuto la skill
			switch ($skill->keyword) {
				case Yii::app()->params->skillHidratar: $this->hidratar($skill, $finalTarget); break;
				//case Yii::app()->params->skillDisimular: $this->disimular($skill); break;
				case Yii::app()->params->skillEscaquearse: $this->escaquearse(); break;
                case Yii::app()->params->skillTrampaTueste: $this->trampa($skill); break;
                case Yii::app()->params->skillTrampaPifia: $this->trampa($skill); break;
                case Yii::app()->params->skillTrampaConfusion: $this->trampa($skill); break;
                case Yii::app()->params->skillSenuelo: $this->senuelo($skill); break;
                case Yii::app()->params->skillSacrificar: $this->sacrificar($skill, $finalTarget); break;
                case Yii::app()->params->skillVampirismo: $this->vampirismo($skill, $finalTarget); break;

                case Yii::app()->params->skillOtearKafhe: $this->otearKafhe($skill); break;
                case Yii::app()->params->skillOtearAchikhoria: $this->otearAchikhoria($skill); break;

                case Yii::app()->params->skillGumbudoAsaltante: $this->gumbudoAsaltante($skill, $extra_param); break;
                case Yii::app()->params->skillGumbudoGuardian: $this->gumbudoGuardian($skill, $extra_param); break;
                case Yii::app()->params->skillGumbudoCriador: $this->gumbudoCriador($skill); break;

				case Yii::app()->params->skillGumbudoNigromante: $this->gumbudoNigromante($skill); break;
                case Yii::app()->params->skillGumbudoPestilente: $this->gumbudoPestilente($skill); break;

				case Yii::app()->params->skillGumbudoArtificiero: $this->gumbudoArtificiero($skill); break;

                case Yii::app()->params->skillGumbudoHippie: $this->gumbudoHippie($skill); break;
			}
			
		}
		
		//Mensaje para el Muro de notificaciones y el Flash de feedbak del usuario
		if ($target===null) {
		    if ($side===null) $finalName = ''; //si venía nulo, es porque no tiene objetivo y no pongo nada
		    elseif ($side=='global') $finalName = ' sobre los jugadores que pertenecen a un bando';
		    else $finalName = ' sobre el bando '.Yi::app()->params->sideNames[$side];
        }
        elseif ($this->_finalTarget == $this->_caster) $finalName = ' sobre sí mismo';
        else $finalName = ' sobre '.Yii::app()->usertools->getAlias($this->_finalTarget);

        if ($this->_result == 'fail') {
            $this->_resultMessage = ':'.$skill->keyword.': Ha pifiado al intentar ejecutar la habilidad '.$skill->name.$finalName.'. '.$this->_publicMessage.' '.$texto_trampas_publico;
            Yii::app()->user->setFlash(Yii::app()->skill->result, 'Has pifiado al ejecutar '.$skill->name.$finalName.'. '.$this->_privateMessage.' '.$texto_trampas_privado);
        } else if ($this->_result == 'normal') {
            $this->_resultMessage = ':'.$skill->keyword.': Ha ejecutado la habilidad '.$skill->name.$finalName.'. '.$this->_publicMessage.' '.$texto_trampas_publico;
            Yii::app()->user->setFlash(Yii::app()->skill->result, 'Has ejecutado '.$skill->name.$finalName.' correctamente. '.$this->_privateMessage.' '.$texto_trampas_privado);
        } else if ($this->_result == 'critic') {
            $this->_resultMessage = ':'.$skill->keyword.': Ha hecho un crítico ejecutando la habilidad '.$skill->name.$finalName.'. '.$this->_publicMessage.' '.$texto_trampas_publico;
            Yii::app()->user->setFlash(Yii::app()->skill->result, '¡Has hecho un crítico al ejecutar '.$skill->name.$finalName.'! '.$this->_privateMessage.' '.$texto_trampas_privado);
        }

		return true;
    }
	
	/************* SKILLS ************/
    /** Crea un modificador de "hidratado"
     * @param $skill Obj de la skill
     * @param $target Obj del target
     * @return bool
     */
    private function hidratar($skill, $target)
	{
	    //Si tengo Desecar, lo que hago es quitármelo de encima en vez de ejecutar hidratar
        $modificador = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->id, ':keyword'=>Yii::app()->params->modifierDesecado)));
        if ($modificador !== null) {
            if (!$modificador->delete())
                throw new CHttpException(400, 'Error al eliminar el modificador ('.Yii::app()->params->modifierDesecado.'). ['.print_r($modificador->getErrors(),true).']');

            $this->_privateMessage = 'Al estar el objetivo previamente '.Yii::app()->params->modifierDesecado.', '.$skill->name.' únicamente ha eliminado esa penalización.';
            return true;
        }

	    //si ya tengo hidratar, lo que hago es actualizar sus datos, ya que solo puede haber uno (busco por keyword porque puede estar creado por diferentes fuentes)
        $modificador = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->id, ':keyword'=>$skill->modifier_keyword)));

        if ($modificador === null)
            $modificador = new Modifier;

	    $modificador->event_id = Yii::app()->event->id;
	    $modificador->caster_id = Yii::app()->currentUser->id;
	    $modificador->target_final = $target->id;
	    $modificador->skill_id = $skill->id;
	    $modificador->keyword = $skill->modifier_keyword;
	    $modificador->duration = $skill->duration;
	    $modificador->duration_type = $skill->duration_type;
	    $modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

	    if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.'). ['.print_r($modificador->getErrors(),true).']');

		return true;
	}

    /** Crea un modificador de "desecado"
     * @param $skill Obj de la skill
     * @param $target Obj del target
     * @return bool
     */
    /*private function desecar($skill, $target)
    {
        //Si tiene Hidratar, lo que hago es quitarlo en vez de ejecutar desecar
        $modificador = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->id, ':keyword'=>Yii::app()->params->modifierHidratado)));
        if ($modificador !== null) {
            if (!$modificador->delete())
                throw new CHttpException(400, 'Error al eliminar el modificador ('.Yii::app()->params->modifierHidratado.'). ['.print_r($modificador->getErrors(),true).']');

            $this->_privateMessage = 'Al estar el objetivo previamente '.Yii::app()->params->modifierHidratado.', '.$skill->name.' únicamente ha eliminado esa bonificación.';
            return true;
        }

        //si ya tengo desecar, lo que hago es actualizar sus datos, ya que solo puede haber uno (busco por keyword porque puede estar creado por diferentes fuentes)
        $modificador = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->id, ':keyword'=>$skill->modifier_keyword)));

        if ($modificador === null)
            $modificador = new Modifier;

        $modificador->event_id = Yii::app()->event->id;
        $modificador->caster_id = Yii::app()->currentUser->id;
        $modificador->target_final = $target->id;
        $modificador->skill_id = $skill->id;
        $modificador->keyword = $skill->modifier_keyword;
        $modificador->duration = $skill->duration;
        $modificador->duration_type = $skill->duration_type;
        $modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

        if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.') ['.print_r($modificador->getErrors(),true).'].');

        return true;
    }*/

    /** Crea un modificador de "disimulando"
     * @param $skill Obj de la skill
     * @return bool
     */
	private function disimular($skill)
	{
	    $user = Yii::app()->currentUser->model;
		//Si ya tengo disimular lo que haré será sumar 1 uso
		$modificador = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$user->id, ':keyword'=>$skill->modifier_keyword)));

        if ($modificador == null) {
            $modificador = new Modifier;
			$modificador->duration = $skill->duration;
		} else
			$modificador->duration += $skill->duration;

        $modificador->event_id = Yii::app()->event->id;
		$modificador->caster_id = Yii::app()->currentUser->id;
	    $modificador->target_final = $user->id;
	    $modificador->skill_id = $skill->id;
	    $modificador->value = $skill->extra_param;
	    $modificador->keyword = $skill->modifier_keyword;	    
	    $modificador->duration_type = $skill->duration_type;
		$modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

	    if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.'). ['.print_r($modificador->getErrors(),true).']');

		return true;
	}

    /** Caza gungubos y me pone como Cazador si estaba como Criador
     * @return bool
     */
	/*private function cazarGungubos($skill)
	{
		$user = Yii::app()->currentUser->model; //cojo el usuario actual
		$proportion = 0.5; //Precisión a la hora de cazar
		
		//Miro a ver si tengo modificador de Otear		
        $modificador = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$user->id, ':keyword'=>Yii::app()->params->modifierOteando)));

		if ($modificador!==null) {
			$proportion += intval($modificador->value)/100;
			$proportion = min($proportion, 1);

			//Elimino el modificador
			if (!$modificador->delete())
                throw new CHttpException(400, 'Error al eliminar el modificador ('.Yii::app()->params->modifierOteando.'). ['.print_r($modificador->getErrors(),true).']');
		}
		
		
		$amount = $this->randomWithRangeProportion(intval($skill->extra_param), $proportion);
	
		//Cambio al usuario a Cazador si era criador
		if ($user->status == Yii::app()->params->statusInactivo) {
			$user->status = Yii::app()->params->statusCazador;
		
			if (!$user->save())
				throw new CHttpException(400, 'Error al guardar el estado del usuario ('.$user->id.') a Cazador. ['.print_r($user->getErrors(),true).']');
		}

        $event = Yii::app()->event->model; //Cojo el evento (desayuno) actual

        //Si hay menos gungubos libres de los que iba a cazar, cazo los que quedan
        if($amount > $event->gungubos_population) $amount = $event->gungubos_population;

		if($user->side == 'kafhe') {
			$event->gungubos_kafhe += $amount;
		} elseif ($user->side == 'achikhoria') {
			$event->gungubos_achikhoria += $amount;
		}

        //La población de gungubos merma en la cantidad de gungubos cazados
        $event->gungubos_population -= $amount;
		
		if (!$event->save())
            throw new CHttpException(400, 'Error al sumar gungubos al bando '.$user->side.' del evento ('.$event->id.'). ['.print_r($event->getErrors(),true).']');

        $this->_publicMessage = 'Ha logrado hacerse con '.$amount.' gungubos.';
        $this->_privateMessage = 'Has logrado hacerte con '.$amount.' gungubos.';

		return true;
	}*/

    /** Libera gungubos del bando oponente
     * @return bool
     */
    /*private function liberarGungubos($skill)
    {
        $user = Yii::app()->currentUser->model; //cojo el usuario actual
        $amount = $this->randomWithRangeProportion(intval($skill->extra_param),0.5);
        $protected = false;

        //Cambio al usuario a Cazador si era criador
        if ($user->status == Yii::app()->params->statusInactivo) {
            $user->status = Yii::app()->params->statusCazador;

            if (!$user->save())
                throw new CHttpException(400, 'Error al guardar el estado del usuario ('.$user->id.') a Cazador. ['.print_r($user->getErrors(),true).']');
        }

        $event = Yii::app()->event->model; //Cojo el evento (desayuno) actual
        if($user->side == 'kafhe') {
            //No se pueden liberar más de los que existen
            if($event->gungubos_achikhoria < $amount) $amount = $event->gungubos_achikhoria;

            $targetSide = 'achikhoria';
            $modifier = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierProtegiendo,Yii::app()->modifier->getSideModifiers($targetSide));
            if($modifier!=null){
                $protected = true;
                $finalAmount = $amount - $modifier->value;
                if($finalAmount <0) $finalAmount = 0;
                $modifier->value -= $amount-$finalAmount;
                if($modifier->value <= 0) {
                    if (!$modifier->delete())
                        throw new CHttpException(400, 'Error al eliminar el modificador de protección de gungubos del bando '.$targetSide.' del evento '.$event->id.' ['.print_r($modifier->getErrors(),true).']');
                } else if (!$modifier->save())
                    throw new CHttpException(400, 'Error al actualizar la protección del bando '.$targetSide.' del evento ('.$event->id.'). ['.print_r($modifier->getErrors(),true).']');
            }else $finalAmount = $amount;

            $event->gungubos_achikhoria -= $finalAmount;
        } elseif ($user->side == 'achikhoria') {
            if($event->gungubos_kafhe < $amount) $amount = $event->gungubos_kafhe;

            $targetSide = 'kafhe';
            $modifier = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierProtegiendo,Yii::app()->modifier->getSideModifiers($targetSide));
            if($modifier!=null){
                $protected = true;
                $finalAmount = $amount - $modifier->value;
                if($finalAmount <0) $finalAmount = 0;
                $modifier->value -= $amount-$finalAmount;
                if($modifier->value <= 0) {
                    if (!$modifier->delete())
                        throw new CHttpException(400, 'Error al eliminar el modificador de protección de gungubos del bando '.$targetSide.' del evento '.$event->id.' ['.print_r($modifier->getErrors(),true).']');
                } else if (!$modifier->save())
                    throw new CHttpException(400, 'Error al actualizar la protección del bando '.$targetSide.' del evento ('.$event->id.'). ['.print_r($modifier->getErrors(),true).']');
            }else $finalAmount = $amount;

            $event->gungubos_kafhe -= $finalAmount;
        }

        $event->gungubos_population += $finalAmount;

        if (!$event->save())
            throw new CHttpException(400, 'Error al restar gungubos desde el bando '.$user->side.' del evento ('.$event->id.'). ['.print_r($event->getErrors(),true).']');

        if(!$protected) {
            $this->_publicMessage = 'Ha logrado liberar a '.$finalAmount.' gungubos.';
            $this->_privateMessage = 'Has logrado liberar a '.$finalAmount.' gungubos.';
        }
        if($protected && $finalAmount == 0) {
            $this->_publicMessage = 'Los gungubos estaban protegidos y no ha podido liberarlos.';
            $this->_privateMessage = 'Los gungubos estaban protegidos y no has podido liberarlos.';
        }
        if($protected && $finalAmount > 0) {
            $this->_publicMessage = 'Ha logrado liberar a '.$finalAmount.' gungubos rompiendo la protección.';
            $this->_privateMessage = 'Has logrado liberar a '.$finalAmount.' gungubos rompiendo la protección.';
        }

        return true;
    }*/

    /** Le quita al bando oponente una cantidad de gungubos para darsela a tu bando
     * @return bool
     */
    /*private function atraerGungubos($skill)
    {
        $user = Yii::app()->currentUser->model; //cojo el usuario actual
        $amount = $this->randomWithRangeProportion(intval($skill->extra_param),0.5);
        $protected = false;

        //Cambio al usuario a Cazador si era criador
		if ($user->status == Yii::app()->params->statusInactivo) {
            $user->status = Yii::app()->params->statusCazador;

            if (!$user->save())
                throw new CHttpException(400, 'Error al guardar el estado del usuario ('.$user->id.') a Cazador. ['.print_r($user->getErrors(),true).']');
        }

        $event = Yii::app()->event->model; //Cojo el evento (desayuno) actual
        if($user->side == 'kafhe') {
            //No se pueden atraer más de los que existen
            if($event->gungubos_achikhoria < $amount) $amount = $event->gungubos_achikhoria;

            $targetSide = 'achikhoria';
            $modifier = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierProtegiendo,Yii::app()->modifier->getSideModifiers($targetSide));
            if($modifier!=null){
                $protected = true;
                $finalAmount = $amount - $modifier->value;
                if($finalAmount <0) $finalAmount = 0;
                $modifier->value -= $amount-$finalAmount;
                if($modifier->value <= 0) {
                    if (!$modifier->delete())
                        throw new CHttpException(400, 'Error al eliminar el modificador de protección de gungubos del bando '.$targetSide.' del evento '.$event->id.' ['.print_r($modifier->getErrors(),true).']');
                } else if (!$modifier->save())
                    throw new CHttpException(400, 'Error al actualizar la protección del bando '.$targetSide.' del evento ('.$event->id.'). ['.print_r($modifier->getErrors(),true).']');
            }else $finalAmount = $amount;

            $event->gungubos_achikhoria -= $finalAmount;
            $event->gungubos_kafhe += $finalAmount;
        } elseif ($user->side == 'achikhoria') {

            if($event->gungubos_kafhe < $amount) $amount = $event->gungubos_kafhe;

            $targetSide = 'kafhe';
            $modifier = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierProtegiendo,Yii::app()->modifier->getSideModifiers($targetSide));
            if($modifier!=null){
                $protected = true;
                $finalAmount = $amount - $modifier->value;
                if($finalAmount <0) $finalAmount = 0;
                $modifier->value -= $amount-$finalAmount;
                if($modifier->value <= 0) {
                    if (!$modifier->delete())
                        throw new CHttpException(400, 'Error al eliminar el modificador de protección de gungubos del bando '.$targetSide.' del evento '.$event->id.' ['.print_r($modifier->getErrors(),true).']');
                } else if (!$modifier->save())
                    throw new CHttpException(400, 'Error al actualizar la protección del bando '.$targetSide.' del evento ('.$event->id.'). ['.print_r($modifier->getErrors(),true).']');
            }else $finalAmount = $amount;

            $event->gungubos_kafhe -= $finalAmount;
            $event->gungubos_achikhoria += $finalAmount;
        }

        if (!$event->save())
            throw new CHttpException(400, 'Error al restar gungubos desde el bando '.$user->side.' del evento ('.$event->id.'). ['.print_r($event->getErrors(),true).']');

        if(!$protected) {
            $this->_publicMessage = 'Ha logrado atraer a su bando a '.$finalAmount.' gungubos.';
            $this->_privateMessage = 'Has logrado atraer a tu bando a '.$finalAmount.' gungubos.';
        }
        if($protected && $finalAmount == 0) {
            $this->_publicMessage = 'Los gungubos estaban protegidos y no ha podido atraerlos.';
            $this->_privateMessage = 'Los gungubos estaban protegidos y no has podido atraerlos.';
        }
        if($protected && $finalAmount > 0) {
            $this->_publicMessage = 'Ha logrado atraer a su bando a '.$finalAmount.' gungubos rompiendo la protección.';
            $this->_privateMessage = 'Has logrado atraer a tu bando a '.$finalAmount.' gungubos rompiendo la protección.';
        }

        return true;
    }*/

    /** Protegeré una cantidad de gungubos de ser liberados o robados
     * @return bool
     */
    /*private function protegerGungubos($skill, $target)
    {
        $user = Yii::app()->currentUser->model; //cojo el usuario actual
        //Cambio al usuario a Cazador si era criador
        if ($user->status == Yii::app()->params->statusInactivo) {
            $user->status = Yii::app()->params->statusCazador;

            if (!$user->save())
                throw new CHttpException(400, 'Error al guardar el estado del usuario ('.$user->id.') a Cazador. ['.print_r($user->getErrors(),true).']');
        }
		
		//Saco el máximo de gungubos que se pueden proteger en el bando del usuario actual
		if (Yii::app()->currentUser->side=='kafhe')
			$max_proteger = Yii::app()->event->gungubosKafhe;
		elseif (Yii::app()->currentUser->side=='achikhoria')
			$max_proteger = Yii::app()->event->gungubosAchikhoria;

        //Si ya tengo proteger lo que haré será sumar al valor los gungubos nuevos que puedo proteger
        $modificador = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->side, ':keyword'=>$skill->modifier_keyword)));

        if ($modificador == null) {
            $modificador = new Modifier;
            $modificador->duration = $skill->duration;
            $modificador->value = 0;
        } else {
			//Como ya existe, miro cuántos gúngubos hay protegidos y lo resto al máximo posible a proteger
			$max_proteger = max( ($max_proteger - $modificador->value) , 0); //Que no sea menor de cero
		}

        $amount = $this->randomWithRangeProportion(intval($skill->extra_param), 0.75);
		$amount = min($max_proteger, $amount); //Protejo como mucho los que tengo desprotegidos
		
		if ($amount>0) {
			$modificador->value += $amount;
			$modificador->event_id = Yii::app()->event->id;
			$modificador->caster_id = Yii::app()->currentUser->id;
			$modificador->target_final = $target->side;
			$modificador->skill_id = $skill->id;
			$modificador->keyword = $skill->modifier_keyword;
			$modificador->duration_type = $skill->duration_type;
			$modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

			if (!$modificador->save())
				throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.'). ['.print_r($modificador->getErrors(),true).']');
			
			$this->_privateMessage = 'Has logrado proteger a '.$amount.' gungubos.';
		} else
			$this->_privateMessage = 'No había gungubos a los que proteger.';      

        return true;
    }*/
	
    /** Relanza el evento
     * @return bool
     */
	public function escaquearse()
	{
		//Lanzo de nuevo el evento
		$event = Yii::app()->event->model;
		if ($event === null)
			throw new CHttpException(400, 'Error al cargar el evento Escaqueandose.');
		
		//Elijo al llamador
		$battleResult = Yii::app()->event->selectCaller();
		$event->caller_id = $battleResult['userId'];
		$event->caller_side = $battleResult['side'];
		$event->relauncher_id = Yii::app()->currentUser->id;
		
		//Guardo el evento
		if (!$event->save())
			throw new CHttpException(400, 'Error al guardar el estado del evento '.$event->id.' a '.$event->status.'. ['.print_r($event->getErrors(),true).']');

		//Aviso al llamador
		$caller = User::model()->findByPk($event->caller_id);
		$sent = Yii::app()->mail->sendEmail(array(
		    'to'=>$caller->email,
		    'subject'=>'¡A llamar!',
		    'body'=>'El Gran Omelettus dictamina que te toca llamar.'
		    ));
		if ($sent !== true)
		    Yii::log($sent, 'error', 'Email escaqueo');
		
		return true;
	}
	
    /** Libera gungubos de un bando aleatorio
     * @return bool
     */
	/*private function rescatarGungubos($skill)
	{
		//Elijo un bando aleatorio
		$rand = mt_rand(0,1);
		if ($rand==0) $bando = 'kafhe';
		else $bando = 'achikhoria';

        $amount = $this->randomWithRangeProportion(intval($skill->extra_param),0.5);
        $protected = false;

        $event = Yii::app()->event->model; //Cojo el evento (desayuno) actual
        if($bando == 'kafhe') {
            //No se pueden liberar más de los que existen
            if($event->gungubos_kafhe < $amount) $amount = $event->gungubos_kafhe;

            $modifier = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierProtegiendo,Yii::app()->modifier->getSideModifiers($bando));
            if($modifier!=null){
                $protected = true;
                $finalAmount = $amount - $modifier->value;
                if($finalAmount <0) $finalAmount = 0;
                $modifier->value -= $amount-$finalAmount;
                if($modifier->value <= 0) {
                    if (!$modifier->delete())
                        throw new CHttpException(400, 'Error al eliminar el modificador de protección de gungubos del bando '.$bando.' del evento '.$event->id.' ['.print_r($modifier->getErrors(),true).']');
                } else if (!$modifier->save())
                    throw new CHttpException(400, 'Error al actualizar la protección del bando '.$bando.' del evento ('.$event->id.'). ['.print_r($modifier->getErrors(),true).']');
            }else $finalAmount = $amount;

            $event->gungubos_kafhe -= $finalAmount;
            $event->gungubos_population += $finalAmount;

        } elseif ($bando == 'achikhoria') {

            if($event->gungubos_achikhoria < $amount) $amount = $event->gungubos_achikhoria;

            $modifier = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierProtegiendo,Yii::app()->modifier->getSideModifiers($bando));
            if($modifier!=null){
                $protected = true;
                $finalAmount = $amount - $modifier->value;
                if($finalAmount <0) $finalAmount = 0;
                $modifier->value -= $amount-$finalAmount;
                if($modifier->value <= 0) {
                    if (!$modifier->delete())
                        throw new CHttpException(400, 'Error al eliminar el modificador de protección de gungubos del bando '.$bando.' del evento '.$event->id.' ['.print_r($modifier->getErrors(),true).']');
                } else if (!$modifier->save())
                    throw new CHttpException(400, 'Error al actualizar la protección del bando '.$bando.' del evento ('.$event->id.'). ['.print_r($modifier->getErrors(),true).']');
            }else $finalAmount = $amount;

            $event->gungubos_achikhoria -= $finalAmount;
            $event->gungubos_population += $finalAmount;
        }

        if (!$event->save())
            throw new CHttpException(400, 'Error al liberar gungubos del bando '.$bando.' del evento ('.$event->id.'). ['.print_r($event->getErrors(),true).']');

        if(!$protected) $this->_publicMessage = 'Ha logrado liberar a '.$finalAmount.' gungubos del bando de '.ucfirst($bando).'.';
        if($protected && $finalAmount == 0) $this->_publicMessage = 'Los gungubos estaban protegidos y no ha podido atraerlos.';
        if($protected && $finalAmount > 0) $this->_publicMessage = 'Ha logrado liberar a '.$finalAmount.' gungubos del bando de '.ucfirst($bando).', rompiendo la protección.';
			
		return true;
	}*/


    /** Crea un modificador de "trampa", sea del tipo que sea
     * @param $skill Obj de la skill
     * @return bool
     */
    private function trampa($skill)
    {
        //si ya tengo trampas puestas, lo que hago es sumar 1 a sus usos
        $modificador = Modifier::model()->find(array('condition'=>'keyword=:keyword', 'params'=>array(':keyword'=>$skill->modifier_keyword)));

        if ($modificador === null) {
            $modificador = new Modifier;
            $modificador->duration = $skill->duration;
        } else
            $modificador->duration++;

        $modificador->duration_type = $skill->duration_type;
        $modificador->event_id = Yii::app()->event->id;
        $modificador->caster_id = Yii::app()->currentUser->id;
        $modificador->target_final = 'global'; //afecta a ambos bandos
        $modificador->skill_id = $skill->id;
        $modificador->keyword = $skill->modifier_keyword;
        $modificador->hidden = $skill->modifier_hidden;
        $modificador->value = $skill->extra_param;
        $modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

        if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.'). ['.print_r($modificador->getErrors(),true).']');

        return true;
    }

    private function senuelo($skill)
    {
        $user = Yii::app()->currentUser->model;
        //Si ya he tirado un señuelo cambio de objetivo
        $modificador = Modifier::model()->find(array('condition'=>'keyword=:keyword', 'params'=>array(':keyword'=>$skill->modifier_keyword)));

        if ($modificador == null)
            $modificador = new Modifier;

        //Calculo el objetivo final, que será un jugador aleatorio
        $objetivo = Yii::app()->usertools->randomUser($user->group_id, null, array($user->id), true); //Aleatorio menos yo y activos
        if ($objetivo===null)
            return false; //Si no hay posible objetivo

        $modificador->event_id = Yii::app()->event->id;
        $modificador->caster_id = Yii::app()->currentUser->id;
        $modificador->target_final = $objetivo->id;
        $modificador->skill_id = $skill->id;
        $modificador->keyword = $skill->modifier_keyword;
        $modificador->duration = $skill->duration;
        $modificador->duration_type = $skill->duration_type;
        $modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

        if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.'). ['.print_r($modificador->getErrors(),true).']');

        return true;
    }

    private function sacrificar($skill, $finalTarget)
    {
        //Doy puntos de tueste al usuario
        $finalTarget->ptos_tueste = min($finalTarget->ptos_tueste+intval($skill->extra_param), Yii::app()->currentUser->maxTueste);

        //Le quito fama por los sacrificios
        $finalTarget->fame = max(0, $finalTarget->fame-$skill->cost_gungubos);

        if (!$finalTarget->save())
            throw new CHttpException(400, 'Error al guardar el usuario ('.$finalTarget->username.') al darle el tueste correspondiente al Sacrificio. ['.print_r($finalTarget->getErrors(),true).']');

        return true;
    }

    private function vampirismo($skill, $finalTarget)
    {
        //Elijo un objetivo que tenga tueste y esté activo
        $posiblesUsuarios = Yii::app()->usertools->getUsers();
        $usuarios_full = $usuarios_partial = $u_partial_tueste = array();

        foreach ($posiblesUsuarios as $usuario) {
            if ($usuario->id==Yii::app()->currentUser->id || $usuario->status==Yii::app()->params->statusInactivo || $usuario->status==Yii::app()->params->statusIluminado || $usuario->status==Yii::app()->params->statusLibertador)
                continue; //Si está inactivo o es el iluminado o soy yo paso de él

            if ($usuario->ptos_tueste >= intval($skill->extra_param)) {
                $usuarios_full[] = $usuario; //Si tiene tueste suficiente, entra en el bombo de los guays
            }elseif ($usuario->ptos_tueste>0) {
                $usuarios_partial[$usuario->username] = $usuario; //Si tiene tueste pero no el suficiente, al bombo de los bueeeenoooo
                $u_partial_tueste [$usuario->username] = $usuario->ptos_tueste;
            }
        }

        if (!empty($usuarios_full)) {
            //Elijo uno al azar
            $victima = array_rand($usuarios_full);
            $victima = $usuarios_full[$victima];
        } elseif(!empty($usuarios_partial)) {
            arsort($u_partial_tueste); //Ordeno los parciales por su tueste restante
            $keys = array_keys($u_partial_tueste); //Cojo las keys, me interesa la primera
            $victima = $usuarios_partial[$keys[0]];
        } else {
            $victima = null;
            $this->_privateMessage = 'No has podido extraer tueste a ninguna víctima.';
        }

        if ($victima!==null) {
            //Le quito el tueste a la víctima
            $tuesteExtraido = min($victima->ptos_tueste, intval($skill->extra_param)); //Cojo el tueste que le puedo quitar
            $victima->ptos_tueste -= $tuesteExtraido;

            //Salvo a la victima
            if (!$victima->save())
                throw new CHttpException(400, 'Error al guardar el usuario ('.$victima->username.'), victima de un Vampirismo. ['.print_r($victima->getErrors(),true).']');

            //Doy puntos de tueste al usuario
            $finalTarget->ptos_tueste = min($finalTarget->ptos_tueste+$tuesteExtraido, Yii::app()->currentUser->maxTueste);

            $this->_privateMessage = 'Has extraído '.$tuesteExtraido.' puntos de tueste a '.Yii::app()->usertools->getAlias($victima->id).'.';
        }

        //Le quito fama por los sacrificios
        $finalTarget->fame = max(0, $finalTarget->fame-$skill->cost_gungubos);

        if (!$finalTarget->save())
            throw new CHttpException(400, 'Error al guardar el usuario ('.$finalTarget->username.') al darle el tueste correspondiente al Vampirismo. ['.print_r($finalTarget->getErrors(),true).']');

        return true;
    }

    /** Crea un modificador de "oteando"
     * @param $skill Obj de la skill
     * @return bool
     */
    /*private function otear($skill, $target)
    {
        //si ya tengo oteando, lo que hago es renovar su tiempo de ejecución y punto        
		$modificador = Modifier::model()->find(array('condition'=>'target_final=:target AND keyword=:keyword', 'params'=>array(':target'=>$target->id, ':keyword'=>$skill->modifier_keyword)));

        if ($modificador === null) {
            $modificador = new Modifier;
            $modificador->duration = $skill->duration;
        }

        $modificador->duration_type = $skill->duration_type;
        $modificador->event_id = Yii::app()->event->id;
        $modificador->caster_id = Yii::app()->currentUser->id;
        $modificador->target_final = Yii::app()->currentUser->id; //Siempre va a ser el usuario actual
        $modificador->skill_id = $skill->id;
        $modificador->keyword = $skill->modifier_keyword;
        $modificador->hidden = $skill->modifier_hidden;
		$modificador->value = $skill->extra_param;
        $modificador->timestamp = date('Y-m-d H:i:s'); //he de ponerlo para cuando se actualiza

        if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.'). ['.print_r($modificador->getErrors(),true).']');

        //Oteo para mostrar el resultado
        $oteados = Yii::app()->gungubos->getGungubosOteados(Yii::app()->event->model);

        $this->_privateMessage = $oteados['texto'];

        return true;
    }*/

    private function otearKafhe($skill)
    {
        //Saco los Gumbudos Asaltantes de Achikhorias en este evento
        $event = Yii::app()->event->model; //Cojo el evento (desayuno) actual

        $gumbudos = Gumbudo::model()->findAll(array('condition'=>'event_id=:evento AND class=:clase AND side=:bando', 'params'=>array(':evento'=>$event->id, ':clase'=>Yii::app()->params->gumbudoClassAsaltante, ':bando'=>'achikhoria')));
        $armas = array(Yii::app()->params->gumbudoWeapon1=>0, Yii::app()->params->gumbudoWeapon2=>0, Yii::app()->params->gumbudoWeapon3=>0);

        foreach ($gumbudos as $gumbudo) {
            $armas[$gumbudo->weapon]++;
        }

        if ($armas[Yii::app()->params->gumbudoWeapon1]==$armas[Yii::app()->params->gumbudoWeapon2] && $armas[Yii::app()->params->gumbudoWeapon2]==$armas[Yii::app()->params->gumbudoWeapon3]) {
            //Los tres iguales
            if ($armas[Yii::app()->params->gumbudoWeapon1]==0)
                $this->_privateMessage = 'No ves ningún Gumbudo '.Yii::app()->params->gumbudoClassNames['asaltante'].' Renunciante.';
            else
                $this->_privateMessage = 'No predomina ningún arma concreta entre los Gumbudos '.Yii::app()->params->gumbudoClassNamesPlural['asaltante'].' Renunciantes.';
        } elseif ($armas[Yii::app()->params->gumbudoWeapon1]==$armas[Yii::app()->params->gumbudoWeapon2] && $armas[Yii::app()->params->gumbudoWeapon2]>$armas[Yii::app()->params->gumbudoWeapon3]) {
            //Ganan 1=2 sobre 3
            $this->_privateMessage = 'Predominan '.Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon1].' y '.Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon2].' por igual entre los Gumbudos '.Yii::app()->params->gumbudoClassNamesPlural['asaltante'].' Renunciantes.';
        } elseif ($armas[Yii::app()->params->gumbudoWeapon1]==$armas[Yii::app()->params->gumbudoWeapon3] && $armas[Yii::app()->params->gumbudoWeapon3]>$armas[Yii::app()->params->gumbudoWeapon2]) {
            //Ganan 1=3 sobre 2
            $this->_privateMessage = 'Predominan '.Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon1].' y '.Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon3].' por igual entre los Gumbudos '.Yii::app()->params->gumbudoClassNamesPlural['asaltante'].' Renunciantes.';
        } elseif ($armas[Yii::app()->params->gumbudoWeapon2]==$armas[Yii::app()->params->gumbudoWeapon3] && $armas[Yii::app()->params->gumbudoWeapon2]>$armas[Yii::app()->params->gumbudoWeapon1]) {
            //Ganan 2=3 sobre 1
            $this->_privateMessage = 'Predominan '.Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon2].' y '.Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon3].' por igual entre los Gumbudos '.Yii::app()->params->gumbudoClassNamesPlural['asaltante'].' Renunciantes.';
        } else {
            //Los 3 diferentes o el que va solo mayor, por lo que gana 1 solo
            arsort($armas);
            $armas = array_flip($armas);
            $this->_privateMessage = 'Predominan '.Yii::app()->params->gumbudoWeaponNames[array_shift($armas)].' entre los Gumbudos '.Yii::app()->params->gumbudoClassNamesPlural['asaltante'].' Renunciantes.';
        }

        return true;
    }

    private function otearAchikhoria($skill)
    {
        //Saco los Gumbudos Asaltantes de Achikhorias en este evento
        $event = Yii::app()->event->model; //Cojo el evento (desayuno) actual

        $gumbudos = Gumbudo::model()->findAll(array('condition'=>'event_id=:evento AND class=:clase AND side=:bando', 'params'=>array(':evento'=>$event->id, ':clase'=>Yii::app()->params->gumbudoClassGuardian, ':bando'=>'kafhe')));
        $armas = array(Yii::app()->params->gumbudoWeapon1=>0, Yii::app()->params->gumbudoWeapon2=>0, Yii::app()->params->gumbudoWeapon3=>0);

        foreach ($gumbudos as $gumbudo) {
            $armas[$gumbudo->weapon]++;
        }

        if ($armas[Yii::app()->params->gumbudoWeapon1]==$armas[Yii::app()->params->gumbudoWeapon2] && $armas[Yii::app()->params->gumbudoWeapon2]==$armas[Yii::app()->params->gumbudoWeapon3]) {
            //Los tres iguales
            if ($armas[Yii::app()->params->gumbudoWeapon1]==0)
                $this->_privateMessage = 'No ves ningún Gumbudo '.Yii::app()->params->gumbudoClassNames['guardian'].' Kafheíta.';
            else
                $this->_privateMessage = 'No predomina ningún arma concreta entre los Gumbudos '.Yii::app()->params->gumbudoClassNamesPlural['guardian'].' Kafheítas.';
        } elseif ($armas[Yii::app()->params->gumbudoWeapon1]==$armas[Yii::app()->params->gumbudoWeapon2] && $armas[Yii::app()->params->gumbudoWeapon2]>$armas[Yii::app()->params->gumbudoWeapon3]) {
            //Ganan 1=2 sobre 3
            $this->_privateMessage = 'Predominan '.Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon1].' y '.Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon2].' por igual entre los Gumbudos '.Yii::app()->params->gumbudoClassNamesPlural['guardian'].' Kafheítas.';
        } elseif ($armas[Yii::app()->params->gumbudoWeapon1]==$armas[Yii::app()->params->gumbudoWeapon3] && $armas[Yii::app()->params->gumbudoWeapon3]>$armas[Yii::app()->params->gumbudoWeapon2]) {
            //Ganan 1=3 sobre 2
            $this->_privateMessage = 'Predominan '.Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon1].' y '.Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon3].' por igual entre los Gumbudos '.Yii::app()->params->gumbudoClassNamesPlural['guardian'].' Kafheítas.';
        } elseif ($armas[Yii::app()->params->gumbudoWeapon2]==$armas[Yii::app()->params->gumbudoWeapon3] && $armas[Yii::app()->params->gumbudoWeapon2]>$armas[Yii::app()->params->gumbudoWeapon1]) {
            //Ganan 2=3 sobre 1
            $this->_privateMessage = 'Predominan '.Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon2].' y '.Yii::app()->params->gumbudoWeaponNames[Yii::app()->params->gumbudoWeapon3].' por igual entre los Gumbudos '.Yii::app()->params->gumbudoClassNamesPlural['guardian'].' Kafheítas.';
        } else {
            //Los 3 diferentes o el que va solo mayor, por lo que gana 1 solo
            arsort($armas);
            $armas = array_flip($armas);
            $this->_privateMessage = 'Predominan '.Yii::app()->params->gumbudoWeaponNames[array_shift($armas)].' entre los Gumbudos '.Yii::app()->params->gumbudoClassNamesPlural['guardian'].' Kafheítas.';
        }

        return true;
    }


    /*************************************************/
	/************ GUNBUDOS ***************************/
	private function gumbudoAsaltante($skill, $weapon)
    {
        //Creo un Gumbudo
        $gumbudo = new Gumbudo;

        $fecha = new DateTime();
        $fecha->add(DateInterval::createFromDateString($skill->gumbudo_action_duration.' hours')); //Cuando muere

        $gumbudo->class = Yii::app()->params->gumbudoClassAsaltante;
        $gumbudo->owner_id = Yii::app()->currentUser->id;
        $gumbudo->event_id = Yii::app()->event->id;
        $gumbudo->side = Yii::app()->currentUser->side;
        $gumbudo->actions = Yii::app()->config->getParam('gumbudoAsaltanteActions');
        $gumbudo->weapon = $weapon;
        $gumbudo->ripdate = $fecha->format('Y-m-d H:i:s');

        //A ver si es sanguinario o no
        $tirada = mt_rand(1,100);
        $limit = intval(Yii::app()->config->getParam('gumbudoAsaltanteProbabilidadSanguinario'));
        if ($tirada <= $limit) {
            //Es Sanguinario !!!!
            $gumbudo->trait = Yii::app()->params->traitSanguinario;
            $gumbudo->trait_value = 2;
			$this->_privateMessage = '¡El Gumbudo evolucionado es Sanguinario!';
        }

        if (!$gumbudo->save())
            throw new CHttpException(400, 'Error al guardar el Gumbudo ('.$gumbudo->class.'). ['.print_r($gumbudo->getErrors(),true).']');

        //Con los datos de su actividad o action calculo los ataques
        $ataques = array();
        $num_ataques = intval($skill->gumbudo_action_duration / $skill->gumbudo_action_rate);
		$hours = $this->generateAttackHours($num_ataques, $skill->gumbudo_action_rate);
		foreach($hours as $hour) {
			$ataques[] = "('gumbudo', 'gumbudoAsaltanteAttack', '".$gumbudo->id."', '".$hour."')";
        }
        Yii::app()->db->createCommand('INSERT INTO cronpile (`type`, `operation`, `params`, `due_date`) VALUES '.implode(',', $ataques).';')->query();

        return true;
    }

    private function gumbudoGuardian($skill, $weapon)
    {
        //Creo un Gumbudo
        $gumbudo = new Gumbudo;

        $fecha = new DateTime();
        $fecha->add(DateInterval::createFromDateString($skill->gumbudo_action_duration.' hours')); //Cuando muere

        $gumbudo->class = Yii::app()->params->gumbudoClassGuardian;
        $gumbudo->owner_id = Yii::app()->currentUser->id;
        $gumbudo->event_id = Yii::app()->event->id;
        $gumbudo->side = Yii::app()->currentUser->side;
        $gumbudo->actions = Yii::app()->config->getParam('gumbudoGuardianActions');
        $gumbudo->weapon = $weapon;
        $gumbudo->ripdate = $fecha->format('Y-m-d H:i:s');

        //A ver si es acorazado o no
        $tirada = mt_rand(1,100);
        $limit = intval(Yii::app()->config->getParam('gumbudoGuardianProbabilidadAcorazado'));
        if ($tirada <= $limit) {
            //Es Acorazado !!!!
            $gumbudo->actions += 1;
            $gumbudo->trait = Yii::app()->params->traitAcorazado;
            $gumbudo->trait_value = 1;
			$this->_privateMessage = '¡El Gumbudo evolucionado es Acorazado!';
        }

        if (!$gumbudo->save())
            throw new CHttpException(400, 'Error al guardar el Gumbudo ('.$gumbudo->class.'). ['.print_r($gumbudo->getErrors(),true).']');

        return true;
    }

    private function gumbudoCriador($skill)
    {
        //Creo un Gumbudo
        $gumbudo = new Gumbudo;

        $fecha = new DateTime();
        $fecha->add(DateInterval::createFromDateString($skill->gumbudo_action_duration.' hours')); //Cuando muere

        $gumbudo->class = Yii::app()->params->gumbudoClassCriador;
        $gumbudo->owner_id = Yii::app()->currentUser->id;
        $gumbudo->event_id = Yii::app()->event->id;
        $gumbudo->side = Yii::app()->currentUser->side;
        $gumbudo->ripdate = $fecha->format('Y-m-d H:i:s');

        if (!$gumbudo->save())
            throw new CHttpException(400, 'Error al guardar el Gumbudo ('.$gumbudo->class.'). ['.print_r($gumbudo->getErrors(),true).']');

        return true;
    }
	
	private function gumbudoNigromante($skill)
    {
        //Creo un Gumbudo
        $gumbudo = new Gumbudo;

        $fecha = new DateTime();
        $fecha->add(DateInterval::createFromDateString($skill->gumbudo_action_duration.' hours')); //Cuando muere

        $gumbudo->class = Yii::app()->params->gumbudoClassNigromante;
        $gumbudo->owner_id = Yii::app()->currentUser->id;
        $gumbudo->event_id = Yii::app()->event->id;
        $gumbudo->side = Yii::app()->currentUser->side;
        $gumbudo->ripdate = $fecha->format('Y-m-d H:i:s');

        if (!$gumbudo->save())
            throw new CHttpException(400, 'Error al guardar el Gumbudo ('.$gumbudo->class.'). ['.print_r($gumbudo->getErrors(),true).']');
			
		//Con los datos de su actividad o action calculo los ataques
        $ataques = array();
        $num_ataques = intval($skill->gumbudo_action_duration / $skill->gumbudo_action_rate);
		$hours = $this->generateAttackHours($num_ataques, $skill->gumbudo_action_rate);
		foreach($hours as $hour) {
			$ataques[] = "('gumbudo', 'gumbudoNigromanteAttack', '".$gumbudo->id."', '".$hour."')";
        }
        Yii::app()->db->createCommand('INSERT INTO cronpile (`type`, `operation`, `params`, `due_date`) VALUES '.implode(',', $ataques).';')->query();

        return true;
    }

    private function gumbudoPestilente($skill)
    {
        //Creo un Gumbudo
        $gumbudo = new Gumbudo;

        $fecha = new DateTime();
        $fecha->add(DateInterval::createFromDateString($skill->gumbudo_action_duration.' hours')); //Cuando muere

        $gumbudo->class = Yii::app()->params->gumbudoClassPestilente;
        $gumbudo->owner_id = Yii::app()->currentUser->id;
        $gumbudo->event_id = Yii::app()->event->id;
        $gumbudo->side = Yii::app()->currentUser->side;
        $gumbudo->weapon = $weapon;
        $gumbudo->ripdate = $fecha->format('Y-m-d H:i:s');

        //A ver si es fetido
        $tirada = mt_rand(1,100);
        $limit = intval(Yii::app()->config->getParam('gumbudoPestilenteProbabilidadFetido'));
        if ($tirada <= $limit) {
            //Es Fétido !!!!
            $gumbudo->trait = Yii::app()->params->traitFetido;
            //$gumbudo->trait_value = 2;
            $this->_privateMessage = '¡El Gumbudo evolucionado es Fétido!';
        }

        if (!$gumbudo->save())
            throw new CHttpException(400, 'Error al guardar el Gumbudo ('.$gumbudo->class.'). ['.print_r($gumbudo->getErrors(),true).']');

        //Con los datos de su actividad o action calculo los ataques
        $ataques = array();
        $num_ataques = intval($skill->gumbudo_action_duration / $skill->gumbudo_action_rate);
        $hours = $this->generateAttackHours($num_ataques, $skill->gumbudo_action_rate);
        foreach($hours as $hour) {
            $ataques[] = "('gumbudo', 'gumbudoPestilenteAttack', '".$gumbudo->id."', '".$hour."')";
        }
        Yii::app()->db->createCommand('INSERT INTO cronpile (`type`, `operation`, `params`, `due_date`) VALUES '.implode(',', $ataques).';')->query();

        return true;
    }

	private function gumbudoArtificiero($skill)
	{
		//Creo un Gumbudo
        $gumbudo = new Gumbudo;

        $fecha = new DateTime();
        $fecha->add(DateInterval::createFromDateString($skill->gumbudo_action_duration.' hours')); //Cuando muere

        $gumbudo->class = Yii::app()->params->gumbudoClassArtificiero;
        $gumbudo->owner_id = Yii::app()->currentUser->id;
        $gumbudo->event_id = Yii::app()->event->id;
        $gumbudo->side = Yii::app()->currentUser->side;
        $gumbudo->ripdate = $fecha->format('Y-m-d H:i:s');

        if (!$gumbudo->save())
            throw new CHttpException(400, 'Error al guardar el Gumbudo ('.$gumbudo->class.'). ['.print_r($gumbudo->getErrors(),true).']');
			
		//Con los datos de su actividad o action calculo los ataques
        $ataques = array();
        $num_ataques = intval($skill->gumbudo_action_duration / $skill->gumbudo_action_rate);
		$hours = $this->generateAttackHours($num_ataques, $skill->gumbudo_action_rate);
		foreach($hours as $hour) {
        	$ataques[] = "('gumbudo', 'gumbudoArtificieroAttack', '".$gumbudo->id."', '".$hour."')";
        }
        Yii::app()->db->createCommand('INSERT INTO cronpile (`type`, `operation`, `params`, `due_date`) VALUES '.implode(',', $ataques).';')->query();

        return true;
	}

    private function gumbudoHippie($skill)
    {
        //Creo un Gumbudo
        $gumbudo = new Gumbudo;

        $fecha = new DateTime();
        $fecha->add(DateInterval::createFromDateString($skill->gumbudo_action_duration.' hours')); //Cuando muere

        $gumbudo->class = Yii::app()->params->gumbudoClassGuardian;
        $gumbudo->owner_id = Yii::app()->currentUser->id;
        $gumbudo->event_id = Yii::app()->event->id;
        $gumbudo->side = Yii::app()->currentUser->side;
        $gumbudo->actions = Yii::app()->config->getParam('gumbudoHippieActions');
        $gumbudo->weapon = $weapon;
        $gumbudo->ripdate = $fecha->format('Y-m-d H:i:s');

        //A ver si es hiperactivo o no
        $tirada = mt_rand(1,100);
        $limit = intval(Yii::app()->config->getParam('gumbudoHippieProbabilidadHiperactivo'));
        if ($tirada <= $limit) {
            //Es Hiperactivo !!!!
            $gumbudo->actions += 1;
            $gumbudo->trait = Yii::app()->params->traitHiperactivo;
            $gumbudo->trait_value = 1;
            $this->_privateMessage = '¡El Gumbudo evolucionado es Hiperactivo!';
        }

        if (!$gumbudo->save())
            throw new CHttpException(400, 'Error al guardar el Gumbudo ('.$gumbudo->class.'). ['.print_r($gumbudo->getErrors(),true).']');

        return true;
    }


	/*************************************************/
	/************** FUNCIONES AUXILIARES *************/
    /** Pago el coste de ejecutar la habilidad. Además marca como activo al jugador.
     * @param $skill Obj de la skill
     * @param $executionResult Texto con el resultado de la ejecución, si fue critic, normal...
     * @param $extra sobrecoste o rebaja de los costes por TRAMPAS, o null. Admite valores absolutos o porcentajes (0, 10%, -15%...)
     */
    private function paySkillCosts($skill, $executionResult, $extra=null)
	{
		$user = Yii::app()->currentUser->model;
	    //No compruebo nada porque se ha comprobado ya antes de llegar a executeSkill

	    //Los extras
	    $extra_absoluto = 0;
	    $extra_porcentaje = 0;
	    if ($extra!==null) {
            if (strpos($extra, '%')) $extra_porcentaje = intval(str_replace('%', '', $extra)) / 100;
            else $extra_absoluto = $extra;
	    }

        //Si ha sido crítico, cuesta menos
        $criticModificator = array('tueste'=>1, 'retueste'=>1, 'tostolares'=>1, 'relanzamiento'=>1);
        if ($executionResult == 'critic') {
            $criticModificator = array('tueste'=>0.5, 'retueste'=>0.75, 'tostolares'=>0.5, 'relanzamiento'=>1);
        }

        //Pago el tueste
        if ($skill->cost_tueste !== null) {
            $costT = $this->calculateCostTueste($skill);
            $user->ptos_tueste = $user->ptos_tueste - round($costT * $criticModificator['tueste']) + $extra_absoluto + round($costT * $extra_porcentaje);
            //Ganancia de retueste
            $retueste = round($costT*Yii::app()->config->getParam('retuestePerSkill')/100);
            $user->ptos_retueste += $retueste;
        }

	    //Pago el restueste
        if ($skill->cost_retueste !== null)
            $user->ptos_retueste = $user->ptos_retueste - round($skill->cost_retueste * $criticModificator['retueste']);

	    //Pago los tostólares
        if ($skill->cost_tostolares !== null)
            $user->tostolares = $user->tostolares - round($skill->cost_tostolares * $criticModificator['tostolares']);

	    //Pago los puntos de relanzamiento
        if ($skill->cost_relanzamiento !== null)
            $user->ptos_relanzamiento = $user->ptos_relanzamiento - round($skill->cost_relanzamiento * $criticModificator['relanzamiento']);

        //Pago los gungubos
        if ($skill->cost_gungubos !== null) {
            //Remuevo los gungubos que indique empezando por los que menos vida tienen
            Gungubo::model()->deleteAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND location=:lugar AND health>0 ORDER BY health DESC LIMIT '.$skill->cost_gungubos, 'params'=>array(':evento'=>Yii::app()->event->id, ':owner'=>$user->id, ':lugar'=>'corral')));
        }
			
		//Pongo al jugador activo
		if ($user->status == Yii::app()->params->statusInactivo) {
			//Miro si había metido el desayuno para saber qué estado ponerle
			$has_enrollment = Enrollment::model()->exists(array('condition'=>'user_id=:user AND event_id=:event', 'params'=>array(':user'=>$user->id, ':event'=>Yii::app()->event->id)));
			
			if ($has_enrollment)
				$user->status = Yii::app()->params->statusAlistado;
			else
				$user->status = Yii::app()->params->statusCazador;
		}
		$user->last_activity = date('Y-m-d H:i:s'); //Actualizo la última vez que ha hecho algo

        //Salvo todo
	    if (!$user->save())
            throw new CHttpException(400, 'Error al actualizar el usuario ('.$user->id.') tras ejecutar una habilidad ('.$skill->id.'). ['.print_r($user->getErrors(),true).']');

	    return true;
	}

    /** Calcula el coste de tueste de una skill teniendo en cuenta todo: sobrecarga, etc.
     * @param $skill Objeto de la skill a calcular su coste
     * @param &$desglose Variable en la que se dejará el coste desglosado
     * @return Devuelte el coste final de la habilidad
     */
    public function calculateCostTueste($skill, &$desglose=null)
    {
        $user = Yii::app()->currentUser->model; //cojo el usuario actual
        $costeFinal = $skill->cost_tueste;
		
		//DISIMULAR
			$extraDisimular = 0;
			
			//Saco el modificador de disimular si lo tengo
			$modifier = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierDisimulando);
			if ($modifier !== false) {
				$porcentajeExtraDisimular = intval($modifier->value);

				$extraDisimular = round( ($costeFinal * $porcentajeExtraDisimular) / 100 );
			}

        //SOBRECARGA
            $porcentajeExtraSobrecarga = Yii::app()->config->getParam('sobrecargaPorcentajeTuesteExtra');
			$extraSobrecarga = 0;

            //Miro el histórico de ejecuciones del jugador
            $historico = Yii::app()->historySkill->model;
            if ($historico!==null) {
                $repeticiones = 0;
                foreach ($historico as $historic) {
                    //Miro si la ejecución era de esta skill y si el resultado fue normal o crítico (pifia no se tiene en cuenta)
                    if ($historic->skill_id == $skill->id  &&  ($historic->result=='normal' || $historic->result=='critic')) {
                        $repeticiones++;
                    }
                }

                if ($repeticiones > 0) {
                    $extraSobrecarga = round( ($costeFinal * ($porcentajeExtraSobrecarga * $repeticiones)) / 100 );
                }
            }
		
		$costeFinal += $extraDisimular + $extraSobrecarga;
		$desglose = array(
			'disimular'=>$extraDisimular,
			'sobrecarga'=>$extraSobrecarga
		);

        return $costeFinal;
    }

    /** Calcula el valor de crítico de la habilidad
     * @param $skill Obj de la skill
     * @return int Valor del crítico
     */
    private function criticValue($skill) {
		$critic = $skill->critic;
		return $critic;
	}

    /** Calcula el valor de pifia de la habilidad
     * @param $skill Obj de la skill
     * @return int Valor de la pifia
     */
	private function failValue($skill) {
		$fail = $skill->fail;
		return $fail;
	}

    /** Devuelve un valor aleatorizado entre el valor indicado y un porcentaje del mismo indicado como parámetro
     * @param $value Valor máximo del rango
     * @param $range Porcentaje del valor máximo que se admite como valor mínimo del rango (0-1)
     * @return int Valor aleatorizado
     */
    public function randomWithRangeProportion($value, $proportion) {
        return mt_rand($proportion*$value, $value);
    }

    /** Calcula el objetivo final de la habilidad
     * @param $skill objeto de la skill ejecutada
     * @param $target objeto del objetivo o NULL si no hay
     * @param $side texto del bando objetivo o NULL si no hay
     * @return object|text Objeto objetivo o el texto del bando
     */
    private function calculateFinalTarget($skill, $target, $side) {
		if ($target===null && $side===null) {
            $finalTarget = Yii::app()->currentUser->model; //Si no hay objetivo, es que el objetivo es uno mismo. 
			$this->_finalTarget = Yii::app()->currentUser->id;
		} elseif ($target===null && $side!==null) {
		    $finalTarget = $side;
            $this->_finalTarget = $side; //texto, kafhe, achikhoria, global
        } elseif ($target!==null) {
			$finalTarget = $target;
			$this->_finalTarget = $finalTarget->id;
		}

		return $finalTarget;
	}

    /** Comprueba si el usuario activo tiene un modificador Trampa afectándole, y reduce el modificador en caso afirmativo
     * @return mix False si no caes en una trampa, o el objeto del modificador de la trampa en que has caído
     */
    private function userCaeTrampa($skill) {
        //Las habilidades de relanzamiento no pifian, ni las de evolucion de gumbudos
        if ($skill->category=='relanzamiento' || $skill->category=='gumbudos') return false;

	    //Saco modificadores de trampas que me afectan
	    $trampas[] = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierTrampaPifia);
        $trampas[] = Yii::app()->modifier->inModifiers(Yii::app()->params->modifierTrampaTueste);
        shuffle($trampas);

        foreach ($trampas as $trampa) {
            if ($trampa === false) continue;

            //Miro a ver si caigo en la trampa
            $tirada = mt_rand(1,100);
            $valor = Yii::app()->config->getParam($trampa->keyword.'Probabilidad');

            if ($tirada<=$valor) {
                //He caído cual primo. Reduzco los usos del modificador trampa
                if (!Yii::app()->modifier->reduceModifierUses($trampa))
                    throw new CHttpException(400, 'Error al eliminar el modificador '.$trampa->name);

                return $trampa;
            }
        }

	    return false;
	}
	
	
	//Calcula y devuelve un array con los ataques
	private function generateAttackHours($number_attacks, $attack_hour_rate, $max_aproximation=10)
	{
		$attack_hours = array();
        
        for($i=1; $i<=$number_attacks; $i++) {
            $fecha = new DateTime();
			
			if ($i != $number_attacks) { //Si no es el último ataque, le meto una variable de tiempo			
				$signo = mt_rand(1,2);
				if ($signo===1) $signo = '+'; else $signo = '-';			
				$aproximation = mt_rand(1, $max_aproximation);

                $fecha->add(DateInterval::createFromDateString(($i*$attack_hour_rate).' hours '.$signo.' '.$aproximation.' minutes')); //Añado X horas y unos minutos
			} else
                $fecha->add(DateInterval::createFromDateString(($i*$attack_hour_rate).' hours -5 minutes')); //Añado X horas
			
			$attack_hours[] = $fecha->format('Y-m-d H:i:s');
		}
		
		return $attack_hours;
	}
	
	
	/******** GETTERS **********/
	public function getCaster() { return $this->_caster; }
	public function getOriginalTarget() { return $this->_originalTarget; }
	public function getFinalTarget() { return $this->_finalTarget; }
	public function getKeyword() { return $this->_keyword; }
	public function getResult() { return $this->_result; }
	public function getResultMessage() { return $this->_resultMessage; }
	public function getError() { return $this->_error; }
	public function getGeneratesNotification() { return $this->_generates_notification; }
	
}