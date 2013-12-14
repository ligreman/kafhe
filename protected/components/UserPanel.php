<?php
class UserPanel extends CWidget {

    public function run() {
        $data['user'] = Yii::app()->currentUser->model;
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
            $modTrampa = Modifier::model()->findByAttributes(array('keyword'=>Yii::app()->params->modifierTrampaPifia));
            if ($modTrampa!==null) {
                $listaFinal[] = $modTrampa; //Siempre me devuelve 1 Objeto, no un array (el findByAttr)
            }
        }

        return $listaFinal;
    }

}
?>