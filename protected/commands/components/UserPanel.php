<?php
class UserPanel extends CWidget {

    public function run() {
        $data['user'] = Yii::app()->currentUser->model;
        $data['modifiers'] = $this->getModifiers();
        $data['romanRank'] = Yii::app()->usertools->roman_numerals($data['user']->rank);

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
            $modsExtra = Modifier::model()->findAll(array('condition'=>'keyword=:k1 OR keyword=:k2 OR keyword=:k3', 'params'=>array('k1'=>Yii::app()->params->modifierTrampaPifia, 'k2'=>Yii::app()->params->modifierTrampaTueste, 'k3'=>Yii::app()->params->modifierTrampaConfusion)));

            //ByAttributes(array('keyword'=>Yii::app()->params->modifierTrampaPifia));
            if ($modsExtra!==null) {
                $listaFinal = array_merge($listaFinal, $modsExtra); //Siempre me devuelve 1 Objeto, no un array (el findByAttr)
            }
        }

        return $listaFinal;
    }



}
?>