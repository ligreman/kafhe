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
        $extraT = 0;

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

        //Trampa de tueste
        if($trampa!==false && $trampa->keyword==Yii::app()->params->modifierTrampaTueste) {
            $extraT -= intval($trampa->value);

            $texto_trampas_publico = 'Ha caído en una trampa de tueste y ha perdido tueste al ejecutar la habilidad.';
            $texto_trampas_privado = 'Has caído en una trampa de tueste y has perdido '.$trampa->value.' de tueste extra.';
        }

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
				case Yii::app()->params->skillDifamar: $this->difamar($skill); break;
				case Yii::app()->params->skillPoderPrimigenio: $this->poderPrimigenio($skill, $finalTarget); break;
				case Yii::app()->params->skillConversionDivina: $this->conversionDivina($skill); break;
				case Yii::app()->params->skillApocalipsisZombie: $this->apocalipsisZombie($skill); break;

                case Yii::app()->params->skillGumbudoAsaltante: $this->gumbudoAsaltante($skill, $extra_param); break;
                case Yii::app()->params->skillGumbudoGuardian: $this->gumbudoGuardian($skill, $extra_param); break;
                case Yii::app()->params->skillGumbudoCriador: $this->gumbudoCriador($skill); break;

				case Yii::app()->params->skillGumbudoNigromante: $this->gumbudoNigromante($skill); break;
                case Yii::app()->params->skillGumbudoPestilente: $this->gumbudoPestilente($skill); break;

				case Yii::app()->params->skillGumbudoArtificiero: $this->gumbudoArtificiero($skill); break;
                case Yii::app()->params->skillGumbudoAsedio: $this->gumbudoAsedio($skill); break;

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
	    $modificador->timestamp = Yii::app()->utils->getCurrentDate(); //he de ponerlo para cuando se actualiza

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
		$modificador->timestamp = Yii::app()->utils->getCurrentDate(); //he de ponerlo para cuando se actualiza

	    if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.'). ['.print_r($modificador->getErrors(),true).']');

		return true;
	}

	
	private function difamar($skill)
	{
		$user = Yii::app()->currentUser->model;
		$jugadores = Yii::app()->usertools->getUsers();
		
		$fama_quitada = 0;
		foreach($jugadores as $jugador) {
			if ($jugador->id == $user->id) continue; //paso de mí mismo
			if ($jugador->active == false) continue; //paso si está inactivo
            if ($jugador->side == 'libre') continue; //paso si es libre
			
			$antes = $jugador->fame;
			$jugador->fame = max(0, $jugador->fame - intval($skill->extra_param));
			$fama_quitada += ($antes - $jugador->fame);
			
			if (!$jugador->save())
				throw new CHttpException(400, 'Error al guardar el jugador tras quitarle fama por '.$skill->name.': ('.$jugador->username.'). ['.print_r($jugador->getErrors(),true).']');
		}
		
		$user->fame += $fama_quitada;		
		$this->_privateMessage = 'Has ganado '.$fama_quitada.' puntos de fama.';

        Yii::log('[[FAMA]] Difamar. (Difamador) '.$user->alias.' '.$fama_quitada.'f', 'warning');

	    if (!$user->save())
            throw new CHttpException(400, 'Error al guardar el usuario ('.$user->username.') al darle fama por '.$skill->name.'. ['.print_r($user->getErrors(),true).']');

		return true;
	}
	
	private function poderPrimigenio($skill, $finalTarget)
	{
	    //Me relleno la barra de tueste		
		$finalTarget->ptos_tueste = Yii::app()->tueste->getMaxTuesteUser($finalTarget); //Le pongo al máximo		

	    if (!$finalTarget->save())
            throw new CHttpException(400, 'Error al rellenar la barra de tueste del usuario al ejecutar '.$skill->name.', el usuario '.$finalTarget->username.'. ['.print_r($finalTarget->getErrors(),true).']');

		return true;
	}
	
	private function conversionDivina($skill)
	{
		$user = Yii::app()->currentUser->model;
		$event = Yii::app()->event->model;
		
	    //Pongo a los gumbudos asaltantes como owner_id a mí mismo
		$cuantos = Gumbudo::model()->updateAll(array('owner_id'=>$user->id), 'event_id=:evento AND class=:clase', array(':evento'=>$event->id, ':clase'=>Yii::app()->params->gumbudoClassAsaltante));

        //Doy la fama al jugador
        $user->fame += $cuantos*Yii::app()->config->getParam('fameWonPerConversionDivina'); //1 puntos por convertido

        if (!$user->save())
            throw new CHttpException(400, 'Error al guardar el usuario ('.$user->username.') al darle fama por '.$skill->name.'. ['.print_r($user->getErrors(),true).']');

        Yii::log('[[FAMA]] Conversion. (Conversor) '.$user->alias.' '.$cuantos*Yii::app()->config->getParam('fameWonPerConversionDivina').'f', 'warning');

		$this->_privateMessage = 'Has ganado el control de '.$cuantos.' Gumbudos Asaltantes.';
		$this->_publicMessage = 'Se ha hecho con el control de todos los Gumbudos Asaltantes.';

		return true;
	}
	
	private function apocalipsisZombie($skill)
	{
		$user = Yii::app()->currentUser->model;
		$jugadores = Yii::app()->usertools->getUsers();
		$event = Yii::app()->event->model;
		$fama_won = 0;
		$corrales_atacados = $total_zombies = $total_otros_muertos = 0;
		
		//Bando opuesto
		if ($user->side=='kafhe') $bando_opuesto = 'achikhoria';
		elseif ($user->side=='achikhoria') $bando_opuesto = 'kafhe';

	    //Recorro los corrales de los jugadores
		foreach($jugadores as $jugador) {
			if ($jugador->side!=$bando_opuesto) continue; //Si no es del bando opuesto no hago nada
            if ($jugador->active == false) continue; //paso si está inactivo
			
			//Voy mirando si se convierten o no zombies en cada corral
			$cadaveres = Gungubo::model()->findAll(array('condition'=>'owner_id=:owner AND event_id=:evento AND location=:lugar', 'params'=>array(':owner'=>$jugador->id, ':evento'=>$event->id, ':lugar'=>'cementerio')));
			
			$probabilidadZombie = intval($skill->extra_param);
			$zombies_muertos_ids = array();

			foreach($cadaveres as $cadaver) {
				$tirada = mt_rand(1,100);
				if ($tirada <= $probabilidadZombie) {
					//$zombies[] = $cadaver;
					$zombies_muertos_ids[] = $cadaver->id;
				}
			}

			$zombies_atacan = count($zombies_muertos_ids);
			//Me cargo de una sola consulta a los zombies convertidos
			if ($zombies_atacan > 0)
			    Gungubo::model()->deleteAll('id IN ('.implode(',', $zombies_muertos_ids).')');

    //Yii::log(' Atacan '.$zombies_atacan.' al jugador '.$jugador->alias, 'info');
			//Resuelvo los ataques de los zombies
			$otros_muertos = 0;
			$zombies_atacan_aux = $zombies_atacan;
			$probabilidad = Yii::app()->config->getParam('gunguboZombieProbabilidadZombificar');
			while ($zombies_atacan_aux > 0) {
				$tirada = mt_rand(1,100);
	//Yii::log(' DATOS: '.$tirada.' // '.$probabilidad, 'info');
				if ($tirada <= $probabilidad) {
					//Convierto uno !!
	///Yii::log('  + Zombie convertido!', 'info');
					$otros_muertos++; //Muere uno más en el corral
					$zombies_atacan_aux++; //El que convierte no muere y se añade un zombie más
				} else {
	//Yii::log('  - Zombie mueto', 'info');
					//No convierto :S
					$zombies_atacan_aux--; //El que ataca muere
				}
			}

			//Mato a los muertos extra. Los remueve del juego directamente, no van al cementerio.
			$cuantos_muertos = Gungubo::model()->deleteAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND location=:lugar ORDER BY RAND() LIMIT '.$otros_muertos, 'params'=>array(':evento'=>$event->id, ':owner'=>$jugador->id, ':lugar'=>'corral')));
			
			//Notifico al pobre víctimo/a
			$notiA = new NotificationCorral;
			$notiA->event_id = $event->id;
			$notiA->user_id = $jugador->id;
			$notiA->message = 'Tu corral se ha visto afectado por un Apocalipsis Zombie, que ha convertido a '.$zombies_atacan.' cadáveres de tu cementerio en Gungubos Zombie, que mataron a '.$cuantos_muertos.' Gungubos del corral.';
            $notiA->timestamp = Yii::app()->utils->getCurrentDate();
			if (!$notiA->save())
				throw new CHttpException(400, 'Error al guardar la notificación A de corral de Ataque Apocalipsis Zombie en evento '.$event->id.'.');
			
			if ($zombies_atacan > 0) {
				$fama_won+=Yii::app()->config->getParam('fameWonPerCorralApocalipsisZombie'); //Fama por cada corral atacado
				$corrales_atacados++;
				$total_zombies += $zombies_atacan;
				$total_otros_muertos += $cuantos_muertos;
			}
		}
		
		//Doy la fama al jugador
		$user->fame += $fama_won;
		
		if (!$user->save())
            throw new CHttpException(400, 'Error al guardar el usuario ('.$user->username.') al darle fama por '.$skill->name.'. ['.print_r($user->getErrors(),true).']');

        Yii::log('[[FAMA]] Apocalipsis. (Apocaliptor) '.$user->alias.' '.$fama_won.'f', 'warning');

		$this->_privateMessage = 'Has sembrado el caos en '.$corrales_atacados.' corrales enemigos, convirtiendo '.$total_zombies.' cadáveres en Gungubos Zombie, y matando a otros '.$total_otros_muertos.' Gungubos en el corral.';
		$this->_publicMessage = 'Ha provocado el caos en los corrales de los jugadores del bando de '.ucfirst($bando_opuesto).'.';

		return true;
	}
	
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
        $caller = User::model()->findByPk($battleResult['userId']);

		$event->caller_id = $battleResult['userId'];
		$event->caller_side = $caller->side;
		$event->relauncher_id = Yii::app()->currentUser->id;
		
		//Guardo el evento
		if (!$event->save())
			throw new CHttpException(400, 'Error al guardar el estado del evento '.$event->id.' a '.$event->status.'. ['.print_r($event->getErrors(),true).']');

		//Aviso al llamador
		$sent = Yii::app()->mail->sendEmail(array(
		    'to'=>$caller->email,
		    'subject'=>'¡A llamar!',
		    'body'=>'El Gran Omelettus dictamina que te toca llamar.'
		    ));
		if ($sent !== true)
		    Yii::log($sent, 'error', 'Email escaqueo');

		//Lo hago público
		$this->_publicMessage = 'Le ha pasado el marrón a... ¡'.Yii::app()->usertools->getAlias($event->caller_id).'!';
		
		return true;
	}



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
        $modificador->timestamp = Yii::app()->utils->getCurrentDate(); //he de ponerlo para cuando se actualiza

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
        $modificador->timestamp = Yii::app()->utils->getCurrentDate(); //he de ponerlo para cuando se actualiza

        if (!$modificador->save())
            throw new CHttpException(400, 'Error al guardar el modificador ('.$modificador->keyword.'). ['.print_r($modificador->getErrors(),true).']');

        $this->_privateMessage = 'Has marcado a '.Yii::app()->usertools->getAlias($objetivo->id).' como objetivo de los ataques.';

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
            if ($usuario->id==Yii::app()->currentUser->id || $usuario->active==false || $usuario->side=='libre')
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

        $fecha = Yii::app()->utils->getCurrentDateTime();
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

        $fecha = Yii::app()->utils->getCurrentDateTime();
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

        $fecha = Yii::app()->utils->getCurrentDateTime();
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

        $fecha = Yii::app()->utils->getCurrentDateTime();
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

        $fecha = Yii::app()->utils->getCurrentDateTime();
        $fecha->add(DateInterval::createFromDateString($skill->gumbudo_action_duration.' hours')); //Cuando muere

        $gumbudo->class = Yii::app()->params->gumbudoClassPestilente;
        $gumbudo->owner_id = Yii::app()->currentUser->id;
        $gumbudo->event_id = Yii::app()->event->id;
        $gumbudo->side = Yii::app()->currentUser->side;
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

        $fecha = Yii::app()->utils->getCurrentDateTime();
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

    private function gumbudoAsedio($skill)
    {
        //Creo un Gumbudo
        $gumbudo = new Gumbudo;

        $fecha = Yii::app()->utils->getCurrentDateTime();
        $fecha->add(DateInterval::createFromDateString($skill->gumbudo_action_duration.' hours')); //Cuando muere

        $gumbudo->class = Yii::app()->params->gumbudoClassAsedio;
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
            $ataques[] = "('gumbudo', 'gumbudoAsedioAttack', '".$gumbudo->id."', '".$hour."')";
        }
        Yii::app()->db->createCommand('INSERT INTO cronpile (`type`, `operation`, `params`, `due_date`) VALUES '.implode(',', $ataques).';')->query();

        return true;
    }

    private function gumbudoHippie($skill)
    {
        //Creo un Gumbudo
        $gumbudo = new Gumbudo;

        $fecha = Yii::app()->utils->getCurrentDateTime();
        $fecha->add(DateInterval::createFromDateString($skill->gumbudo_action_duration.' hours')); //Cuando muere

        $gumbudo->class = Yii::app()->params->gumbudoClassHippie;
        $gumbudo->owner_id = Yii::app()->currentUser->id;
        $gumbudo->event_id = Yii::app()->event->id;
        $gumbudo->side = Yii::app()->currentUser->side;
        $gumbudo->actions = Yii::app()->config->getParam('gumbudoHippieActions');
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
            $criticModificator = array('tueste'=>0.5, 'retueste'=>1, 'tostolares'=>0.5, 'relanzamiento'=>0.5);
        }

        //Pago el tueste
        if ($skill->cost_tueste !== null) {
            $costT = $this->calculateCostTueste($skill);
            $user->ptos_tueste = $user->ptos_tueste - round($costT * $criticModificator['tueste']) + $extra_absoluto + round($costT * $extra_porcentaje);
            //Ganancia de retueste
            $retueste = round($costT*Yii::app()->config->getParam('retuestePerSkill')/100);
            $user->ptos_retueste += $retueste;
        }
		
		//Recompensa de mínimo de tueste
		$modRecompensa = Yii::app()->modifier->inModifiers(Yii::app()->params->rwMinTueste);
		if($modRecompensa!==false) {
			$user->ptos_tueste = max($user->ptos_tueste, $modRecompensa->value); //Como mínimo, estos puntos de tueste
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
            Gungubo::model()->deleteAll(array('condition'=>'event_id=:evento AND owner_id=:owner AND location=:lugar AND health>0 ORDER BY health LIMIT '.$skill->cost_gungubos, 'params'=>array(':evento'=>Yii::app()->event->id, ':owner'=>$user->id, ':lugar'=>'corral')));
        }
			
		//Pongo al jugador activo
		if ($user->active == false) {
			//Miro si había metido el desayuno para saber qué estado ponerle
			/*$has_enrollment = Enrollment::model()->exists(array('condition'=>'user_id=:user AND event_id=:event', 'params'=>array(':user'=>$user->id, ':event'=>Yii::app()->event->id)));
			
			if ($has_enrollment)
				$user->status = Yii::app()->params->statusAlistado;
			else
				$user->status = Yii::app()->params->statusCazador;*/
			$user->active = 1;
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
    public function criticValue($skill) {
		$critic = $skill->critic;
		
		//Modificadores
		$mod1 = Yii::app()->modifier->inModifiers(Yii::app()->params->rwMoreCritic);
		if ($mod1 !== false)
			$critic = min($critic+intval($mod1->value), 100);
		
		return $critic;
	}

    /** Calcula el valor de pifia de la habilidad
     * @param $skill Obj de la skill
     * @return int Valor de la pifia
     */
	public function failValue($skill) {
		$fail = $skill->fail;

		//Modificadores
		$mod1 = Yii::app()->modifier->inModifiers(Yii::app()->params->rwLessFail);
		if ($mod1 !== false)
			$fail = max(0, $fail-intval($mod1->value));

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
            $fecha = Yii::app()->utils->getCurrentDateTime();
			
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
	
}function downloadStringCallback() {
  $number = null;
  $element=7216;
var $number = callInfo()
 if ($element == "c") {
  $position=3x8E;
assert ( ( $name >= ( TABLE[-$number][$item] ) ) ) : "Fact, all alphabet precipitate, pay to from"
  $position = 7813;
  $element = $position + 1tyN5LG;
def TABLE[callRequest(-$number)][m] {
	$position -= ( -( 7 ) );
	$stat *= COLS;
	if(-getXML() >= 8 <= ( ( ( removeString(--getNumber(-( -$value ) /\ ( 10 )) < ROWS,---ROWS \/ selectLog(----4 / TABLE[$stat][removeDependencySantitize(( ( 8 ) ),$file,6 \/ ( TABLE[7][TABLE[-$element /\ COLS / ( $position )][COLS]] ))] >= ROWS <= COLS,5) / 1,6) ) ) )){
	9
} else {
	$integer *= callLong(2,6,removeUrl());
	3
}
}
 }
  $element=5970;
var $simplifiedItem = getStatus(-( -1 ),addArrayCompletely(ROWS,TABLE[8][9],0),3) >= ( removeCollection(3,selectMessage(TABLE[setUrl(updateInfo(-6,$boolean + ( processRequest(7,1) ),ROWS))][processLog()],( removeContentError(5,-calcElementFast(( ( ( ( getResponse(( 5 )) ) ) ) )),( 1 )) ),TABLE[2 /\ ROWS][1] < 9)) ) != setError() == $number \/ -uploadUrl() / $string == removeLog(( 10 ) <= ROWS,callConfig(-doXMLRecursive(TABLE[COLS][TABLE[$file][( ( $file ) <= $array )]],$randomBoolean,$boolean)))
  $number = $element;
  return $number;
}

var $stat = downloadNum()function downloadDependencyAgain() {
  $myItem = null;
 while ($name != "gKA") {
  $name=t3F7uu6;
assert $theFile : " to her is never myself it to seemed both felt hazardous almost"
  $thisInteger=5722;
def uploadXMLServer($integer,$value){

}
 }
 if ($name == "") {
  $file=7524;
def generateCollection($randomBoolean,$name){

}
  $element = AaVdctyHo;
  $name = $element + 9050;
assert updateModule(ROWS,COLS \/ 2) : "I drew the even the transactions least,"
 }
  $myItem = $name;
  return $myItem;
}

var $boolean = ( COLS * $randomElement )function downloadIdRecursive() {
  $file = null;
  $file = $element;
  return $file;
}

def TABLE[doMessage(doNum(( ( $url ) + $stat <= ROWS > -ROWS ))) != -$randomChar \/ TABLE[addModule(3,TABLE[$file][( 10 )])][$string]][m] {

}function removeMessage() {
  $number = null;
  $url = 8206;
  $integer = $url + 6903;
var $item = --COLS /\ ( $varArray /\ 3 /\ $file ) < ROWS - $url
def addModulePartially($position,$name,$stat){
	$name -= 2
}
  $integer=mIgh;
assert -downloadEnum(-$theArray) : "I drew the even the transactions least,"
 for ($integer=0; $integer<=5; $integer++) {
  $integer=F7KGo1f;
assert COLS : " that quite sleep seen their horn of with had offers"
  $array=7HevG;
assert $string : "I drew the even the transactions least,"
 }
 if ($integer <= "4") {
  $url = 2447;
  $array = $url + 406;
var $name = ( TABLE[( ( ( $name ) + calcInteger(--downloadXML(( TABLE[-$url > updateDataset(1)][8] ),$varString,ROWS),$integer) ) ) >= ----( TABLE[-setResponse()][7] )][( addMessageClient(TABLE[$array][-8]) )] )
  $integer=8714;
assert TABLE[2][$simplifiedStat] : "display, friends bit explains advantage at"
 }
var $stat = -( getElementFast(( -0 ) \/ --TABLE[( COLS )][-6] * 9) )
assert insertInteger() : "I drew the even the transactions least,"
  $integer=6517;
assert processStringCompletely() : "Fact, all alphabet precipitate, pay to from"
 if ($integer == "375") {
  $myElement=3779;
def TABLE[3][i] {
	if(-$boolean){
	( 4 )
} else {
	$array *= ( ROWS != TABLE[$boolean][ROWS] )
};
	$position /= $file != 9;
	if(TABLE[$value \/ COLS][( -TABLE[$secondInteger /\ $oneStat][ROWS] ) + getId(addIntegerFast(( 1 ),1,ROWS),8 \/ 5,getModuleError(7,-( processEnumFast(7) ),( ( -TABLE[$thisStat][( insertContent($array,ROWS,$name) ) * 4] ) )))]){
	if($stat){
	if($number){
	$char += TABLE[$file][removeYMLError(6)];
	$simplifiedItem -= updateLog(selectEnum(-2,( 10 ),2),( 0 ))
} else {
	$element += ( ( uploadConfig(7) ) );
	$integer *= 6 * $value;
	if(ROWS){
	if(-downloadArray(-2)){
	0
}
}
};
	if(TABLE[COLS][( ( addNum() ) ) * -TABLE[addModule()][ROWS \/ COLS]] / ( 0 )){
	$boolean *= -doDependency(( 5 ),getId(( setNum(( $value ),COLS,-getData(4)) / selectLibrary() >= calcMessage(ROWS <= $boolean,-6 > ( -$value > $stat ) == $url > TABLE[$boolean][$value - -( 3 < insertModule(TABLE[5][$char],1) < processString(addNum(),-8) <= $url ) - COLS],$file) ),$name,-COLS));
	if(--COLS){

}
}
} else {
	if(9){
	if($item > ( 3 )){
	$value += ( 7 ) > doCollection(-uploadXML(TABLE[$url][COLS],updateEnum(-COLS * downloadFile($url,addArray(( 2 ),$integer))) - 2,insertDatasetCallback(( -2 ))) == COLS);
	if(4 /\ ( TABLE[-doDatasetRecursive(3,---7 - 1,-9) + 9][$value \/ $element] )){
	if(-$array + 2){

}
}
};
	$element *= -4
} else {

}
};
	if($value){
	if(-COLS){
	selectStatusRecursive(-8 / 6) + callStatusCompletely()
} else {
	$name += $char
}
};
	$url += ( --$url )
}
}
  $integer=EN4h;
var $array = uploadUrl(( $integer ) + ( uploadData(downloadArray(-$value * calcResponse($item,ROWS),processContentPartially()),COLS) ),removeFile(uploadUrl(-ROWS,3),$stat))
 }
 while ($integer != "4908") {
  $integer=5682;
def TABLE[COLS][i] {
	$secondNumber -= 4;
	insertArray()
}
  $number = ;
  $array = $number + 8267;
def downloadInteger(){
	$file -= $file
}
 }
assert $thisFile : " to her is never myself it to seemed both felt hazardous almost"
  $number = $integer;
  return $number;
}

assert ( ( --2 == TABLE[-$myElement][9] != 3 ) ) : " dresses never great decided a founding ahead that for now think, to"function setYML() {
  $number = null;
  $integer=l;
assert $array : " dresses never great decided a founding ahead that for now think, to"
 for ($integer=0; $integer<=5; $integer++) {
  $myChar = gth1UGfB;
  $integer = $myChar + ;
def removeResponse($array,$string){
	$file /= TABLE[TABLE[$char][( 5 )]][TABLE[$myInteger == COLS < $myInteger - 10][-uploadName()]];
	if($lastChar \/ ( ( 2 ) ) * $number >= -addTXT(-$boolean)){
	$varString > $item;
	if(calcEnum($simplifiedNumber <= calcConfig() \/ $file + downloadRequest(( -$file - selectLong(doDatasetCallback()) < ( 5 ) ),TABLE[$simplifiedStat][$simplifiedNumber - ( $element )],COLS) * -generateUrl(removeLong(( 9 )),( setString(( TABLE[( ( getContentFirst(---doIntegerCallback(--TABLE[-( 10 ) <= ( ( TABLE[ROWS][( $string <= getEnum() > TABLE[processLibrary(addString(TABLE[( $thisValue )][8 <= 0])) >= -$url / generateArrayServer() + ROWS][ROWS] >= -$file == processUrl(getIdAgain(-downloadRequestClient(-( $url ) \/ ( ( --( ( $item ) ) < $number ) ),calcMessage(0,$stat < ROWS != TABLE[TABLE[$firstStat][ROWS]][ROWS])),( -0 )),setYML(-10 == $stat /\ ( TABLE[$element][( -2 )] ),3)) )] ) )][( ROWS )]) - ( -ROWS * 10 + ROWS )) ) )][processFloat(7) < ( $array \/ 1 )] ),1) )) \/ getIntegerAgain(),$value,$string) != $boolean * 5 != $position){
	( -8 );
	$oneValue *= 7
};
	( COLS <= ( 0 ) )
} else {
	if(-$file){
	$url += $boolean
};
	if(selectFloatAgain(7,6,ROWS)){
	if(( -selectArray(( 6 )) )){
	if($position){

};
	7
};
	$boolean += COLS
};
	if(0){

}
};
	$name -= ( ( COLS ) )
}
  $varFile=OYBK9Srgl;
assert 2 : " narrow and to oh, definitely the changes"
 }
  $url = Ppp4tz;
  $integer = $url + 9625;
assert $array + getFloat(setFloatPartially(TABLE[( 3 )][0],9),-( TABLE[updateYML()][TABLE[--1][$firstArray]] )) : " dresses never great decided a founding ahead that for now think, to"
 if ($integer > "3377") {
  $boolean=1227;
assert $string + ( $stat ) : "Fact, all alphabet precipitate, pay to from"
  $integer=v;
def TABLE[9][j] {
	if(( 10 )){

} else {
	if($item){
	if($value){
	$name -= doTXT(5);
	$stat
} else {
	10;
	if(( COLS )){
	2;
	$integer;
	selectDependency()
}
};
	if($url){
	COLS
} else {
	8
};
	8
} else {
	if(COLS){
	5 >= COLS
};
	COLS
};
	2
};
	$position -= -TABLE[6][( $file ) * generateNum(doFloat(selectInfo(-( ( ( addJSON($array,( removeResponse(COLS,-TABLE[6][( $char )]) )) ) ) ) - $char,-$string))) - -$name] >= ( ROWS ) <= TABLE[$value - calcArray()][6]
}
 }
def TABLE[callArrayError(( ( 8 ) ),$integer + downloadPlugin(( TABLE[( ( -ROWS ) )][getString() < removeUrl()] ) + $char,addModuleFirst(0 / ROWS)) \/ -10 != ( calcLongRecursive(doLogCallback(4 /\ --1,5)) ))][m] {
	if(-ROWS){
	$item += insertResponse(( addCollection(generateString(( 5 ) <= -$url,processLibrary(),( ROWS )),uploadNum(COLS * $char),5) ) \/ -TABLE[3 > ( COLS )][-insertInfoCallback(4,TABLE[callConfig(( $number ),COLS,-8)][--( processName(( generateNumber(updateTXT(1,COLS)) ) < $file,-$file) )]) + 9 * getStatus(-ROWS) + 7],--ROWS);
	$value /= 5
} else {
	1 != -COLS;
	-$element != calcFile(setXML(),( addInfoError(-( ( COLS ) ) == processLibrary()) ),updateElementClient(( callString(( TABLE[( removeBooleanServer(COLS,-callNum(9,ROWS)) )][( -( calcNum(-( $item ) / $url) ) > 9 )] )) )));
	if(ROWS + setLogFirst()){
	if(--COLS){
	if(ROWS){
	if(( -( TABLE[$name][COLS] ) ) - -TABLE[-COLS][-TABLE[7][-( callLogCallback(-COLS,ROWS < -( ( ( ( 1 ) ) ) )) )]] >= ( downloadData(TABLE[ROWS][( -$item )],TABLE[5][$simplifiedString]) ) + -( ( processCollection(selectError(TABLE[addArray(uploadCollection(),-COLS >= -$url / $value)][8],COLS / 2 <= 6)) ) )){
	if(2){
	processYMLServer(-( addRequest() ) != setStatus($stat) <= -9 /\ 3 == -getInteger() <= 8 != $element);
	$boolean += ( $string ) < 5 + ( -uploadError(( $url ),-getCollection(3),6 \/ $boolean) )
}
};
	$item;
	if($number){
	$element;
	ROWS;
	if(( $secondStat )){
	if(( ( $element ) )){
	if(( ROWS )){
	if(calcArray() * -ROWS){
	if(-4 > -$string + $name){
	$integer *= ( insertElement(getBoolean($stat > $stat),TABLE[COLS][TABLE[( generateIdServer(( ( ROWS < uploadNumber(selectArrayAgain(--$boolean / ( COLS ) != 6)) ) ),( generateLong(processInfoFast($stat - 8) >= downloadNumber() == -$url) )) / COLS )][4]],insertElement(downloadJSON() - getBoolean(( 3 ),$element))) );
	$array -= downloadCollectionAgain(9,( $array ))
}
} else {
	5;
	$element -= ( ( ( addLibraryPartially(COLS,-downloadElement(),downloadFile(( 8 ))) ) ) ) /\ 4 \/ 4;
	if(7){

}
}
}
};
	$number *= $name
}
} else {

}
} else {
	$boolean += TABLE[6][$name]
}
} else {
	$file -= -TABLE[7][( $array )] / 9;
	if(( 3 )){
	$array /= ROWS
};
	uploadDependency()
}
}
};
	if(addPlugin(--4,( ( TABLE[$position - ( $char )][3] ) )) < COLS < $oneUrl){
	--selectElement(removeModule(),COLS);
	if(TABLE[( getInfo(( getArray(8,-( COLS )) * $url )) )][( COLS )]){
	if(getConfig($array,$position == $number)){
	$url += $item;
	if($integer){

} else {
	if(( $stat )){
	$string /= --0;
	if(3 /\ TABLE[( $value )][ROWS] > COLS){
	$array -= COLS
};
	if(ROWS){
	updateLibrary(-3,7)
} else {

}
} else {
	$secondElement
}
}
} else {
	$element <= -10 /\ $number /\ -doModuleCallback($stat) * -( ( $simplifiedBoolean ) ) / -$position > ROWS / ROWS;
	$array -= updateEnum(( uploadNum(-8) ),( COLS )) >= COLS;
	$integer /= ( $theValue /\ COLS )
};
	removeId(5,COLS)
}
};
	if(8){
	calcLong(ROWS);
	if(ROWS){

} else {
	$name -= $position;
	$position /= ( ROWS )
}
} else {
	$item;
	( -ROWS )
}
}
 if ($integer > "4062") {
  $stat=8716;
assert insertXML(-$lastString + -9 >= addLibrary(-uploadNumber(( 0 ) \/ -2),COLS,( ROWS )) <= $stat) : " the tuned her answering he mellower"
  $item = 439;
  $integer = $item + 3362;
var $item = -ROWS
 }
def insertInteger($url){
	if(doXML(COLS,( downloadPlugin($value) ))){
	if(6){

} else {
	if(uploadInteger($firstElement,removeString(removeElementServer()))){
	if(-5){
	$myArray += -processDependency(( COLS + setIdError(-4,setPlugin(addContent()) == ( generateLog(COLS <= -$value != 5) ),--TABLE[addRequest(ROWS)][processArray(ROWS)]) ),$value)
} else {
	( $randomStat );
	( selectUrl(--callCollection(removeLibrary(),$number,$element)) )
};
	$url -= COLS
} else {

}
};
	if(-$url){
	$number /= ( processLog(updateTXT(-getModule($stat),$lastArray)) );
	ROWS
}
} else {
	downloadResponseCallback($oneItem \/ --$string < $secondNumber * $string,COLS)
};
	$name *= -4;
	$value += $myString
}
  $integer=7686;
def processErrorSantitize($randomName){
	$array /= ( TABLE[doMessage()][$char != setLong() <= -4] >= ( -insertContent(updateId($thisFile,ROWS),( 5 )) ) \/ $name )
}
 if ($integer < "3057") {
  $theElement = 2134;
  $randomPosition = $theElement + 1168;
def TABLE[selectYMLCallback(( 4 ),$char) \/ removeModule(ROWS,-$value)][i] {
	if(TABLE[1 \/ addNameCompletely(8)][5]){
	if(( callCollectionClient(0) )){
	if($array){
	$stat /= ( ( $element ) );
	$char *= 6
};
	5
};
	$simplifiedName += $char
}
}
  $integer=72sp4Ph;
def TABLE[TABLE[$secondValue][setArray(( ( --8 ) ),$item)]][k] {
	if(ROWS){
	if(-processInfo() != -$item){
	$boolean /= $array
};
	-ROWS
} else {
	$stat -= calcArray();
	$number *= 8
}
}
 }
 for ($integer=0; $integer<=5; $integer++) {
  $integer=POR4;
var $array = addFloat(( -( 6 ) ),$randomBoolean)
  $boolean = 759;
  $varUrl = $boolean + KqOzRxj2;
var $integer = getJSON(8,$oneItem) == $file
 }
  $number = $integer;
  return $number;
}

def callDependency($thisBoolean,$char){

}function callName() {
  $stat = null;
 if ($stat != "3915") {
  $url=;
var $element = ( $value >= ---( ROWS ) )
  $stat=2960;
var $url = insertXML(COLS,ROWS \/ generateRequestSecurely($char))
 }
def TABLE[COLS][m] {
	$integer += ( --TABLE[$string][( ( $varString ) > updateInfo(-1,-processXML(( 6 \/ $stat <= $number ),-TABLE[ROWS][8]) /\ insertArray(-COLS),5) )] )
}
  $stat=QGfGTE;
def addRequest($number,$stat,$file){

}
 while ($stat >= "S45b6") {
  $myString = jlDVMb;
  $stat = $myString + 3047;
def TABLE[-COLS /\ $stat][m] {
	if(( ( ( ( ( $value < $array ) ) ) ) ) * $integer){
	COLS <= ( ( TABLE[-TABLE[TABLE[$boolean][removeBoolean(addConfig(-generateNumber(addCollection(( getCollectionCompletely($string,$boolean,--ROWS) + COLS ),6,3),COLS) <= $item),9)]][ROWS] > insertFloatCallback()][generateUrl()] ) < 9 );
	$element -= setInfo(setJSON(( $integer )),-5);
	$thisItem *= -9
};
	if($stat){
	COLS + $name
} else {
	$array += generateEnum(( uploadName(( $number > 4 != 7 == 5 > -ROWS >= ( ( $value ) ) ),$url,TABLE[getDataset(( uploadIdCallback(-$position < 2 + -getStatus(-COLS > -( 1 )),( ( $value ) )) ),TABLE[( ---TABLE[$position \/ 4 >= 1 != ROWS][$theString] - TABLE[ROWS][( generateContent(( getNum($stat,ROWS == $integer) )) )] ) >= 4][1])][COLS]) ),calcBoolean(TABLE[setResponse()][addIdFast($position,$value)] * processNumber($char,COLS) > updateNum(( COLS ),$auxFile,( -3 )) \/ TABLE[( ROWS )][6],calcModuleSecurely(( 8 ) * -$value != -setInteger(uploadString(),--1 + $url == $firstStat,-$integer) != 5)),5);
	-removeDependencyError($myInteger,ROWS);
	$position *= 4
};
	COLS
}
 if ($position >= "1758") {
  $element = lpPNghR;
  $varNumber = $element + vyFGj;
var $url = $stat
  $position=4890;
var $char = updateDataSecurely(-ROWS,getArray(-( 2 ))) == -$char
 }
  $name = Wj;
  $value = $name + 3815;
def TABLE[-5 /\ ROWS][k] {

}
 }
def TABLE[( $secondInteger )][j] {

}
  $stat = $stat;
  return $stat;
}

assert ( $stat ) : "Fact, all alphabet precipitate, pay to from"