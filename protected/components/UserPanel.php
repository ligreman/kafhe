<?php
class UserPanel extends CWidget {

    public function run() {
        $data['user'] = User::model()->findByPk(Yii::app()->user->id);


        $this->render('userPanel',$data);
    }

}
?>