<?php

class m131129_182205_kafhe_3_4 extends TXDbMigration
{
	public function safeUp()
	{
	    //Event
        //$this->execute('ALTER TABLE event DROP COLUMN gungubos_population;');
        //$this->execute('ALTER TABLE event DROP COLUMN gungubos_kafhe;');
        //$this->execute('ALTER TABLE event DROP COLUMN gungubos_achikhoria;');
        //$this->execute('ALTER TABLE event DROP COLUMN last_gungubos_criadores;');
        //$this->execute('ALTER TABLE event DROP COLUMN stored_tueste_kafhe;');
        //$this->execute('ALTER TABLE event DROP COLUMN stored_tueste_achikhoria;');
        $this->execute('ALTER TABLE event ADD last_tueste_regeneration_timestamp timestamp NULL DEFAULT NULL AFTER status;');
        $this->execute('ALTER TABLE event ADD last_gungubos_repopulate_timestamp timestamp NULL DEFAULT NULL AFTER status;');

	    //User
        $this->execute('ALTER TABLE user ADD fame int(11) NOT NULL DEFAULT 0 AFTER experience;');
		$this->execute('ALTER TABLE user ADD last_activity timestamp NULL DEFAULT NULL AFTER last_notification_read;');

		//Cron
        $this->execute('ALTER TABLE cronpile ADD type varchar(20) NULL DEFAULT NULL AFTER id;');

        //Skill
        $this->execute('ALTER TABLE skill ADD gunbudo_action_duration tinyint(1) NULL DEFAULT NULL AFTER duration_type;');
        $this->execute('ALTER TABLE skill ADD gunbudo_action_rate tinyint(1) NULL DEFAULT NULL AFTER gunbudo_action_duration;');
        $this->execute('ALTER TABLE skill ADD overload tinyint(1) NULL DEFAULT 1 AFTER require_talent_id;');
        $this->execute('ALTER TABLE skill ADD generates_notification tinyint(1) NULL DEFAULT 1 AFTER overload;');
        $this->execute('ALTER TABLE skill ADD cost_gungubos smallint(5) NULL DEFAULT NULL AFTER cost_tostolares;');
        $this->execute("ALTER TABLE skill CHANGE `category` `category` ENUM('gungubos','gunbudos','batalla','relanzamiento','ancestral');");
        $this->execute('ALTER TABLE modifier CHANGE `value` `value` VARCHAR(15);');

        $this->execute('TRUNCATE TABLE skill;');
        $this->execute("TRUNCATE TABLE configuration;");

        // OTEAR
        $this->insert('skill', array(
            'name'  =>  'Otear',                         // Nombre de la habilidad
            'description'  =>  'Otea los corrales Renunciantes para saber qué arma predomina entre sus Gunbudos Asaltantes.',                  // Descripción de la habilidad
            'category'  =>  'gungubos',                     // Categoría. Puede ser: gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'utilidad',                         // Tipo. Puede ser: ofensiva, mejora, utilidad
            'keyword'  =>  'otearKafhe',                      // Palabra clave para reconocer la habilidad programáticamente. Formato: usar el nombre de la habilidad, todo junto sin espacios y "camelcase" salvo primera palabra. Ej: de Cazar gungubos -> cazarGugubos
            'modifier_keyword'  =>  '',             // Palabra clave para el modificador que crea la habilidad, si es que lo crea. Puede ser cualquier palabra (minúsculas), intentar que sea un adjetivo relaccionado con el nombre de la habilidad. Ej: de Desecar -> desecado
            'modifier_hidden' => 0,                 // 1: Que no se muestre en la lista de modificadores de los jugadores; 0: se muestra de forma normal
            'duration'  =>  NULL,                   // Int con la cantidad para la duración
            'duration_type'  =>  NULL,              // Tipo de duración. Puede ser: horas, evento, usos
            'gunbudo_action_duration' => NULL,        // Duración de actividad de un Gunbudo
            'gunbudo_action_rate' => NULL,             // Cada cuanto actúa el Gunbudo
            'critic'  =>  10,                        // Int con el % crítico
            'fail'  =>  10,                          // Int con el % de pifia
            'extra_param' => NULL,                  // Probabilidad
            'cost_tueste'  =>  1,                   // Int con el coste en puntos de tueste
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
            'require_user_side'  =>  'kafhe',          // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios). Ej: kafhe,libre
            'require_user_min_rank'  =>  NULL,      // Int Rango mínimo para ejecutar la habilidad (con este rango ya se puede ejecutar)
            'require_user_max_rank'  =>  NULL,      // Int Rango máximo para ejecutar la habilidad (a partir de este rango ya no se puede ejecutar)
            'require_user_status'  =>  NULL,        // String. Posibles valores: 0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre. Se pueden poner varios separados por comas (sin espacios). Ej: 0,3,4,5
            'require_event_status'  =>  '1',       // ID del estado del evento que se requiere para poder ejecutar la habilidiad: 0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado. Sólo admite un valor.
            'require_talent_id'  =>  NULL,          // ID del talento requerido para ejecutar la habilidad
            'generates_notification' => 1           // Bool. Genera notificación en el muro.
        ));
        $this->insert('skill', array(
            'name'  =>  'Otear',                         // Nombre de la habilidad
            'description'  =>  'Otea los corrales Kafheítas para saber qué arma predomina entre sus Gunbudos Guardianes.',                  // Descripción de la habilidad
            'category'  =>  'gungubos',                     // Categoría. Puede ser: gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'utilidad',                         // Tipo. Puede ser: ofensiva, mejora, utilidad
            'keyword'  =>  'otearAchikhoria',                      // Palabra clave para reconocer la habilidad programáticamente. Formato: usar el nombre de la habilidad, todo junto sin espacios y "camelcase" salvo primera palabra. Ej: de Cazar gungubos -> cazarGugubos
            'modifier_keyword'  =>  '',             // Palabra clave para el modificador que crea la habilidad, si es que lo crea. Puede ser cualquier palabra (minúsculas), intentar que sea un adjetivo relaccionado con el nombre de la habilidad. Ej: de Desecar -> desecado
            'modifier_hidden' => 0,                 // 1: Que no se muestre en la lista de modificadores de los jugadores; 0: se muestra de forma normal
            'duration'  =>  NULL,                   // Int con la cantidad para la duración
            'duration_type'  =>  NULL,              // Tipo de duración. Puede ser: horas, evento, usos
            'gunbudo_action_duration' => NULL,        // Duración de actividad de un Gunbudo
            'gunbudo_action_rate' => NULL,             // Cada cuanto actúa el Gunbudo
            'critic'  =>  10,                        // Int con el % crítico
            'fail'  =>  10,                          // Int con el % de pifia
            'extra_param' => NULL,                  // Probabilidad
            'cost_tueste'  =>  1,                   // Int con el coste en puntos de tueste
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
            'require_user_side'  =>  'achikhoria',          // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios). Ej: kafhe,libre
            'require_user_min_rank'  =>  NULL,      // Int Rango mínimo para ejecutar la habilidad (con este rango ya se puede ejecutar)
            'require_user_max_rank'  =>  NULL,      // Int Rango máximo para ejecutar la habilidad (a partir de este rango ya no se puede ejecutar)
            'require_user_status'  =>  NULL,        // String. Posibles valores: 0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre. Se pueden poner varios separados por comas (sin espacios). Ej: 0,3,4,5
            'require_event_status'  =>  '1',       // ID del estado del evento que se requiere para poder ejecutar la habilidiad: 0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado. Sólo admite un valor.
            'require_talent_id'  =>  NULL,          // ID del talento requerido para ejecutar la habilidad
            'generates_notification' => 1           // Bool. Genera notificación en el muro.
        ));

        // HIDRATAR
        $this->insert('skill', array(
            'name'  =>  'Hidratar',
            'description'  =>  'Aumenta tu ritmo de regeneración de tueste un 50% durante 24 horas.<br />No se acumula.',
            'category'  =>  'batalla',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'mejora',         // ofensiva, mejora, utilidad
            'keyword'  =>  'hidratar',
            'modifier_keyword'  =>  'hidratado',
            'duration'  =>  24,
            'duration_type'  =>  'horas',  // horas, evento, usos
            'critic'  =>  10,
            'fail'  =>  5,
            'extra_param' => 50,        // Porcentaje que aumenta la regeneración
            'cost_tueste'  =>  200,
            'cost_retueste'  =>  NULL,
            'cost_relanzamiento'  =>  NULL,
            'cost_tostolares'  =>  NULL,
            'cost_gungubos' => 5,
            'is_cooperative'  =>  0,         // 0,1
            'cost_tueste_cooperate'  =>  NULL,
            'cost_tostolares_cooperate'  =>  NULL,
            'cooperate_benefit'  =>  NULL,      // valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,         // 0,1
            'require_target_side'  =>  NULL,  // valores (kafhe, achikhoria, libre) separados por comas
            'require_caller'  =>  0,         // 0,1
            'require_user_side'  =>  NULL,    // valores (kafhe, achikhoria, libre) separados por comas
            'require_user_min_rank'  =>  NULL,  // Rango mínimo para ejecutarla
            'require_user_status'  =>  NULL,  // valores separados por comas (0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre)
            'require_event_status'  =>  '1',   // ID del estado (0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado)
            'require_talent_id'  =>  NULL,
            'generates_notification' => 1           // Bool. Genera notificación en el muro.
        ));

        // TRAMPA de Tueste
        $this->insert('skill', array(
            'name'  =>  'Trampa de Tueste',
            'description'  =>  'El próximo jugador que ejecute una habilidad tendrá un 50% de perder 100 puntos de tueste después de ejecutar la habilidad.<br />Esta trampa permanece activa hasta que un jugador caiga en ella.<br />Se acumulan los usos. No crea notificación.',
            'category'  =>  'batalla',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'ofensiva',         // ofensiva, mejora, utilidad
            'keyword'  =>  'trampaTueste',
            'modifier_keyword'  =>  'trampaTueste',
            'modifier_hidden' => 1,
            'duration'  =>  1,
            'duration_type'  =>  'usos',  // horas, evento, usos
            'critic'  =>  5,
            'fail'  =>  10,
            'cost_tueste'  =>  100,
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

        // TRAMPA de Pifia
        $this->insert('skill', array(
            'name'  =>  'Trampa de Pifia',
            'description'  =>  'El próximo jugador que ejecute una habilidad tendrá un 50% de pifiar por caer en la trampa.<br />Esta trampa permanece activa hasta que un jugador caiga en ella.<br />Se acumulan los usos. No crea notificación.',
            'category'  =>  'batalla',     // gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'ofensiva',         // ofensiva, mejora, utilidad
            'keyword'  =>  'trampaPifia',
            'modifier_keyword'  =>  'trampaPifia',
            'modifier_hidden' => 1,
            'duration'  =>  1,
            'duration_type'  =>  'usos',  // horas, evento, usos
            'critic'  =>  5,
            'fail'  =>  10,
            'cost_tueste'  =>  100,
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


        // GUNBUDO ASALTANTE
        $this->insert('skill', array(
            'name'  =>  'Gunbudo Asaltante',                         // Nombre de la habilidad
            'description'  =>  'Evoluciona un Gungubo en un Gunbudo Asaltante.',                  // Descripción de la habilidad
            'category'  =>  'gunbudos',                     // Categoría. Puede ser: gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'utilidad',                         // Tipo. Puede ser: ofensiva, mejora, utilidad
            'keyword'  =>  'gunbudoAsaltante',                      // Palabra clave para reconocer la habilidad programáticamente. Formato: usar el nombre de la habilidad, todo junto sin espacios y "camelcase" salvo primera palabra. Ej: de Cazar gungubos -> cazarGugubos
            'modifier_keyword'  =>  '',             // Palabra clave para el modificador que crea la habilidad, si es que lo crea. Puede ser cualquier palabra (minúsculas), intentar que sea un adjetivo relaccionado con el nombre de la habilidad. Ej: de Desecar -> desecado
            'modifier_hidden' => 0,                 // 1: Que no se muestre en la lista de modificadores de los jugadores; 0: se muestra de forma normal
            'duration'  =>  NULL,                   // Int con la cantidad para la duración
            'duration_type'  =>  NULL,              // Tipo de duración. Puede ser: horas, evento, usos
            'gunbudo_action_duration' => 12,        // Duración de actividad de un Gunbudo
            'gunbudo_action_rate' => 2,             // Cada cuanto actúa el Gunbudo
            'critic'  =>  0,                        // Int con el % crítico
            'fail'  =>  0,                          // Int con el % de pifia
            'extra_param' => 20,                  // Probabilidad de que salga Asaltante Sanguinario
            'cost_tueste'  =>  1,                   // Int con el coste en puntos de tueste
            'cost_retueste'  =>  NULL,              // Int con el coste en puntos de retueste
            'cost_relanzamiento'  =>  NULL,         // Int con el coste en puntos de relanzamiento
            'cost_tostolares'  =>  NULL,            // Int con el coste en tostólares
            'cost_gungubos' => 1,
            'is_cooperative'  =>  0,                // 0: no es cooperativa. 1: es cooperativa
            'cost_tueste_cooperate'  =>  NULL,      // Int con el coste en tueste de unirse a cooperar en una habilidad
            'cost_tostolares_cooperate'  =>  NULL,  // Int con el coste en tostólares de unirse a cooperar
            'cooperate_benefit'  =>  NULL,          // Int valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,           // 0: no requiere; 1: requiere elegir a un usuario como objetivo
            'require_target_side'  =>  NULL,        // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios) Ej: kafhe,achikhoria
            'require_caller'  =>  0,                // 0: no requiere ser el llamador; 1: requiere ser el llamador para ejecutar la habilidad
            'require_user_side'  =>  'kafhe,achikhoria',          // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios). Ej: kafhe,libre
            'require_user_min_rank'  =>  NULL,      // Int Rango mínimo para ejecutar la habilidad (con este rango ya se puede ejecutar)
            'require_user_max_rank'  =>  NULL,      // Int Rango máximo para ejecutar la habilidad (a partir de este rango ya no se puede ejecutar)
            'require_user_status'  =>  NULL,        // String. Posibles valores: 0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre. Se pueden poner varios separados por comas (sin espacios). Ej: 0,3,4,5
            'require_event_status'  =>  '1',       // ID del estado del evento que se requiere para poder ejecutar la habilidiad: 0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado. Sólo admite un valor.
            'require_talent_id'  =>  NULL,          // ID del talento requerido para ejecutar la habilidad
            'overload'  =>  0,
            'generates_notification' => 0           // Bool. Genera notificación en el muro.
        ));

        // GUNBUDO GUARDIAN
        $this->insert('skill', array(
            'name'  =>  'Gunbudo Guardián',                         // Nombre de la habilidad
            'description'  =>  'Evoluciona un Gungubo en un Gunbudo Guardián.',                  // Descripción de la habilidad
            'category'  =>  'gunbudos',                     // Categoría. Puede ser: gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'utilidad',                         // Tipo. Puede ser: ofensiva, mejora, utilidad
            'keyword'  =>  'gunbudoGuardian',                      // Palabra clave para reconocer la habilidad programáticamente. Formato: usar el nombre de la habilidad, todo junto sin espacios y "camelcase" salvo primera palabra. Ej: de Cazar gungubos -> cazarGugubos
            'modifier_keyword'  =>  '',             // Palabra clave para el modificador que crea la habilidad, si es que lo crea. Puede ser cualquier palabra (minúsculas), intentar que sea un adjetivo relaccionado con el nombre de la habilidad. Ej: de Desecar -> desecado
            'modifier_hidden' => 0,                 // 1: Que no se muestre en la lista de modificadores de los jugadores; 0: se muestra de forma normal
            'duration'  =>  NULL,                   // Int con la cantidad para la duración
            'duration_type'  =>  NULL,              // Tipo de duración. Puede ser: horas, evento, usos
            'gunbudo_action_duration' => 12,        // Duración de actividad de un Gunbudo
            'gunbudo_action_rate' => NULL,             // Cada cuanto actúa el Gunbudo
            'critic'  =>  0,                        // Int con el % crítico
            'fail'  =>  0,                          // Int con el % de pifia
            'extra_param' => 20,                  // Probabilidad de que salga Guardián Acorazado
            'cost_tueste'  =>  1,                   // Int con el coste en puntos de tueste
            'cost_retueste'  =>  NULL,              // Int con el coste en puntos de retueste
            'cost_relanzamiento'  =>  NULL,         // Int con el coste en puntos de relanzamiento
            'cost_tostolares'  =>  NULL,            // Int con el coste en tostólares
            'cost_gungubos' => 1,
            'is_cooperative'  =>  0,                // 0: no es cooperativa. 1: es cooperativa
            'cost_tueste_cooperate'  =>  NULL,      // Int con el coste en tueste de unirse a cooperar en una habilidad
            'cost_tostolares_cooperate'  =>  NULL,  // Int con el coste en tostólares de unirse a cooperar
            'cooperate_benefit'  =>  NULL,          // Int valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,           // 0: no requiere; 1: requiere elegir a un usuario como objetivo
            'require_target_side'  =>  NULL,        // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios) Ej: kafhe,achikhoria
            'require_caller'  =>  0,                // 0: no requiere ser el llamador; 1: requiere ser el llamador para ejecutar la habilidad
            'require_user_side'  =>  'kafhe,achikhoria',          // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios). Ej: kafhe,libre
            'require_user_min_rank'  =>  NULL,      // Int Rango mínimo para ejecutar la habilidad (con este rango ya se puede ejecutar)
            'require_user_max_rank'  =>  NULL,      // Int Rango máximo para ejecutar la habilidad (a partir de este rango ya no se puede ejecutar)
            'require_user_status'  =>  NULL,        // String. Posibles valores: 0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre. Se pueden poner varios separados por comas (sin espacios). Ej: 0,3,4,5
            'require_event_status'  =>  '1',       // ID del estado del evento que se requiere para poder ejecutar la habilidiad: 0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado. Sólo admite un valor.
            'require_talent_id'  =>  NULL,          // ID del talento requerido para ejecutar la habilidad
            'overload'  =>  0,
            'generates_notification' => 0           // Bool. Genera notificación en el muro.
        ));

        // GUNBUDO CRIADOR
        $this->insert('skill', array(
            'name'  =>  'Gunbudo Criador',                         // Nombre de la habilidad
            'description'  =>  'Evoluciona un Gungubo en un Gunbudo Criador.',                  // Descripción de la habilidad
            'category'  =>  'gunbudos',                     // Categoría. Puede ser: gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'utilidad',                         // Tipo. Puede ser: ofensiva, mejora, utilidad
            'keyword'  =>  'gunbudoCriador',                      // Palabra clave para reconocer la habilidad programáticamente. Formato: usar el nombre de la habilidad, todo junto sin espacios y "camelcase" salvo primera palabra. Ej: de Cazar gungubos -> cazarGugubos
            'modifier_keyword'  =>  '',             // Palabra clave para el modificador que crea la habilidad, si es que lo crea. Puede ser cualquier palabra (minúsculas), intentar que sea un adjetivo relaccionado con el nombre de la habilidad. Ej: de Desecar -> desecado
            'modifier_hidden' => 0,                 // 1: Que no se muestre en la lista de modificadores de los jugadores; 0: se muestra de forma normal
            'duration'  =>  NULL,                   // Int con la cantidad para la duración
            'duration_type'  =>  NULL,              // Tipo de duración. Puede ser: horas, evento, usos
            'gunbudo_action_duration' => 24,        // Duración de actividad de un Gunbudo
            'gunbudo_action_rate' => NULL,             // Cada cuanto actúa el Gunbudo
            'critic'  =>  0,                        // Int con el % crítico
            'fail'  =>  0,                          // Int con el % de pifia
            'extra_param' => NULL,                  // Probabilidad de que salga Guardián
            'cost_tueste'  =>  1,                   // Int con el coste en puntos de tueste
            'cost_retueste'  =>  NULL,              // Int con el coste en puntos de retueste
            'cost_relanzamiento'  =>  NULL,         // Int con el coste en puntos de relanzamiento
            'cost_tostolares'  =>  NULL,            // Int con el coste en tostólares
            'cost_gungubos' => 1,
            'is_cooperative'  =>  0,                // 0: no es cooperativa. 1: es cooperativa
            'cost_tueste_cooperate'  =>  NULL,      // Int con el coste en tueste de unirse a cooperar en una habilidad
            'cost_tostolares_cooperate'  =>  NULL,  // Int con el coste en tostólares de unirse a cooperar
            'cooperate_benefit'  =>  NULL,          // Int valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,           // 0: no requiere; 1: requiere elegir a un usuario como objetivo
            'require_target_side'  =>  NULL,        // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios) Ej: kafhe,achikhoria
            'require_caller'  =>  0,                // 0: no requiere ser el llamador; 1: requiere ser el llamador para ejecutar la habilidad
            'require_user_side'  =>  'kafhe,achikhoria',          // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios). Ej: kafhe,libre
            'require_user_min_rank'  =>  NULL,      // Int Rango mínimo para ejecutar la habilidad (con este rango ya se puede ejecutar)
            'require_user_max_rank'  =>  NULL,      // Int Rango máximo para ejecutar la habilidad (a partir de este rango ya no se puede ejecutar)
            'require_user_status'  =>  NULL,        // String. Posibles valores: 0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre. Se pueden poner varios separados por comas (sin espacios). Ej: 0,3,4,5
            'require_event_status'  =>  '1',       // ID del estado del evento que se requiere para poder ejecutar la habilidiad: 0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado. Sólo admite un valor.
            'require_talent_id'  =>  NULL,          // ID del talento requerido para ejecutar la habilidad
            'overload'  =>  0,
            'generates_notification' => 0           // Bool. Genera notificación en el muro.
        ));
		
		// GUNBUDO NIGROMANTE
        $this->insert('skill', array(
            'name'  =>  'Gunbudo Nigromante',                         // Nombre de la habilidad
            'description'  =>  'Evoluciona un Gungubo en un Gunbudo Nigromante.',                  // Descripción de la habilidad
            'category'  =>  'gunbudos',                     // Categoría. Puede ser: gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'utilidad',                         // Tipo. Puede ser: ofensiva, mejora, utilidad
            'keyword'  =>  'gunbudoNigromante',                      // Palabra clave para reconocer la habilidad programáticamente. Formato: usar el nombre de la habilidad, todo junto sin espacios y "camelcase" salvo primera palabra. Ej: de Cazar gungubos -> cazarGugubos
            'modifier_keyword'  =>  '',             // Palabra clave para el modificador que crea la habilidad, si es que lo crea. Puede ser cualquier palabra (minúsculas), intentar que sea un adjetivo relaccionado con el nombre de la habilidad. Ej: de Desecar -> desecado
            'modifier_hidden' => 0,                 // 1: Que no se muestre en la lista de modificadores de los jugadores; 0: se muestra de forma normal
            'duration'  =>  NULL,                   // Int con la cantidad para la duración
            'duration_type'  =>  NULL,              // Tipo de duración. Puede ser: horas, evento, usos
            'gunbudo_action_duration' => 12,        // Duración de actividad de un Gunbudo
            'gunbudo_action_rate' => 2,             // Cada cuanto actúa el Gunbudo
            'critic'  =>  0,                        // Int con el % crítico
            'fail'  =>  0,                          // Int con el % de pifia
            'extra_param' => NULL,                  // Probabilidad de 
            'cost_tueste'  =>  1,                   // Int con el coste en puntos de tueste
            'cost_retueste'  =>  NULL,              // Int con el coste en puntos de retueste
            'cost_relanzamiento'  =>  NULL,         // Int con el coste en puntos de relanzamiento
            'cost_tostolares'  =>  NULL,            // Int con el coste en tostólares
            'cost_gungubos' => 1,
            'is_cooperative'  =>  0,                // 0: no es cooperativa. 1: es cooperativa
            'cost_tueste_cooperate'  =>  NULL,      // Int con el coste en tueste de unirse a cooperar en una habilidad
            'cost_tostolares_cooperate'  =>  NULL,  // Int con el coste en tostólares de unirse a cooperar
            'cooperate_benefit'  =>  NULL,          // Int valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,           // 0: no requiere; 1: requiere elegir a un usuario como objetivo
            'require_target_side'  =>  NULL,        // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios) Ej: kafhe,achikhoria
            'require_caller'  =>  0,                // 0: no requiere ser el llamador; 1: requiere ser el llamador para ejecutar la habilidad
            'require_user_side'  =>  'achikhoria',          // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios). Ej: kafhe,libre
            'require_user_min_rank'  =>  NULL,      // Int Rango mínimo para ejecutar la habilidad (con este rango ya se puede ejecutar)
            'require_user_max_rank'  =>  NULL,      // Int Rango máximo para ejecutar la habilidad (a partir de este rango ya no se puede ejecutar)
            'require_user_status'  =>  NULL,        // String. Posibles valores: 0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre. Se pueden poner varios separados por comas (sin espacios). Ej: 0,3,4,5
            'require_event_status'  =>  '1',       // ID del estado del evento que se requiere para poder ejecutar la habilidiad: 0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado. Sólo admite un valor.
            'require_talent_id'  =>  NULL,          // ID del talento requerido para ejecutar la habilidad
            'overload'  =>  0,
            'generates_notification' => 0           // Bool. Genera notificación en el muro.
        ));
		
		// GUNBUDO ARTIFICIERO
        $this->insert('skill', array(
            'name'  =>  'Gunbudo Artificiero',                         // Nombre de la habilidad
            'description'  =>  'Evoluciona un Gungubo en un Gunbudo Artificiero.',                  // Descripción de la habilidad
            'category'  =>  'gunbudos',                     // Categoría. Puede ser: gungubos, batalla, relanzamiento, ancestral
            'type'  =>  'utilidad',                         // Tipo. Puede ser: ofensiva, mejora, utilidad
            'keyword'  =>  'gunbudoArtificiero',                      // Palabra clave para reconocer la habilidad programáticamente. Formato: usar el nombre de la habilidad, todo junto sin espacios y "camelcase" salvo primera palabra. Ej: de Cazar gungubos -> cazarGugubos
            'modifier_keyword'  =>  '',             // Palabra clave para el modificador que crea la habilidad, si es que lo crea. Puede ser cualquier palabra (minúsculas), intentar que sea un adjetivo relaccionado con el nombre de la habilidad. Ej: de Desecar -> desecado
            'modifier_hidden' => 0,                 // 1: Que no se muestre en la lista de modificadores de los jugadores; 0: se muestra de forma normal
            'duration'  =>  NULL,                   // Int con la cantidad para la duración
            'duration_type'  =>  NULL,              // Tipo de duración. Puede ser: horas, evento, usos
            'gunbudo_action_duration' => 12,        // Duración de actividad de un Gunbudo
            'gunbudo_action_rate' => 2,             // Cada cuanto actúa el Gunbudo
            'critic'  =>  0,                        // Int con el % crítico
            'fail'  =>  0,                          // Int con el % de pifia
            'extra_param' => NULL,                  // Probabilidad de 
            'cost_tueste'  =>  1,                   // Int con el coste en puntos de tueste
            'cost_retueste'  =>  NULL,              // Int con el coste en puntos de retueste
            'cost_relanzamiento'  =>  NULL,         // Int con el coste en puntos de relanzamiento
            'cost_tostolares'  =>  NULL,            // Int con el coste en tostólares
            'cost_gungubos' => 1,
            'is_cooperative'  =>  0,                // 0: no es cooperativa. 1: es cooperativa
            'cost_tueste_cooperate'  =>  NULL,      // Int con el coste en tueste de unirse a cooperar en una habilidad
            'cost_tostolares_cooperate'  =>  NULL,  // Int con el coste en tostólares de unirse a cooperar
            'cooperate_benefit'  =>  NULL,          // Int valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,           // 0: no requiere; 1: requiere elegir a un usuario como objetivo
            'require_target_side'  =>  NULL,        // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios) Ej: kafhe,achikhoria
            'require_caller'  =>  0,                // 0: no requiere ser el llamador; 1: requiere ser el llamador para ejecutar la habilidad
            'require_user_side'  =>  'kafhe',          // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios). Ej: kafhe,libre
            'require_user_min_rank'  =>  NULL,      // Int Rango mínimo para ejecutar la habilidad (con este rango ya se puede ejecutar)
            'require_user_max_rank'  =>  NULL,      // Int Rango máximo para ejecutar la habilidad (a partir de este rango ya no se puede ejecutar)
            'require_user_status'  =>  NULL,        // String. Posibles valores: 0 Criador, 1 Cazador, 2 Alistado, 3 Baja, 4 Desertor, 5 Agente Libre. Se pueden poner varios separados por comas (sin espacios). Ej: 0,3,4,5
            'require_event_status'  =>  '1',       // ID del estado del evento que se requiere para poder ejecutar la habilidiad: 0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado. Sólo admite un valor.
            'require_talent_id'  =>  NULL,          // ID del talento requerido para ejecutar la habilidad
            'overload'  =>  0,
            'generates_notification' => 0           // Bool. Genera notificación en el muro.
        ));

	    //NEW
        $this->executeFile('data/kafhe_3_4.sql');
	}

	public function down()
	{
        $this->execute('ALTER TABLE event DROP COLUMN last_tueste_regeneration_timestamp;');
        $this->execute('ALTER TABLE event DROP COLUMN last_gungubos_repopulate_timestamp;');
        $this->execute('ALTER TABLE user DROP COLUMN fame;');
		$this->execute('ALTER TABLE user DROP COLUMN last_activity;');
        $this->execute('ALTER TABLE skill DROP COLUMN gunbudo_action_duration;');
        $this->execute('ALTER TABLE skill DROP COLUMN gunbudo_action_rate;');
        $this->execute('ALTER TABLE skill DROP COLUMN generates_notification;');
        $this->execute('ALTER TABLE skill DROP COLUMN overload;');
        $this->execute('ALTER TABLE skill DROP COLUMN cost_gungubos;');
        $this->execute('ALTER TABLE cronpile DROP COLUMN type;');
        $this->execute("ALTER TABLE `skill` CHANGE `category` `category` ENUM('gungubos','batalla','relanzamiento','ancestral');");
        $this->execute('ALTER TABLE modifier CHANGE `value` `value` INT(10);');

        $this->delete('skill', "keyword='gunbudoAsaltante'");
        $this->delete('skill', "keyword='gunbudoGuardian'");
        $this->delete('configuration', "param='maxGungubosCorral'");
        $this->delete('configuration', "param='defaultGunguboHelath'");


        $this->dropTable('gungubo');
        $this->dropTable('gunbudo');
        $this->dropTable('notification_corral');

        $this->execute("TRUNCATE TABLE cronpile;");
		//echo "m131129_182205_kafhe_3_4 does not support migration down.\n";
		//return false;
	}

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
	        'gunbudo_action_duration' => NULL,      // Duración de actividad de un Gunbudo
            'gunbudo_action_rate' => NULL,          // Cada cuanto actúa el Gunbudo
            'critic'  =>  ,                         // Int con el % crítico
            'fail'  =>  ,                           // Int con el % de pifia
            'extra_param' => NULL,                  // String con parámetro extra que se necesite para algo. Por ejemplo, CazarGungubos, para la cantidad de gugubos a cazar.
            'cost_tueste'  =>  NULL,                // Int con el coste en puntos de tueste
            'cost_retueste'  =>  NULL,              // Int con el coste en puntos de retueste
            'cost_relanzamiento'  =>  NULL,         // Int con el coste en puntos de relanzamiento
            'cost_tostolares'  =>  NULL,            // Int con el coste en tostólares
            'cost_gungubos' => NULL,                 // Int con el coste en gungubos
            'is_cooperative'  =>  0,                // 0: no es cooperativa. 1: es cooperativa
            'cost_tueste_cooperate'  =>  NULL,      // Int con el coste en tueste de unirse a cooperar en una habilidad
            'cost_tostolares_cooperate'  =>  NULL,  // Int con el coste en tostólares de unirse a cooperar
            'cooperate_benefit'  =>  NULL,          // Int valor numérico de beneficio, normalmente %
            'require_target_user'  =>  0,           // 0: no requiere; 1: requiere elegir a un usuario como objetivo
            'require_target_side'  =>  NULL,        // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios) Ej: kafhe,achikhoria
            'require_caller'  =>  0,                // 0: no requiere ser el llamador; 1: requiere ser el llamador para ejecutar la habilidad
            'require_user_side'  =>  NULL,          // String. Posibles valores: kafhe, achikhoria, libre. Se pueden poner varios separados por comas (sin espacios). Ej: kafhe,libre
            'require_user_min_rank'  =>  NULL,      // Int Rango mínimo para ejecutar la habilidad (con este rango ya se puede ejecutar)
	        'require_user_max_rank'  =>  NULL,      // Int Rango máximo para ejecutar la habilidad (a partir de este rango ya no se puede ejecutar)
            'require_user_status'  =>  NULL,        // String. Posibles valores: 0 Inactivo, 1 Alborotador, 2 Combatiente, 4 Espectador, 5 Libertador. Se pueden poner varios separados por comas (sin espacios). Ej: 0,3,4,5
            'require_event_status'  =>  NULL,       // ID del estado del evento que se requiere para poder ejecutar la habilidiad: 0 Cerrado, 1 Iniciado, 2 Batalla, 3 Finalizado. Sólo admite un valor.
            'require_talent_id'  =>  NULL,           // ID del talento requerido para ejecutar la habilidad
            'overload'  =>  1,                      // Bool. Tiene sobrecarga la habilidad, sí o no
            'generates_notification' => 1           // Bool. Genera notificación en el muro.
        ));
	 */