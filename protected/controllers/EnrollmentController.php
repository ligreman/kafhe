<?php

class EnrollmentController extends Controller
{	
	// Uncomment the following methods and override them if needed
	
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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('index'),
                'roles'=>array('Usuario'),
                'expression'=>"(isset(Yii::app()->event->model) && Yii::app()->event->type=='desayuno' && (Yii::app()->event->status==Yii::app()->params->statusIniciado || Yii::app()->event->status==Yii::app()->params->statusBatalla))", //Dejo entrar si hay evento desayuno abierto sólo

            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
/*        if (isset(Yii::app()->currentUser->groupId))
			return Event::model()->exists('group_id=:groupId AND open=1', array(':groupId'=>Yii::app()->currentUser->groupId));
		else return false;
        /*
	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
        
    public function actionIndex()
	{
        $data = array();

        //Primero comprobaré si ya he metido mi desayuno o no (si hay enrollment de mi usuario para este evento)
        $enroll = Enrollment::model()->find(array('condition'=>'user_id=:user_id AND event_id=:event_id', 'params'=>array(':user_id'=>Yii::app()->currentUser->id, 'event_id'=>Yii::app()->event->id)));

        if ($enroll===null) { //Si no hay creo uno nuevo
            $enroll = new Enrollment;
            $data['already_enroll'] = false;
            $model = new EnrollmentForm('create'); //Modelo de formulario en modo crear
        } else {
            $data['already_enroll'] = true;
            $model = new EnrollmentForm('update');
        }

        $data['output'] = 'nada';

        //Recojo los meals y drinks para pasarselo a la vista
        $data['meals'] = Meal::model()->findAll(array('order'=>'type, name'));
        $data['drinks'] = Drink::model()->findAll(array('order'=>'type, name'));
        //findAll(array('order'=>'somefield', 'condition'=>'otherfield=:x', 'params'=>array(':x'=>$x)));


        /*
        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='enrollment-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }*/

        //Si viene del formulario....
        if(isset($_POST['EnrollmentForm']))
        {

            if (isset($_POST['btn_submit'])) {
                // collects user input data
                $model->attributes=$_POST['EnrollmentForm'];

                // validates user input and redirect to previous page if validated
                if($model->validate())
                {
                    if (!$enroll->isNewRecord) {
                        //Actualizo (cojo el enroll de antes)
                        $enroll->meal_id = $model->meal_id;
                        $enroll->drink_id = $model->drink_id;
                        $enroll->ito = $model->ito;
                        //Yii::log('actualizo enroll', 'warning', 'ENROLL');
                    } else {
                        //Guardo campo nuevo
                        $enroll->user_id = Yii::app()->currentUser->id;
                        $enroll->event_id = Yii::app()->event->id;
                        $enroll->meal_id = $model->meal_id;
                        $enroll->drink_id = $model->drink_id;
                        $enroll->ito = $model->ito;
                        //Yii::log('nuevo enroll', 'warning', 'ENROLL');
                    }
    
                    //Yii::log(print_r($enroll, true), 'error', 'ENROLL');
					//Le alisto
                    if (!$enroll->save()){
                        throw new CHttpException(400, 'Error al guardar o actualizar el pedido.');
                    }

                    $data['already_enroll'] = true;
                    //var_dump($enroll->errors);

                    $message = "";

					//Si el estado del usuario cambia (no es una actualización del pedido) le pongo alistado
					if (Yii::app()->currentUser->status==Yii::app()->params->statusDesertor) {

						if (!User::model()->updateByPk(Yii::app()->currentUser->id, array('status'=>Yii::app()->params->statusLibre)))
							throw new CHttpException(400, 'Error al actualizar el estado del usuario desertor ('.Yii::app()->currentUser->id.') a Libre.');

                        $message = ':'.Yii::app()->currentUser->side.': Se ahora es un agente libre ';
					} elseif (Yii::app()->currentUser->status!=Yii::app()->params->statusLibre  &&  Yii::app()->currentUser->status!=Yii::app()->params->statusAlistado) {

						
						if (!User::model()->updateByPk(Yii::app()->currentUser->id, array('status'=>Yii::app()->params->statusAlistado)))
							throw new CHttpException(400, 'Error al actualizar el estado del usuario ('.Yii::app()->currentUser->id.') a Alistado.');

                        $message = ':'.Yii::app()->currentUser->side.': Se ha alistado en el bando de '.Yii::app()->currentUser->side;
					}

                    $nota = new Notification;
                    $nota->recipient_original = Yii::app()->currentUser->id;;
                    $nota->recipient_final = Yii::app()->currentUser->id;;
                    $nota->message = $message; //Mensaje para el muro
                    $nota->type = Yii::app()->currentUser->side;
                    $nota->sender = Yii::app()->currentUser->id;

                    if (!$nota->save())
                        throw new CHttpException(400, 'Error al notificar el cambio de estado del usuario ('.Yii::app()->currentUser->id.') a Alistado.');

                    //hago un redirect para actualizar el userPanel
                    $this->redirect(array('/enrollment'));
                }
            }
            else if (isset($_POST['btn_cancel'])) {                
                //Elimino mi alistamiento
                if (!$enroll->isNewRecord) {
                    $enroll->delete();
                    $data['already_enroll'] = false;

                    $message = "";

					//Actualizao mi estado a Baja/Desertor
					if (Yii::app()->currentUser->status==Yii::app()->params->statusLibre) {

						if (!User::model()->updateByPk(Yii::app()->currentUser->id, array('status'=>Yii::app()->params->statusDesertor)))
							throw new CHttpException(400, 'Error al actualizar el estado del usuario ('.Yii::app()->currentUser->id.') a Desertor.');

                        $message = ':'.Yii::app()->currentUser->side.': Ha causado desertado';
					} else {

						if (!User::model()->updateByPk(Yii::app()->currentUser->id, array('status'=>Yii::app()->params->statusBaja)))
							throw new CHttpException(400, 'Error al actualizar el estado del usuario ('.Yii::app()->currentUser->id.') a Baja.');

                        $message = ':'.Yii::app()->currentUser->side.': Ha causado baja del bando de '.Yii::app()->currentUser->side;
					}

                    $nota = new Notification;
                    $nota->recipient_original = Yii::app()->currentUser->id;;
                    $nota->recipient_final = Yii::app()->currentUser->id;;
                    $nota->message = $message; //Mensaje para el muro
                    $nota->type = Yii::app()->currentUser->side;
                    $nota->sender = Yii::app()->currentUser->id;

                    if (!$nota->save())
                        throw new CHttpException(400, 'Error al notificar el cambio de estado del usuario ('.Yii::app()->currentUser->id.') a Baja.');

                    //hago un redirect para actualizar el userPanel
                    $this->redirect(array('/enrollment'));
                } else
                    throw new CHttpException(400,'Error al darse de baja: No se han encontrado tus datos de alistamiento.');
            }
        }
        //Si el usuario simplemente accede a la página...
        else if (!$enroll->isNewRecord)
        {
            //Toy actualizando así que pongo los valores de BBDD para el formulario
            $model->meal_id = $enroll->meal_id;
            $model->drink_id = $enroll->drink_id;
            $model->ito = $enroll->ito;
        }

        $data['model'] = $model;

        // displays the login form
        $this->render('index', $data);
	}
}
