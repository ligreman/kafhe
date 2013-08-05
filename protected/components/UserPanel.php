<?php
class UserPanel extends CWidget {

    public function run() {
        $data['user'] = User::model()->findByPk(Yii::app()->user->id);
        $data['skills'] = Skill::model()->findAll(array('order'=>'category, type, name'));
		$data['targets'] = Yii::app()->usertools->users; //aquí están todos los users del grupo activo //User::model()->findAll('group_id = '.$user->group_id);

        $this->render('userPanel',$data);
    }

}
?>