<?php

class SiteController extends Controller
{
    //private $_notifications;

	/************ FILTROS Y REGLAS DE ACCESO ****************/

	/*public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }*/

	/**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    /*public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('alistamiento'),
                //'users'=>array('admin'),
				'roles'=>array('Administrador', 'Usuario'),
				//'expression'=>"Yii::app()->controller->isPostOwner()",
				//'expression'=>"Yii::app()->controller->puedo()",
				'expression'=>"puedoAlistarme()", //Dejo entrar si hay evento abierto sólo
            ),
            array('deny',  // deny all users
				'actions'=>array('alistamiento'),
                'users'=>array('*'),
            ),
        );
    }*/

	/*public function puedoAlistarme() {
		if (isset(Yii::app()->currentUser->groupId))
			return Event::model()->exists('group_id=:groupId AND open=1', array(':groupId'=>Yii::app()->currentUser->groupId));
		else return false;
	}*/

	
	/************************ ACCIONES *******************/

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			/*'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),*/
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction'
			),
					        
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{

        $model=new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }

		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
        if(Yii::app()->user->checkAccess('Administrador')) {
            $this->redirect(array('/administration/home'));
        } else if(Yii::app()->user->checkAccess('Usuario')) {
            //Estoy identificado, muestro el Muro
            $data_notif = $this->loadNotifications();
			if($data_notif!=null) $data_notif = $this->processNotifications($data_notif);

            $corral_notif = $this->loadNotificationsCorral();
            if($corral_notif!=null) $corral_notif = $this->processNotifications($corral_notif);

            $this->render('index', array('notifications'=>$data_notif, 'notifications_corral'=>$corral_notif));
        } else{
            $this->layout = 'guest';
		    $this->render('login', array('model'=>$model));
        }
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	/*public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}*/

	/**
	 * Displays the login page
	 */
	/*public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}*/

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}


	/*public function actionPrueba()
    {
        $this->render('prueba');
    }*/
    /*public function actionPruebaAjax()
    {
        $data = array();
        $data["valor"] = 'Funciono con AJAX';
        $this->renderPartial('_ajaxPrueba', $data, false, true);
    }

    public function actionRead($date) 
	{
        $d = date_parse($date);
        if($d != false){
            $notifications = User::model()->updateByPk(Yii::app()->currentUser->id,array("last_notification_read" => $date));
        }
		Yii::app()->end(); //Para terminar ya que no devuelvo ni view ni nada.
    }
	

    public function actionLoad($date,$type) {
        $d = date_parse($date);
        if($d != false){
            $notifications = Notification::model()->findAll(array('condition'=>'timestamp < :d', 'params'=>array(':d' => $date), 'order'=>'timestamp DESC', 'limit'=>Yii::app()->config->getParam('maxNotificacionesMuro')));

            if(count($notifications) < Yii::app()->config->getParam('maxNotificacionesMuro'))
                $data['hay_mas'] = false;
            else
                $data['hay_mas'] = true;
            $data['type'] = $type;
            $data["notifications"] = $notifications;
            $this->renderPartial('more',$data);
        }
    }

    public function actionAskForNew($date) {
        $d = date_parse($date);
        if($d != false){
            $notifications = Notification::model()->count('timestamp > :d', array(':d' => $date));

            $data["notifications"] = $notifications;
            echo $notifications;
        }
    }*/



    /******* Funciones auxiliares **********/
    public function loadNotifications() {
        $notifications = Notification::model()->findAll(array('condition'=>'event_id=:evento AND (type!=:type OR (type=:type AND recipient_final=:recipient))', 'params'=>array(':evento'=>Yii::app()->event->id, ':type'=>'system', ':recipient'=>Yii::app()->currentUser->id), 'order'=>'timestamp DESC', 'limit'=>Yii::app()->config->getParam('maxNewNotificacionesMuro')));

        return $notifications;
    }

	//Separa las notificaciones en nuevas y viejas
	public function processNotifications($data_notif)
	{
		$user = Yii::app()->currentUser->model;
		
		$last_read = $user->last_notification_read;
		if($last_read==null)
			$last_read = Yii::app()->event->getCurrentDate();
						
		//Proceso las notificaciones
		$nuevas = $viejas_aux = $viejas = array();

		foreach($data_notif as $noti) {
			if(strtotime($last_read) < strtotime($noti->timestamp))
				array_push($nuevas, $noti);
			else
				array_push($viejas_aux, $noti);
		}
		
		//$user->last_notification_read = date('Y-m-d H:i:s');
		
		// La actualización del last_read se hace ahora por ajax
		//if (!$user->save())
			//throw new CHttpException(400, 'Error al guardar el usuario '.$user->id.' procesando las notificaciones. ['.print_r($user->getErrors(),true).']');

		//Si con las nuevas no lleno el cupo de notificaciones del muro, cojo algunas viejas
		if (count($nuevas) < Yii::app()->config->getParam('maxNotificacionesMuro'))	{		
			$cantidad = intval(Yii::app()->config->getParam('maxNotificacionesMuro')) - count($nuevas);
			for($i=1; $i<=$cantidad; $i++) {
				$not = array_shift($viejas_aux);
				if($not===null)
					continue;
				else
					array_push($viejas, $not);
			}
		}

        if(count($data_notif) < Yii::app()->config->getParam('maxNotificacionesMuro'))
            $hay_mas = false;
        else
            $hay_mas = true;


		return array('new'=>$nuevas, 'old'=>$viejas, 'hay_mas'=>$hay_mas);
	}

    public function loadNotificationsCorral() {
        $notifications = NotificationCorral::model()->findAll(array('condition'=>'event_id=:evento AND user_id=:recipient', 'params'=>array(':recipient'=>Yii::app()->currentUser->id, ':evento'=>Yii::app()->event->id), 'order'=>'timestamp DESC', 'limit'=>Yii::app()->config->getParam('maxNewNotificacionesMuro')));

        return $notifications;
    }
}