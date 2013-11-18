<?php

class m131025_212115_v1_2 extends CDbMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->execute('ALTER TABLE `skill` CHANGE `require_user_status` `require_user_status` VARCHAR(50) NULL DEFAULT NULL;');
        $this->execute('ALTER TABLE `skill` CHANGE `require_event_status` `require_event_status` VARCHAR(50) NULL DEFAULT NULL;');

        //Ajustes en valores ya existentes
        $this->update('skill', array('require_event_status'=>'1,2', 'description'=>'Aumenta tu ritmo de regeneración de tueste durante 24 horas. No se acumula en intensidad y sólo funciona si eres Cazador, Alistado o Libertador. Si estás desecado sólo eliminará dicho penalizador.'), 'keyword=:key', array(':key'=>'hidratar'));
        $this->update('skill', array('require_event_status'=>'1,2'), 'keyword=:key', array(':key'=>'disimular'));
        $this->update('skill', array('require_event_status'=>'1,2', 'cost_tueste'=>'100'), 'keyword=:key', array(':key'=>'trampa'));
        $this->update('skill', array('require_event_status'=>'1,2'), 'keyword=:key', array(':key'=>'desecar'));
        $this->update('skill', array('cost_tueste'=>'75'), 'keyword=:key', array(':key'=>'rescatarGungubos'));

        $this->update('configuration', array('value'=>'10'), 'param=:key', array(':key'=>'porcentajeTuesteExtraPorRango'));
    }

    public function safeDown()
    {
        //Ajustes
        $this->update('skill', array('require_event_status'=>NULL, 'description'=>'Aumenta tu ritmo de regeneración de tueste durante 24 horas. No se acumula. Si estás desecado sólo eliminará dicho penalizador.'), 'keyword=:key', array(':key'=>'hidratar'));
        $this->update('skill', array('require_event_status'=>NULL), 'keyword=:key', array(':key'=>'disimular'));
        $this->update('skill', array('require_event_status'=>NULL, 'cost_tueste'=>'200'), 'keyword=:key', array(':key'=>'trampa'));
        $this->update('skill', array('require_event_status'=>NULL), 'keyword=:key', array(':key'=>'desecar'));
        $this->update('skill', array('cost_tueste'=>'100'), 'keyword=:key', array(':key'=>'rescatarGungubos'));

        $this->execute('ALTER TABLE `skill` CHANGE `require_user_status` `require_user_status` VARCHAR(255) NULL DEFAULT NULL;');
        $this->execute('ALTER TABLE `skill` CHANGE `require_event_status` `require_event_status` TINYINT(1) NULL DEFAULT NULL;');

        $this->update('configuration', array('value'=>'20'), 'param=:key', array(':key'=>'porcentajeTuesteExtraPorRango'));
    }
}