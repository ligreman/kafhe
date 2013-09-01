CREATE TABLE IF NOT EXISTS `authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  FOREIGN KEY (parent) REFERENCES authitem (name) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (child) REFERENCES authitem (name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `authassignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  FOREIGN KEY (itemname) REFERENCES authitem (name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `rights` (
  `itemname` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`itemname`),
  FOREIGN KEY (itemname) REFERENCES authitem (name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;




CREATE TABLE IF NOT EXISTS `configuration` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `param` varchar(50) NOT NULL,
  `value` varchar(250) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `drink` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` enum('cafe','infusion','zumo','otro') NOT NULL DEFAULT 'otro',
  `ito` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `event` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `caller_id` int(11) NULL DEFAULT NULL,
  `caller_side` enum('kafhe','achikhoria') NULL DEFAULT NULL,
  `relauncher_id` int(11) NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `gungubos_kafhe` int(11) NULL DEFAULT '0',
  `gungubos_achikhoria` int(11) NULL DEFAULT '0',
  `last_gungubos_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('desayuno') NOT NULL DEFAULT 'desayuno',
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `meal` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` enum('tostada','pulga','bolleria','otro') NOT NULL DEFAULT 'otro',
  `ito` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sender` int(11) NULL DEFAULT NULL,
  `recipient_original` varchar(50) NULL DEFAULT NULL,
  `recipient_final` varchar(50) NULL DEFAULT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('kafhe','achikhoria','omelettus','system','libre') NOT NULL,  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `enrollment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `meal_id` int(11) NULL DEFAULT NULL,
  `drink_id` int(11) NULL DEFAULT NULL,
  `ito` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rank` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ranking` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `date` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `alias` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `birthdate` date NULL DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `group_id` int(10) NULL DEFAULT NULL,
  `side` enum('kafhe','achikhoria','libre') NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rank` smallint(5) NOT NULL DEFAULT '0',
  `ptos_tueste` smallint(5) NOT NULL DEFAULT '0',
  `ptos_retueste` smallint(5) NOT NULL DEFAULT '0',
  `ptos_relanzamiento` smallint(5) NOT NULL DEFAULT '0',
  `ptos_talentos` smallint(5) NOT NULL DEFAULT '0',
  `tostolares` smallint(5) NOT NULL DEFAULT '0',
  `experience` int(10) NOT NULL DEFAULT '0',
  `sugarcubes` smallint(5) NOT NULL DEFAULT '0',
  `dominio_tueste` smallint(5) NOT NULL DEFAULT '0',
  `dominio_habilidades` smallint(5) NOT NULL DEFAULT '0',
  `dominio_bandos` smallint(5) NOT NULL DEFAULT '0',
  `times` smallint(5) NOT NULL DEFAULT '0',
  `calls` smallint(5) NOT NULL DEFAULT '0',
  `last_regen_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_notification_read` timestamp NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `skill` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` varchar(255) NOT NULL,
  `category` enum('gungubos','batalla','relanzamiento', 'ancestral') NOT NULL,
  `type` enum('ofensiva','mejora','utilidad') NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `modifier_keyword` varchar(50) NOT NULL,
  `modifier_hidden` tinyint(1) NOT NULL DEFAULT '0',
  `duration` smallint(5) NULL DEFAULT NULL,
  `duration_type` enum('horas','evento','usos') NULL DEFAULT NULL,
  `critic` smallint(5) NOT NULL DEFAULT '0',
  `fail` smallint(5) NOT NULL DEFAULT '0',
  `cost_tueste` smallint(5) NULL DEFAULT NULL,
  `cost_retueste` smallint(5) NULL DEFAULT NULL,
  `cost_relanzamiento` smallint(5) NULL DEFAULT NULL,
  `cost_tostolares` smallint(5) NULL DEFAULT NULL,
  `is_cooperative` tinyint(1) NOT NULL DEFAULT '0',
  `cost_tueste_cooperate` smallint(5) NULL DEFAULT NULL,
  `cost_tostolares_cooperate` smallint(5) NULL DEFAULT NULL,
  `cooperate_benefit` smallint(5) NULL DEFAULT NULL,
  `require_target_user` tinyint(1) NOT NULL DEFAULT '0',
  `require_target_side` varchar(100) NULL DEFAULT NULL,
  `require_caller` tinyint(1) NOT NULL DEFAULT '0',    
  `require_user_side` varchar(100) NULL DEFAULT NULL,
  `require_user_min_rank` smallint(5) NULL DEFAULT NULL,
  `require_user_status` varchar(255) NULL DEFAULT NULL,
  `require_event_status` tinyint(1) NULL DEFAULT NULL,
  `require_talent_id` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `modifier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `event_id` int(10) NOT NULL,
  `caster_id` int(10) NOT NULL,
  `target_final` varchar(50) NOT NULL,
  `skill_id` int(10) NULL DEFAULT NULL,
  `item_id` int(10) NULL DEFAULT NULL,
  `keyword` varchar(50) NOT NULL,
  `value` int(10) NULL DEFAULT NULL,
  `duration` smallint(5) NULL DEFAULT '0',
  `duration_type` enum('horas','evento', 'usos') NULL DEFAULT 'horas',
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `cronpile` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `operation` varchar(50) NOT NULL,
  `params` varchar(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `user` (`id`, `username`, `password`, `alias`, `email`, `birthdate`, `role`, `group_id`, `side`, `status`, `rank`, `experience`, `ptos_tueste`, `ptos_retueste`, `ptos_relanzamiento`, `ptos_talentos`, `tostolares`, `sugarcubes`, `dominio_tueste`, `dominio_habilidades`, `dominio_bandos`, `times`, `calls`) VALUES
(1, 'admin', '$2a$10$lEkw/VyX4WJOpJrhKAqkoeQvI/ugLjJTmqutbSNKHnL3ysamayGYe', 'Administrador', 'admin@mail.com', NULL, 'admin', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES('tiempoRegeneracionTueste', '600', 'Juego', 'Intervalo de tiempo, en segundos, entre una regeneración automática de tueste y la siguiente.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES('tuesteRegeneradoIntervalo', '50', 'Juego', 'Puntos de tueste regenerado en cada intervalo de regeneración.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES('maxTuesteUsuario', '1000', 'Juego', 'Máximo puntos de tueste que puede tener un usuario.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES('maxExperienciaUsuario', '10000', 'Juego', 'Máximo puntos de experiencia que un usuario puede tener.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES('tiempoCriaGungubos', '3600', 'Juego', 'Intervalo de tiempo, en segundos, entre una cría de gungubos y la siguiente.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES('gungubosCriadosIntervalo', '100', 'Juego', 'Cantidad de gungubos criados a la hora por un bando.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES('maxNewNotificacionesMuro', '30', 'Visual', 'Cantidad máxima de notificaciones nuevas a mostrar en el muro (suele ser mayor que el límite de notificaciones normal).');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES('maxNotificacionesMuro', '10', 'Visual', 'Cantidad máxima de notificaciones a mostrar en el muro.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES('informacionCafeteria', 'El Espiral (987 213 178)', 'Información', 'Datos de la cafetería a la que se llama.');

