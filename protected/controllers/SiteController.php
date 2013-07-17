<?php

class SiteController extends Controller
{
    private $_notifications;

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
				'roles'=>array('Admin', 'Authenticated'),
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
		if (isset(Yii::app()->user->group_id))
			return Event::model()->exists('group_id=:groupId AND open=1', array(':groupId'=>Yii::app()->user->group_id));
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
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
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
        if(Yii::app()->user->checkAccess('Authenticated')) {
            //Estoy identificado
            $data_notif = $this->loadNotifications();
            $this->render('index', array('notifications'=>$data_notif));
        } else
		    $this->render('login', array('model'=>$model));
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


	public function actionPrueba()
    {
        $this->render('prueba',null);
    }
    public function actionPruebaAjax()
    {
        $data = array();
        $data["valor"] = 'Funciono con AJAX';
        $this->renderPartial('_ajaxPrueba', $data, false, true);
    }


	/*public function actionAlistamiento()
	{
		$data = array();
		
		//Recojo los meals y drinks para pasarselo
		$data['meals'] = Meal::model()->findAll(array('order'=>'type, name'));
		$data['drinks'] = Drink::model()->findAll(array('order'=>'type, name'));
		//findAll(array('order'=>'somefield', 'condition'=>'otherfield=:x', 'params'=>array(':x'=>$x)));

		$this->render('alistamiento', $data);*/

		/*$model = new LoginForm;
    $form = new CForm('application.views.site.loginForm', $model);
    if($form->submitted('login') && $form->validate())
        $this->redirect(array('site/index'));
    else
        $this->render('login', array('form'=>$form));*/
	//}


    /******* Funciones auxiliares **********/
    public function loadNotifications() {
        $this->_notifications = null;
        if ($this->_notifications===null) {
            //$this->_notifications = Notification::model()->findAllByAttributes(array('sender'=>1), array('order'=>'timestamp DESC', 'limit'=>1));
            $this->_notifications = Notification::model()->findAll(array('condition'=>'recipient_final IS NULL  OR  recipient_final=:recipient', 'params'=>array(':recipient'=>Yii::app()->user->id), 'order'=>'timestamp DESC'));
        }
        if($this->_notifications === null)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $this->_notifications;
    }
}