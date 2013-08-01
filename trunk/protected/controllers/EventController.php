<?php

class EventController extends Controller
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
				'actions'=>array('index'),
				'roles'=>array('Usuario'),
			),
			array('allow', 
				'actions'=>array('start'),
				'roles'=>array('Usuario'),
				'expression'=>"(isset(Yii::app()->event->model) && Yii::app()->event->status==Yii::app()->params->statusPreparativos && Yii::app()->user->checkAccess('lanzar_evento'))", //Dejo entrar 
			),
			array('allow', 
				'actions'=>array('finish'),
				'roles'=>array('Usuario'),
				'expression'=>"(isset(Yii::app()->event->model) && Yii::app()->event->status==Yii::app()->params->statusBatalla && isset(Yii::app()->event->caller) && Yii::app()->event->caller==Yii::app()->user->id)", //Dejo entrar 
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	

	public function actionIndex()
	{
		//Recojo los datos de la batalla
		$battle = Yii::app()->event->model;
		$users = Yii::app()->usertools->users;
		
		$this->render('index', array('battle'=>$battle, 'users'=>$users));
	}

	public function actionStart()
	{
		//Cambio el evento a estado 2 (batalla!!)
		if (!isset(Yii::app()->event->model))
			throw new CHttpException(400, 'Error al iniciar la batalla ya que no hay ningún evento activo.');
		
		$event = Yii::app()->event->model;
		$event->status = Yii::app()->params->statusBatalla;
					
		//Elijo al primer llamador
		$battleResult = Yii::app()->event->selectCaller();
		$event->caller_id = $battleResult['userId'];
		$event->caller_side = $battleResult['side'];
		
		//Guardo el evento
		if (!$event->save())
			throw new CHttpException(400, 'Error al guardar el estado del evento '.$event->id.'.');
			
		//Aviso al llamador
		
		//Aviso a los demás usuarios de que se inicia la batalla
		Yii::app()->user->setFlash('success', 'Comienza la batalla');
		$this->redirect(array('event/index'));
		//$this->render('start');
	}
	
	public function actionFinish()
	{
		$this->render('finish');
	}

}