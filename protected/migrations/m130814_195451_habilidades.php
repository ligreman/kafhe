<?php

class m130814_195451_habilidades extends CDbMigration
{

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	    // HIDRATAR
        $this->insert('skill', array(
            'name'  =>  'Hidratar',
            'description'  =>  'Aumenta tu ritmo de regeneración de tueste durante 24 horas. No se acumula. Si estás desecado sólo eliminará dicho penalizador.',
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
            'require_user_status'  =>  '1,2,4,5',  // valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
            'require_event_status'  =>  NULL,   // ID del estado (0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado)
            'require_talent_id'  =>  NULL
        ));

        // DISIMULAR
        $this->insert('skill', array(
            'name'  =>  'Disimular',
            'description'  =>  'La próxima habilidad que lances no aparecerá en el muro de notificaciones. No se acumula. Esta habilidad tampoco aparecerá en el muro.',
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
            'description'  =>  'Cazas 100 gungubos para tu bando.',
            'category'  =>  'gungubos',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'mejora',         // ofensiva, mejora, utilidad
            'keyword'  =>  'cazarGungubos',
            'modifier_keyword'  =>  '',
            'duration'  =>  NULL,       // Int cantidad para la duración
            'duration_type'  =>  NULL,  // horas, evento, usos
            'critic'  =>  15,
            'fail'  =>  15,
            'extra_param' => '100',
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
            'require_user_status'  =>  '0,1,2',    // String valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
            'require_event_status'  =>  '1',   // ID del estado (0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado)
            'require_talent_id'  =>  NULL      // ID del talento requerido
        ));

        //MATAR GUNGUBOS
        $this->insert('skill', array(
            'name'  =>  'Matar gungubos',
            'description'  =>  'Matas 100 gungubos del bando contrario.',
            'category'  =>  'gungubos',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'mejora',         // ofensiva, mejora, utilidad
            'keyword'  =>  'matarGungubos',
            'modifier_keyword'  =>  '',
            'duration'  =>  NULL,       // Int cantidad para la duración
            'duration_type'  =>  NULL,  // horas, evento, usos
            'critic'  =>  15,
            'fail'  =>  15,
            'extra_param' => '100',
            'cost_tueste'  =>  150,
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
            'require_user_status'  =>  '0,1,2',    // String valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
            'require_event_status'  =>  '1',   // ID del estado (0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado)
            'require_talent_id'  =>  NULL      // ID del talento requerido
        ));

        //GUNGUBICIDIO
        $this->insert('skill', array(
            'name'  =>  'Gungubicidio',
            'description'  =>  'Elimina 100 gungubos de un bando aleatorio.',
            'category'  =>  'gungubos',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'ofensiva',         // ofensiva, mejora, utilidad
            'keyword'  =>  'gungubicidio',
            'modifier_keyword'  =>  '',
            'duration'  =>  NULL,       // Int cantidad para la duración
            'duration_type'  =>  NULL,  // horas, evento, usos
            'critic'  =>  10,
            'fail'  =>  10,
            'extra_param' => '100',
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
            'description'  =>  'Provoca que el próximo miembro de un bando que intente ejecutar una habilidad que no sea de relanzamiento pifie. Se acumulan los usos. Esta habilidad no aparecerá en el muro de notificaciones.',
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
            'description'  =>  'Relanza la selección del llamador para intentar escaquearte.',
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
            'description'  =>  'El ritmo de regeneración de tueste del jugador objetivo se invierte durante 4 horas. No se acumula. Si está hidratado sólo eliminará dicho bonificador.',
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
            'require_user_status'  =>  '1,2',    // String valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
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
            'name'  =>  '',                         // Nombre de la habilidad
            'description'  =>  '',                  // Descripción de la habilidad
            'category'  =>  '',                     // Categoría. Puede ser: gungubos, batalla, relanzamiento, ancestral
            'type'  =>  '',                         // Tipo. Puede ser: ofensiva, mejora, utilidad
            'keyword'  =>  '',                      // Palabra clave para reconocer la habilidad programáticamente. Formato: usar el nombre de la habilidad, todo junto sin espacios y "camelcase" salvo primera palabra. Ej: de Cazar gungubos -> cazarGugubos
            'modifier_keyword'  =>  '',             // Palabra clave para el modificador que crea la habilidad, si es que lo crea. Puede ser cualquier palabra (minúsculas), intentar que sea un adjetivo relaccionado con el nombre de la habilidad. Ej: de Desecar -> desecado
	        'modifier_hidden' => 0,                 // 1: Que no se muestre en la lista de modificadores de los jugadores; 0: se muestra de forma normal
            'duration'  =>  NULL,                   // Int con la cantidad para la duración
            'duration_type'  =>  NULL,              // Tipo de duración. Puede ser: horas, evento, usos
            'critic'  =>  ,                         // Int con el % crítico
            'fail'  =>  ,                           // Int con el % de pifia
            'extra_param' => NULL,                  // String con parámetro extra que se necesite para algo. Por ejemplo, CazarGungubos, para la cantidad de gugubos a cazar.
            'cost_tueste'  =>  NULL,                // Int con el coste en puntos de tueste
            'cost_retueste'  =>  NULL,              // Int con el coste en puntos de retueste
            'cost_relanzamiento'  =>  NULL,         // Int con el coste en puntos de relanzamiento
            'cost_tostolares'  =>  NULL,            // Int con el coste en tostólares
            'is_cooperative'  =>  0,                // 0: no es cooperativa. 1: es cooperativa
            'cost_tueste_cooperate'  =>  NULL,      // Int con el coste en tueste de unirse a cooperar en una habilidad
            'cost_tostolares_cooperate'  =>  NULL,  // Int con el coste en tostólares de unirse a cooperar
            'cooperate_benefit'  =>  NULL,          // Int valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,           // 0: no requiere; 1: requiere elegir a un usuario como objetivo
            'require_target_side'  =>  NULL,        // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios) Ej: kafhe,achikhoria
            'require_caller'  =>  0,                // 0: no requiere ser el llamador; 1: requiere ser el llamador para ejecutar la habilidad
            'require_user_side'  =>  NULL,          // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios). Ej: kafhe,libre
            'require_user_min_rank'  =>  NULL,      // Int Rango mínimo para ejecutar la habilidad (con este rango ya se puede ejecutar)
            'require_user_status'  =>  NULL,        // String. Posibles valores: 0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre. Se pueden poner varios separados por comas (sin espacios). Ej: 0,3,4,5
            'require_event_status'  =>  NULL,       // ID del estado del evento que se requiere para poder ejecutar la habilidiad: 0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado. Sólo admite un valor.
            'require_talent_id'  =>  NULL           // ID del talento requerido para ejecutar la habilidad
        ));
	 */

}