<?php
class UserPanel extends CWidget {

    public function run() {
        $data['user'] = User::model()->findByPk(Yii::app()->user->id);
        $data['skills'] = Skill::model()->findAll(array('order'=>'category, type, name'));

        $this->render('userPanel',$data);
    }

}
?>