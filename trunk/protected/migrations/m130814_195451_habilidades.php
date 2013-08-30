<?php

class m130814_195451_habilidades extends CDbMigration
{

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	    // HIDRATAR
        $this->insert('skill', array(
            'name'  =>  'Hidratar',
            'description'  =>  'Aumenta tu ritmo de regeneración de tueste durante 24 horas. No se acumula. Si estás desecado sólo eliminará dicho penalizador',
            'category'  =>  'batalla',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'mejora',         // ofensiva, mejora, utilidad
            'keyword'  =>  'hidratar',
            'modifier_keyword'  =>  'hidratado',
            'duration'  =>  24,
            'duration_type'  =>  'horas',  // horas, evento, usos
            'critic'  =>  10,
            'fail'  =>  10,
            'cost_tueste'  =>  200,
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
            'require_user_side'  =>  NULL,    // valores (kafhe, achikhoria, libre) separados por comas
            'require_user_min_rank'  =>  NULL,  // Rango mínimo para ejecutarla
            'require_user_status'  =>  '1,2,3,4,5',  // valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
            'require_event_status'  =>  NULL,   // ID del estado (0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado)
            'require_talent_id'  =>  NULL
        ));

        // DISIMULAR
        $this->insert('skill', array(
            'name'  =>  'Disimular',
            'description'  =>  'La próxima habilidad que lances no aparecerá en el muro de notificaciones. No se acumula. Esta habilidad tampoco aparecerá en el muro',
            'category'  =>  'batalla',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'utilidad',         // ofensiva, mejora, utilidad
            'keyword'  =>  'disimular',
            'modifier_keyword'  =>  'disimulando',
            'duration'  =>  1,       // Int cantidad para la duración
            'duration_type'  =>  'usos',  // horas, evento, usos
            'critic'  =>  15,
            'fail'  =>  10,
            'cost_tueste'  =>  250,
            'cost_retueste'  =>  NULL,
            'cost_relanzamiento'  =>  NULL,
            'cost_tostolares'  =>  NULL,
            'is_cooperative'  =>  0,         // 0,1
            'cost_tueste_cooperate'  =>  NULL,
            'cost_tostolares_cooperate'  =>  NULL,
            'cooperate_benefit'  =>  NULL,      // Int valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,         // 0,1
            'require_target_side'  =>  NULL,    // String valores (kafhe, achikhoria, libre) separados por comas
            'require_caller'  =>  0,         // 0,1
            'require_user_side'  =>  NULL,      // String valores (kafhe, achikhoria, libre) separados por comas
            'require_user_min_rank'  =>  NULL,  // Int Rango mínimo para ejecutarla
            'require_user_status'  =>  '2',    // String valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
            'require_event_status'  =>  NULL,   // ID del estado (0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado)
            'require_talent_id'  =>  NULL      // ID del talento requerido
        ));

        //CAZAR GUNGUBOS
        $this->insert('skill', array(
            'name'  =>  'Cazar gungubos',
            'description'  =>  'Cazas 100 gungubos para tu bando',
            'category'  =>  'gungubos',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'mejora',         // ofensiva, mejora, utilidad
            'keyword'  =>  'cazarGungubos',
            'modifier_keyword'  =>  '',
            'duration'  =>  NULL,       // Int cantidad para la duración
            'duration_type'  =>  NULL,  // horas, evento, usos
            'critic'  =>  15,
            'fail'  =>  15,
            'cost_tueste'  =>  100,
            'cost_retueste'  =>  NULL,
            'cost_relanzamiento'  =>  NULL,
            'cost_tostolares'  =>  NULL,
            'is_cooperative'  =>  0,         // 0,1
            'cost_tueste_cooperate'  =>  NULL,
            'cost_tostolares_cooperate'  =>  NULL,
            'cooperate_benefit'  =>  NULL,      // Int valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,         // 0,1
            'require_target_side'  =>  NULL,    // String valores (kafhe, achikhoria, libre) separados por comas
            'require_caller'  =>  0,         // 0,1
            'require_user_side'  =>  'kafhe,achikhoria',      // String valores (kafhe, achikhoria, libre) separados por comas
            'require_user_min_rank'  =>  NULL,  // Int Rango mínimo para ejecutarla
            'require_user_status'  =>  NULL,    // String valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
            'require_event_status'  =>  '1',   // ID del estado (0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado)
            'require_talent_id'  =>  NULL      // ID del talento requerido
        ));

