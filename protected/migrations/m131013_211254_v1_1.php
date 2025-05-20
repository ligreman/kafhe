<?php

class m131013_211254_v1_1 extends CDbMigration
{
	/*public function up()
	{
	}

	public function down()
	{
		echo "m131013_211254_v1_1 does not support migration down.\n";
		return false;
	}*/


	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        // OTEAR
        $this->insert('skill', array(
            'name'  =>  'Otear',
            'description'  =>  'Oteas el horizonte en busca de gungubos libres, mejorando a la vez tu precisión para la próxima Caza de gungubos. No se acumula.',
            'category'  =>  'gungubos',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'utilidad',         // ofensiva, mejora, utilidad
            'keyword'  =>  'otear',
            'modifier_keyword'  =>  'oteando',
            'duration'  =>  3,
            'duration_type'  =>  'horas',  // horas, evento, usos
            'critic'  =>  5,
            'fail'  =>  15,
			'extra_param' => '20', //Porcentaje de precisión
            'cost_tueste'  =>  75,
            'cost_retueste'  =>  NULL,
            'cost_relanzamiento'  =>  NULL,
            'cost_tostolares'  =>  NULL,
            'is_cooperative'  =>  0,         // 0,1
            'cost_tueste_cooperate'  =>  NULL,
            'cost_tostolares_cooperate'  =>  NULL,
            'cooperate_benefit'  =>  NULL,      // valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,         // 0,1
            'require_target_side'  =>  NULL,  // valores (kafhe, achikhoria, libre) separados por comas
            'require_caller'  =>  0,         // 0,1
            'require_user_side'  =>  'kafhe,achikhoria',    // valores (kafhe, achikhoria, libre) separados por comas
            'require_user_min_rank'  =>  NULL,  // Rango mínimo para ejecutarla
            'require_user_status'  =>  '1,2',  // valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
            'require_event_status'  =>  1,   // ID del estado (0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado)
            'require_talent_id'  =>  NULL
        ));
		
		//Alter tables
		$this->execute('ALTER TABLE cronpile ADD due_date timestamp NULL DEFAULT NULL AFTER params;');
		$this->execute('ALTER TABLE event DROP COLUMN last_gungubos_repopulation;');

	    //Ajustes en valores ya existentes
	    $this->update('user', array('rank'=>1), 'id=:id', array(':id'=>1));
	    $this->update('skill', array('fail'=>5, 'extra_param'=>50), 'keyword=:key', array(':key'=>'hidratar'));
        $this->update('skill', array('require_user_min_rank'=>3, 'require_user_status'=>'1,2', 'extra_param'=>50, 'cost_tueste'=>0, 'description'=>'La próxima habilidad que lances no aparecerá en el muro de notificaciones pero te costará un 50% más de tueste. No se acumula. Esta habilidad tampoco aparecerá en el muro.'), 'keyword=:key', array(':key'=>'disimular'));
        $this->update('skill', array('require_user_min_rank'=>4), 'keyword=:key', array(':key'=>'protegerGungubos'));
        $this->update('skill', array('description'=>'Atraes a tu bando entre 50 y 100 gungubos del bando contrario.', 'require_user_min_rank'=>5), 'keyword=:key', array(':key'=>'atraerGungubos'));
        $this->update('skill', array('fail'=>10, 'critic'=>15, 'require_user_min_rank'=>6), 'keyword=:key', array(':key'=>'desecar'));

        $this->update('skill', array('description'=>'Cazas de 50 a 100 gungubos libres para tu bando.'), 'keyword=:key', array(':key'=>'cazarGungubos'));
        $this->update('skill', array('description'=>'Liberas de 50 a 100 gungubos del bando contrario.'), 'keyword=:key', array(':key'=>'liberarGungubos'));
        $this->update('skill', array('description'=>'Liberas de 50 a 100 gungubos de un bando aleatorio.'), 'keyword=:key', array(':key'=>'rescatarGungubos'));
		$this->update('skill', array('description'=>'Liberas de 200 gungubos de un bando aleatorio.'), 'keyword=:key', array(':key'=>'rescatarGungubos2'));
		$this->update('skill', array('description'=>'Liberas de 500 gungubos de un bando aleatorio.'), 'keyword=:key', array(':key'=>'rescatarGungubos3'));
		$this->update('skill', array('description'=>'Liberas de 1000 gungubos de un bando aleatorio.'), 'keyword=:key', array(':key'=>'rescatarGungubos4'));
	}

	public function safeDown()
	{
	    // OTEAR
	    $this->delete('skill', 'keyword=:key', array(':key'=>'otear'));

		//Alters
		$this->execute('ALTER TABLE cronpile DROP COLUMN due_date;');
		$this->execute('ALTER TABLE event ADD last_gungubos_repopulation date NULL DEFAULT NULL AFTER gungubos_achikhoria;');
		
	    //Ajustes
        $this->update('user', array('rank'=>0), 'id=:id', array(':id'=>1));
        $this->update('skill', array('fail'=>10, 'extra_param'=>NULL), 'keyword=:key', array(':key'=>'hidratar'));
        $this->update('skill', array('require_user_min_rank'=>NULL, 'require_user_status'=>'2', 'extra_param'=>NULL, 'cost_tueste'=>0, 'description'=>'La próxima habilidad que lances no aparecerá en el muro de notificaciones. No se acumula. Esta habilidad tampoco aparecerá en el muro.'), 'keyword=:key', array(':key'=>'disimular'));
        $this->update('skill', array('require_user_min_rank'=>NULL), 'keyword=:key', array(':key'=>'protegerGungubos'));
        $this->update('skill', array('description'=>'Atraes a tu bando hasta a 100 gungubos del bando contrario.', 'require_user_min_rank'=>NULL), 'keyword=:key', array(':key'=>'atraerGungubos'));
        $this->update('skill', array('fail'=>15, 'critic'=>10, 'require_user_min_rank'=>3), 'keyword=:key', array(':key'=>'desecar'));

        $this->update('skill', array('description'=>'Cazas hasta a 100 gungubos para tu bando.'), 'keyword=:key', array(':key'=>'cazarGungubos'));
        $this->update('skill', array('description'=>'Liberas hasta a 100 gungubos del bando contrario.'), 'keyword=:key', array(':key'=>'liberarGungubos'));
        $this->update('skill', array('description'=>'Liberas hasta a 100 gungubos de un bando aleatorio.'), 'keyword=:key', array(':key'=>'rescatarGungubos'));
	}

}