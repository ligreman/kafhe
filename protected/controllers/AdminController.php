<?php

class AdminController extends Controller
{
    private $_notifications;

	// Uncomment the following methods and override them if needed
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('index'),
                'users'=>array('admin'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
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

    /* Página principal, el muro */
    public function actionIndex()
    {
        $data_notif = $this->loadNotifications();
        $this->render('index', array('notifications'=>$data_notif));
    }

    public function loadNotifications() {
    $this->_notifications = null;
        if ($this->_notifications===null) {
            //$this->_notifications = Notification::model()->findAll('sender=:sender', , array(':sender'=>1));//array('order'=>'??.timestamp DESC'));
            $this->_notifications = Notification::model()->findAllByAttributes(array('sender'=>1), array('order'=>'timestamp DESC', 'limit'=>1));//array('order'=>'??.timestamp DESC'));
        }
        if($this->_notifications === null)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $this->_notifications;
    }
}