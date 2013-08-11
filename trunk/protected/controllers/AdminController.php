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
                //'users'=>array('jiji'),
				'roles'=>array('Admin'),
				//'expression'=>"Yii::app()->controller->isPostOwner()",
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
	/*

	function isPostOwner() {
        $post = Post::model()->findByPk($_GET['post_id']);
        $owner_id = $post->owner_id;
        if(Yii::app()->currentUser->id === $owner_id)
            return true;
        return false;
	}


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
        
        $this->render('index', null);
    }
}