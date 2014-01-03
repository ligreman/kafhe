<?php

class AjaxController extends Controller
{
    public function actionMarkAsRead($date) 
	{
        $d = date_parse($date);
        if($d != false){
            User::model()->updateByPk(Yii::app()->currentUser->id,array("last_notification_read" => $date));
        }
		Yii::app()->end(); //Para terminar ya que no devuelvo ni view ni nada.
    }
	

    public function actionLoadMoreNotifications($date,$type) {
        $d = date_parse($date);
        if($d != false){
            $notifications = Notification::model()->findAll(array('condition'=>'timestamp < :d', 'params'=>array(':d' => $date), 'order'=>'timestamp DESC', 'limit'=>Yii::app()->config->getParam('maxNotificacionesMuro')));

            if(count($notifications) < Yii::app()->config->getParam('maxNotificacionesMuro'))
                $data['hay_mas'] = false;
            else
                $data['hay_mas'] = true;
            $data['type'] = $type;
            $data['notifications'] = $notifications;
            $this->renderPartial('more',$data);
        }
    }

    public function actionLoadMoreCorralNotifications($date) {
        $d = date_parse($date);
        if($d != false){
            $notifications = NotificationCorral::model()->findAll(array('condition'=>'timestamp < :d', 'params'=>array(':d' => $date), 'order'=>'timestamp DESC', 'limit'=>Yii::app()->config->getParam('maxNotificacionesMuro')));

            if(count($notifications) < Yii::app()->config->getParam('maxNotificacionesMuro'))
                $data['hay_mas'] = false;
            else
                $data['hay_mas'] = true;
            $data['notifications'] = $notifications;
            $this->renderPartial('moreCorral',$data);
        }
    }

    public function actionAskForUpdates($date) {
		//Notificaciones nuevas
        $d = date_parse($date);
        if($d != false){
            $notifications = Notification::model()->count('timestamp > :d', array(':d' => $date));

            $data['notifications'] = $notifications;
            //echo $notifications;
			//echo CJavaScript::jsonEncode($data);
        } else
			$data['notifications'] = 0;
			
		//Tueste del usuario
		$user = User::model()->findByPk(Yii::app()->currentUser->id);
		$data['ptos_tueste'] = $user->ptos_tueste;
		$data['ptos_tueste_percent'] = floor(($user->ptos_tueste/Yii::app()->currentUser->maxTueste)*100);
		
		//Estado batalla
		$event = Event::model()->findByPk(Yii::app()->event->id);
		$data['gungubos_kafhe'] = $event->gungubos_kafhe;
		$data['gungubos_achikhoria'] = $event->gungubos_achikhoria;
    
    if ($event->gungubos_kafhe+$event->gungubos_achikhoria == 0)
      $data['gungubos_percent'] = 50;
    else
      $data['gungubos_percent'] = floor(($event->gungubos_kafhe/($event->gungubos_kafhe+$event->gungubos_achikhoria))*100);
		
		//Estado modificadores ¿?
			
		echo CJSON::encode($data);
        Yii::app()->end();
    }

}