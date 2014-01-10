CREATE TABLE IF NOT EXISTS `gungubo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `attacker_id` int(11) NULL DEFAULT NULL,
  `side` enum('kafhe','achikhoria','libre') NULL DEFAULT NULL,
  `health` tinyint(1) NOT NULL,
  `location` varchar(20) NOT NULL DEFAULT 'corral',
  `trait` varchar(50) NULL DEFAULT NULL,
  `trait_value` int(11) NULL DEFAULT NULL,
  `condition_status` varchar(50) NOT NULL DEFAULT 'normal',
  `condition_value` int(11) NULL DEFAULT NULL,
  `birthdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `gumbudo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `side` enum('kafhe','achikhoria','libre') NULL DEFAULT NULL,
  `class` varchar(20) NOT NULL,
  `actions` tinyint(1) NULL DEFAULT NULL,
  `trait` varchar(50) NULL DEFAULT NULL,
  `trait_value` int(11) NULL DEFAULT NULL,
  `weapon` varchar(50) NULL DEFAULT NULL,
  `ripdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `notification_corral` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `notification_id` int(11) NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `talent` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `keyword` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `category` enum('aprendiz','experto','maestro') NULL DEFAULT NULL,
  `required_id` varchar(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_talent` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `talent_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('tiempoRegeneracionTueste', '600', 'Tueste', 'Intervalo de tiempo, en segundos, entre una regeneración automática de tueste y la siguiente.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
  ('tuesteRegeneradoIntervalo', '10', 'Tueste', 'Puntos de tueste regenerado en cada intervalo de regeneración.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
  ('maxTuesteUsuario', '1000', 'Tueste', 'Máximo puntos de tueste que puede tener un usuario.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
  ('porcentajeTuesteExtraPorRango', '10', 'Tueste', 'Porcentaje de tueste extra que se regenera por rango.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('retuestePerSkill', '3', 'Retueste', 'Porcentaje de Retueste ganado por habilidad ejecutada.');


INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('maxExperienciaUsuario', '10000', 'Experiencia', 'Máximo puntos de experiencia que un usuario puede tener.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('expParticipar', '500', 'Experiencia', 'Experiencia recibida por participar en un evento.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('expNoLlamar', '500', 'Experiencia', 'Experiencia recibida por evitar llamar en un evento.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('expPorRango', '250', 'Experiencia', 'Experiencia recibida por evitar llamar en un evento, por cada rango que tengas a partir de 1.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('expPorcentajeHabilidadPorTueste', '10', 'Experiencia', 'Experiencia recibida por ejecutar una habilidad. Porcentaje del coste de tueste.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('expPorcentajeHabilidadPorRetueste', '20', 'Experiencia', 'Experiencia recibida por ejecutar una habilidad. Porcentaje del coste de retueste.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('initialFame', '50', 'Fama', 'Puntos de fama con que un jugador empieza un un evento.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('lostFameByInactivity', '10', 'Fama', 'Puntos de fama con que un jugador inactivo pierde cada hora.');

INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('rewardMoreCritic', '5', 'Recompensas', 'Aumento de la probabilidad de crítico por recompensa.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('rewardLessFail', '5', 'Recompensas', 'Disminución de la probabilidad de pifia por recompensa.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('rewardMinTueste', '5', 'Recompensas', 'Mínima cantidad de tueste que se puede tener, debido a recompensa.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('rewardMoreRegen', '5', 'Recompensas', 'Aumento de la regeneración de tueste por recompensa.');

INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('maxNewNotificacionesMuro', '30', 'Notificaciones', 'Cantidad máxima de notificaciones nuevas a mostrar en el muro (suele ser mayor que el límite de notificaciones normal).');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('maxNotificacionesMuro', '10', 'Notificaciones', 'Cantidad máxima de notificaciones a mostrar en el muro.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('informacionCafeteria', 'El Espiral (987 213 178)', 'Información', 'Datos de la cafetería a la que se llama.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
	('sobrecargaPorcentajeTuesteExtra', '25', 'Juego', 'Porcentaje de tueste extra que cuesta una habilidad por cada vez que aparece en el histórico.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('trampaTuesteProbabilidad', '50', 'Juego', 'Probabilidad de caer en la trampa de Tueste.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('trampaPifiaProbabilidad', '50', 'Juego', 'Probabilidad de caer en la trampa de Pifia.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('trampaConfusionProbabilidad', '50', 'Juego', 'Probabilidad de caer en la trampa de Confusión.');


INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('maxGungubosCorral', '50', 'Gungubos', 'Máxima cantidad de Gungubos que los jugadores pueden tener en el corral.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gunguboHealth', '5', 'Gungubos', 'Contadores de vida por defecto de los Gungubos de corral.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gunguboBombaProbabilidadEstallar', '20', 'Gungubos', 'Probabilidad de un Gungubo Bomba de explotar en el corral atacado.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gunguboBombaMinMuertes', '1', 'Gungubos', 'Mínimo número de Gungubos que mata al estallar.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gunguboBombaMaxMuertes', '3', 'Gungubos', 'Máximo número de Gungubos que mata al estallar.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gunguboBombaProbabilidadIncendiar', '30', 'Gungubos', 'Probabilidad de un Gungubo Bomba de Incendiar un corral.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
	('gunguboMolotovProbabilidadIncendiar', '75', 'Gungubos', 'Probabilidad de un Gungubo Molotov de Incendiar un corral.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gunguboZombieProbabilidadZombificar', '30', 'Gungubos', 'Probabilidad de convertir a un Gungubo en zombie.');		


INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
	('gumbudoGuardianActions', '1', 'Gumbudos', 'Contadores de acción que indica el número de veces que los Gumbudos Guardianes pueden defender cada hora.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('gumbudoGuardianProbabilidadAcorazado', '20', 'Gumbudos', 'Probabilidad de que el Gumbudo tenga la característica Acorazado.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gumbudoAsaltanteActions', '1', 'Gumbudos', 'Contadores de acción que indica el número de veces que los Gumbudos Asaltantes pueden atacar cada hora.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gumbudoAsaltanteMinMuertes', '1', 'Gumbudos', 'Mínimo número de Gungubos que mata en un ataque con éxito.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gumbudoAsaltanteMaxMuertes', '5', 'Gumbudos', 'Máximo número de Gungubos que mata en un ataque con éxito.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('gumbudoAsaltanteProbabilidadSanguinario', '20', 'Gumbudos', 'Probabilidad de que el Gumbudo tenga la característica de Sanguinario.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gumbudoNigromanteProbabilidadZombie', '25', 'Gumbudos', 'Probabilidad en cada ataque de convertir en Gungubos Zombie los cadáveres del cementerio.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gumbudoNigromanteMaxZombies', '10', 'Gumbudos', 'Máximo número de Gungubos Zombie que puede crear por ataque.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gumbudoNigromanteProbabilidadColera', '10', 'Gumbudos', 'Probabilidad de que cada Gungubo Zombie que convierta tenga la característica Cólera.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('gumbudoPestilenteProbabilidadInfectar', '50', 'Gumbudos', 'Probabilidad de que el Gumbudo infecte el corral atacado.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('gumbudoPestilenteProbabilidadFetido', '20', 'Gumbudos', 'Probabilidad de que el Gumbudo tenga la característica de Fétido.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('gumbudoPestilenteIntensidadEnfermedad', '1', 'Gumbudos', 'Cuantos contadores quita a los Gungubos la enfermedad.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gumbudoArtificieroProbabilidadBomba', '25', 'Gumbudos', 'Probabilidad en cada ataque de crear Gungubos Bomba con los cadáveres del cementerio.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gumbudoArtificieroMaxBombas', '10', 'Gumbudos', 'Máximo número de Gungubos Bomba que puede crear por ataque.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gumbudoAsedioGungubosSacrificados', '2', 'Gumbudos', 'Cantidad de Gungubos que se catapultan en cada ataque, y por tanto son sacrificados.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gumbudoAsedioMinMuertes', '1', 'Gumbudos', 'Mínimo número de Gungubos matados por ataque.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gumbudoAsedioMaxMuertes', '3', 'Gumbudos', 'Máximo número de Gungubos matados por ataque.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('gumbudoGunbudensteinProbabilidadCanibal', '10', 'Gumbudos', 'Probabilidad de que un Gungubo nacido bajo la influencia de un Gunbudenstein tenga la característica Caníbal.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
	('gumbudoHippieActions', '1', 'Gumbudos', 'Contadores de acción que indica el número de veces que los Gumbudos Hippies pueden actuar por hora.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('gumbudoHippieProbabilidadActuar', '50', 'Gumbudos', 'Probabilidad de que el Gumbudo Hippie afecte a un atacante y le pacifique.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES
  ('gumbudoHippieProbabilidadHiperactivo', '20', 'Gumbudos', 'Probabilidad de que el Gumbudo tenga la característica Hiperactivo.');


INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('incendiarMinQuemados', '2', 'Características y Condiciones', 'Mínimo número de Gungubos quemados por causa de un Incendiar.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('incendiarMaxQuemados', '4', 'Características y Condiciones', 'Máximo número de Gungubos quemados por causa de un Incendiar.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('canibalMinComidos', '1', 'Características y Condiciones', 'Mínimo número de Gungubos comidos por un Gungubo Caníbal.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('canibalMaxComidos', '3', 'Características y Condiciones', 'Máximo número de Gungubos comidos por un Gungubo Caníbal.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('quemaduraProbabilidadPropagacion', '15', 'Características y Condiciones', 'Probabilidad de que un Gungubo que muera con Quemadura propague esta condición a otros Gungubos adyacentes.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('quemaduraMinQuemados', '1', 'Características y Condiciones', 'Mínimo número de Gungubos a los que se propaga la Quemadura.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES 
	('quemaduraMaxQuemados', '3', 'Características y Condiciones', 'Máximo número de Gungubos a los que se propaga la Quemadura.');
  
