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
		array('deny',
			'roles'=>array('Administrador'), //Prevenir que el admin no entre ya que no es jugador
		),
		array('allow', 
		  'actions'=>array('index', 'skills'),
		  'roles'=>array('Usuario'),
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
	    $data = array();
	    $data['skills'] = Skill::model()->findAll(array('order'=>'category, type, name'));
		$this->render('skills', $data);
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
