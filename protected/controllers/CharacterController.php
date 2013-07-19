<?php

class CharacterController extends Controller
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
          array('allow', // allow admin user to perform 'admin' and 'delete' actions
              'actions'=>array('index', 'skills'),
              'roles'=>array('Authenticated'),
          ),
          array('deny',  // deny all users
              'users'=>array('*'),
          ),
      );
  }

	public function actionIndex()
	{
	   $data = array();
	   $data['user'] = User::model()->findByPk(Yii::app()->user->id);
		$this->render('index', $data);
	}

	public function actionSkills()
	{
		$this->render('skills');
	}

	// Uncomment the following methods and override them if needed
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
}
