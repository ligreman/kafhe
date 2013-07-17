<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<p>El html de esto, para modificarlo, está en views/site/index.php</p>

<p>Notificaciones</p>
<?php
    //Ejemplo usando un widget
    foreach ($notifications as $notification) {
        //echo "Sender: ".$notification->sender."<br>";
        $this->widget('zii.widgets.CDetailView', array(
            'data'=>$notification,
            'attributes'=>array(
                'id',             // title attribute (in plain text)
                'sender',        // an attribute of the related object "owner"
                'message:html',
                'timestamp',  // description attribute in HTML
            ),
        ));

        echo "<br>";

		//$user = User::model()->findByPk($notification->sender);
		//echo $user->username;
		echo Yii::app()->usernames->getAlias($notification->sender);
		
    }


	//print_r(Yii::app()->usernames->users);

    //Ejemplo a pelo
    foreach ($notifications as $notification) {
        echo Yii::app()->usernames->getAlias($notification->recipient_final)."<br>";
        echo $notification->message."<br>";
        echo $notification->timestamp."<br>";
        echo $notification->type."<br>";
        echo $notification->read."<br>";
    }

?>