        //GUNGUBICIDIO
        $this->insert('skill', array(
            'name'  =>  'Gungubicidio',
            'description'  =>  'Elimina 100 gungubos de un bando aleatorio',
            'category'  =>  'gungubos',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'ofensiva',         // ofensiva, mejora, utilidad
            'keyword'  =>  'gungubicidio',
            'modifier_keyword'  =>  '',
            'duration'  =>  NULL,       // Int cantidad para la duración
            'duration_type'  =>  NULL,  // horas, evento, usos
            'critic'  =>  10,
            'fail'  =>  10,
            'cost_tueste'  =>  100,
            'cost_retueste'  =>  NULL,
            'cost_relanzamiento'  =>  NULL,
            'cost_tostolares'  =>  NULL,
            'is_cooperative'  =>  0,         // 0,1
            'cost_tueste_cooperate'  =>  NULL,
            'cost_tostolares_cooperate'  =>  NULL,
            'cooperate_benefit'  =>  NULL,      // Int valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,         // 0,1
            'require_target_side'  =>  NULL,    // String valores (kafhe, achikhoria, libre) separados por comas
            'require_caller'  =>  0,         // 0,1
            'require_user_side'  =>  'libre',      // String valores (kafhe, achikhoria, libre) separados por comas
            'require_user_min_rank'  =>  NULL,  // Int Rango mínimo para ejecutarla
            'require_user_status'  =>  NULL,    // String valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
            'require_event_status'  =>  '1',   // ID del estado (0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado)
            'require_talent_id'  =>  NULL      // ID del talento requerido
        ));

        // TRAMPA
        $this->insert('skill', array(
            'name'  =>  'Trampa',
            'description'  =>  'Provoca que el próximo miembro de un bando que intente ejecutar una habilidad que no sea de relanzamiento pifie. Se acumulan los usos. Esta habilidad no aparecerá en el muro de notificaciones',
            'category'  =>  'batalla',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'ofensiva',         // ofensiva, mejora, utilidad
            'keyword'  =>  'trampa',
            'modifier_keyword'  =>  'trampa',
            'modifier_hidden' => 1,
            'duration'  =>  1,
            'duration_type'  =>  'usos',  // horas, evento, usos
            'critic'  =>  5,
            'fail'  =>  10,
            'cost_tueste'  =>  300,
            'cost_retueste'  =>  NULL,
            'cost_relanzamiento'  =>  NULL,
            'cost_tostolares'  =>  NULL,
            'is_cooperative'  =>  0,         // 0,1
            'cost_tueste_cooperate'  =>  NULL,
            'cost_tostolares_cooperate'  =>  NULL,
            'cooperate_benefit'  =>  NULL,      // valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,         // 0,1
            'require_target_side'  =>  NULL,  // valores (kafhe o achikhoria o libre) separados por comas. Uno u otro, no 'y'.
            'require_caller'  =>  0,         // 0,1
            'require_user_side'  =>  'libre',    // valores (kafhe, achikhoria, libre) separados por comas
            'require_user_min_rank'  =>  NULL,  // Rango mínimo para ejecutarla
            'require_user_status'  =>  NULL,  // valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
            'require_event_status'  =>  NULL,   // ID del estado (0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado)
            'require_talent_id'  =>  NULL
        ));

