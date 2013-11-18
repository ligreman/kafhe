<?php
class UserPanel extends CWidget {

    public function run() {
        $data['skills'] = Skill::model()->findAll(array('order'=>'category, type, name'));
		$data['targets'] = Yii::app()->usertools->users; //aquí están todos los users del grupo activo //User::model()->findAll('group_id = '.$user->group_id);
		$data['user'] = Yii::app()->currentUser->model;
        $data['maxTueste'] = Yii::app()->config->getParam('maxTuesteUsuario');
        $data['skillsHidden'] = isset(Yii::app()->request->cookies['skillsHidden']) ? Yii::app()->request->cookies['skillsHidden']->value : '1';
        $data['modifiers'] = $this->getModifiers();

        $this->render('userPanel',$data);
    }

    private function getModifiers() {
        $mods = Yii::app()->modifier->modifiers;
        $listaFinal = array();

        //Ahora de esa lista, que son todos los que me afectan, quito los modificadores ocultos
        foreach($mods as $mod) {
            if ($mod->hidden) continue;

            $listaFinal[] = $mod;
        }


        /** Ahora saco modificadores que quiero mostrar de forma especial **/

        //Si eres agente libre se muestra el número de trampas que has puesto
        if(Yii::app()->user->side == 'libre') {
            $modTrampa = Modifier::model()->findByAttributes(array('keyword'=>Yii::app()->params->modifierTrampa));
            if ($modTrampa!==null) {
                $listaFinal[] = $modTrampa; //Siempre me devuelve 1 Objeto, no un array (el findByAttr)
            }
        }

        return $listaFinal;
    }

}
?>