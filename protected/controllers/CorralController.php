<?php

class CorralController extends Controller
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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('index'),
                'roles'=>array('Usuario'),
                'expression'=>"(isset(Yii::app()->event->model) && Yii::app()->event->type=='desayuno' && (Yii::app()->event->status==Yii::app()->params->statusIniciado || Yii::app()->event->status==Yii::app()->params->statusCalma || Yii::app()->event->status==Yii::app()->params->statusBatalla))", //Dejo entrar si hay evento desayuno abierto sólo

            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

	public function actionIndex()
	{
	    if (Yii::app()->currentUser->side!='libre') {
            $gungubos = Gungubo::model()->findAll('event_id=:evento AND owner_id=:owner', array(':evento'=>Yii::app()->event->id, ':owner'=>Yii::app()->currentUser->id));
            $gumbudos = Gumbudo::model()->findAll('event_id=:evento AND owner_id=:owner ORDER BY class', array(':evento'=>Yii::app()->event->id, ':owner'=>Yii::app()->currentUser->id));
		    $this->render('index', array('gungubos'=>$gungubos, 'gumbudos'=>$gumbudos));
        } else {
            //Para el Iluminado
            $this->render('iluminado');
        }
	}

}