        // ESCAQUEARSE
        $this->insert('skill', array(
            'name'  =>  'Escaquearse',
            'description'  =>  'Relanza la selección del llamador para intentar escaquearte',
            'category'  =>  'relanzamiento',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'utilidad',         // ofensiva, mejora, utilidad
            'keyword'  =>  'escaquearse',
            'modifier_keyword'  =>  '',
            'duration'  =>  NULL,       // Int cantidad para la duración
            'duration_type'  =>  NULL,  // horas, evento, usos
            'critic'  =>  10,
            'fail'  =>  10,
            'cost_tueste'  =>  NULL,
            'cost_retueste'  =>  NULL,
            'cost_relanzamiento'  =>  2,
            'cost_tostolares'  =>  NULL,
            'is_cooperative'  =>  0,         // 0,1
            'cost_tueste_cooperate'  =>  NULL,
            'cost_tostolares_cooperate'  =>  NULL,
            'cooperate_benefit'  =>  NULL,      // Int valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,         // 0,1
            'require_target_side'  =>  NULL,    // String valores (kafhe, achikhoria, libre) separados por comas
            'require_caller'  =>  1,         // 0,1
            'require_user_side'  =>  NULL,      // String valores (kafhe, achikhoria, libre) separados por comas
            'require_user_min_rank'  =>  NULL,  // Int Rango mínimo para ejecutarla
            'require_user_status'  =>  NULL,    // String valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
            'require_event_status'  =>  '2',   // ID del estado (0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado)
            'require_talent_id'  =>  NULL      // ID del talento requerido
        ));

        //DESECAR
        $this->insert('skill', array(
            'name'  =>  'Desecar',
            'description'  =>  'El ritmo de regeneración de tueste del jugador objetivo se invierte durante 4 horas. No se acumula. Si está hidratado sólo eliminará dicho bonificador',
            'category'  =>  'batalla',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'ofensiva',         // ofensiva, mejora, utilidad
            'keyword'  =>  'desecar',
            'modifier_keyword'  =>  'desecado',
            'duration'  =>  4,       // Int cantidad para la duración
            'duration_type'  =>  'horas',  // horas, evento, usos
            'critic'  =>  10,
            'fail'  =>  15,
            'cost_tueste'  =>  300,
            'cost_retueste'  =>  NULL,
            'cost_relanzamiento'  =>  NULL,
            'cost_tostolares'  =>  NULL,
            'is_cooperative'  =>  0,         // 0,1
            'cost_tueste_cooperate'  =>  NULL,
            'cost_tostolares_cooperate'  =>  NULL,
            'cooperate_benefit'  =>  NULL,      // Int valor numérico de beneficio, normalmente %
            'require_target_user'  =>  1,         // 0,1
            'require_target_side'  =>  NULL,    // String valores (kafhe, achikhoria, libre) separados por comas
            'require_caller'  =>  0,         // 0,1
            'require_user_side'  =>  NULL,      // String valores (kafhe, achikhoria, libre) separados por comas
            'require_user_min_rank'  =>  3,  // Int Rango mínimo para ejecutarla
            'require_user_status'  =>  '1,2,3',    // String valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
            'require_event_status'  =>  NULL,   // ID del estado (0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado)
            'require_talent_id'  =>  NULL      // ID del talento requerido
        ));

	}

	public function safeDown()
	{
        $this->delete('skill', "1=1");
	}


	/*
	    $this->insert('skill', array(
            'name'  =>  '',
            'description'  =>  '',
            'category'  =>  '',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  '',         // ofensiva, mejora, utilidad
            'keyword'  =>  '',
            'modifier_keyword'  =>  '',
	        'modifier_hidden' => 0,       // Que no se muestre en la lista de modificadores de los jugadores
            'duration'  =>  NULL,       // Int cantidad para la duración
            'duration_type'  =>  NULL,  // horas, evento, usos
            'critic'  =>  ,
            'fail'  =>  ,
            'cost_tueste'  =>  NULL,
            'cost_retueste'  =>  NULL,
            'cost_relanzamiento'  =>  NULL,
            'cost_tostolares'  =>  NULL,
            'is_cooperative'  =>  0,         // 0,1
            'cost_tueste_cooperate'  =>  NULL,
            'cost_tostolares_cooperate'  =>  NULL,
            'cooperate_benefit'  =>  NULL,      // Int valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,         // 0,1
            'require_target_side'  =>  NULL,    // String valores (kafhe, achikhoria, libre) separados por comas
            'require_caller'  =>  0,         // 0,1
            'require_user_side'  =>  NULL,      // String valores (kafhe, achikhoria, libre) separados por comas
            'require_user_min_rank'  =>  NULL,  // Int Rango mínimo para ejecutarla
            'require_user_status'  =>  NULL,    // String valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
            'require_event_status'  =>  NULL,   // ID del estado (0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado)
            'require_talent_id'  =>  NULL      // ID del talento requerido
        ));
	 */

}