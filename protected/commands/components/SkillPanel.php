<?php
class SkillPanel extends CWidget {

    public function run() {
        $data['skills'] = Skill::model()->findAll(array('order'=>'category, name'));
		$data['targets'] = Yii::app()->usertools->users; //aquí están todos los users del grupo activo //User::model()->findAll('group_id = '.$user->group_id);
		$data['user'] = Yii::app()->currentUser->model;
        $data['maxTueste'] = Yii::app()->config->getParam('maxTuesteUsuario');
        $data['skillsHidden'] = isset(Yii::app()->request->cookies['skillsHidden']) ? Yii::app()->request->cookies['skillsHidden']->value : '1';
        
        $this->render('skillPanel',$data);
    }
}
?>