<?php

class m140120_182205_kafhe_3_4_1 extends TXDbMigration
{
	public function safeUp()
	{
        $this->insert('configuration', array(
            'param'  =>  'maxVariacionProbabilidadPorFama',
            'value'  =>  '35',
            'category'  =>  'Fama',
            'description'  =>  'Porcentaje máximo de probabilidad que se puede ganar o perder por la fama al final de un evento.'
        ));

        $this->update('skill', array('require_user_side'=>'kafhe,achikhoria'), 'name=:nam', array(':nam'=>'Hidratar'));
	}

	public function down()
	{
        $this->delete('configuration', 'param=:parametro', array(':parametro'=>'maxVariacionProbabilidadPorFama'));
        $this->update('skill', array('require_user_side'=>null), 'name=:nam', array(':nam'=>'Hidratar'));
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
	        'gumbudo_action_duration' => NULL,      // Duración de actividad de un Gumbudo
            'gumbudo_action_rate' => NULL,          // Cada cuanto actúa el Gumbudo
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