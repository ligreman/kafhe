<?php
class UserPanel extends CWidget {

    public function run() {
        $data['user'] = Yii::app()->currentUser->model;
        $data['modifiers'] = $this->getModifiers();
        $data['romanRank'] = $this->roman_numerals($data['user']->rank);

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

    private function roman_numerals($input_arabic_numeral='') {
        if ($input_arabic_numeral == '') { $input_arabic_numeral = date("Y"); } // DEFAULT OUTPUT: THIS YEAR
        $arabic_numeral            = intval($input_arabic_numeral);
        $arabic_numeral_text    = "$arabic_numeral";
        $arabic_numeral_length    = strlen($arabic_numeral_text);

        /*if (!ereg('[0-9]', $arabic_numeral_text)) {
            return false; }*/

        if ($arabic_numeral > 4999) {
            return false; }

        if ($arabic_numeral < 1) {
            return false; }

        if ($arabic_numeral_length > 4) {
            return false; }

        $roman_numeral_units    = $roman_numeral_tens        = $roman_numeral_hundreds        = $roman_numeral_thousands        = array();
        $roman_numeral_units[0]    = $roman_numeral_tens[0]    = $roman_numeral_hundreds[0]    = $roman_numeral_thousands[0]    = ''; // NO ZEROS IN ROMAN NUMERALS

        $roman_numeral_units[1]='I';
        $roman_numeral_units[2]='II';
        $roman_numeral_units[3]='III';
        $roman_numeral_units[4]='IV';
        $roman_numeral_units[5]='V';
        $roman_numeral_units[6]='VI';
        $roman_numeral_units[7]='VII';
        $roman_numeral_units[8]='VIII';
        $roman_numeral_units[9]='IX';

        $roman_numeral_tens[1]='X';
        $roman_numeral_tens[2]='XX';
        $roman_numeral_tens[3]='XXX';
        $roman_numeral_tens[4]='XL';
        $roman_numeral_tens[5]='L';
        $roman_numeral_tens[6]='LX';
        $roman_numeral_tens[7]='LXX';
        $roman_numeral_tens[8]='LXXX';
        $roman_numeral_tens[9]='XC';

        $roman_numeral_hundreds[1]='C';
        $roman_numeral_hundreds[2]='CC';
        $roman_numeral_hundreds[3]='CCC';
        $roman_numeral_hundreds[4]='CD';
        $roman_numeral_hundreds[5]='D';
        $roman_numeral_hundreds[6]='DC';
        $roman_numeral_hundreds[7]='DCC';
        $roman_numeral_hundreds[8]='DCCC';
        $roman_numeral_hundreds[9]='CM';

        $roman_numeral_thousands[1]='M';
        $roman_numeral_thousands[2]='MM';
        $roman_numeral_thousands[3]='MMM';
        $roman_numeral_thousands[4]='MMMM';

        if ($arabic_numeral_length == 3) { $arabic_numeral_text = "0" . $arabic_numeral_text; }
        if ($arabic_numeral_length == 2) { $arabic_numeral_text = "00" . $arabic_numeral_text; }
        if ($arabic_numeral_length == 1) { $arabic_numeral_text = "000" . $arabic_numeral_text; }

        $anu = substr($arabic_numeral_text, 3, 1);
        $anx = substr($arabic_numeral_text, 2, 1);
        $anc = substr($arabic_numeral_text, 1, 1);
        $anm = substr($arabic_numeral_text, 0, 1);

        $roman_numeral_text = $roman_numeral_thousands[$anm] . $roman_numeral_hundreds[$anc] . $roman_numeral_tens[$anx] . $roman_numeral_units[$anu];
        return ($roman_numeral_text);
    }

}
?>