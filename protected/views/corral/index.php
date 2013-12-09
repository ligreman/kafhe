<?php
/* @var $this CorralController */

    $users = User::model()->findAll();
    foreach ($users as $user) {
        $ggc = Gungubo::model()->count(array('condition'=>'event_id='.Yii::app()->event->id.' AND owner_id='.$user->id.' AND location="corral"'));
        $ggm = Gungubo::model()->count(array('condition'=>'event_id='.Yii::app()->event->id.' AND owner_id='.$user->id.' AND location="cementerio"'));
        $gb = Gunbudo::model()->count(array('condition'=>'event_id='.Yii::app()->event->id.' AND owner_id='.$user->id));

        echo "<br>".$user->username;
        echo "<br>&nbsp;&nbsp;Gungubos: ".$ggc.' en corral; '.$ggm.' en cementerio';
        echo "<br>&nbsp;&nbsp;Gunbudos: ".$gb;
        echo "<br>";
    }

?>
