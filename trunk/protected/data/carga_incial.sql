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
  `relauncher_id` int(11) NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
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
  `recipient_original` int(11) NULL DEFAULT NULL,
  `recipient_final` int(11) NULL DEFAULT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('kafhe','achikhoria','omelettus','system') NOT NULL,
  `read` tinyint(1) NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `enrollment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `meal_id` int(11) NOT NULL,
  `drink_id` int(11) NOT NULL,
  `ito` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rank` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `alias` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `birthdate` date NULL DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `group_id` int(10) NULL DEFAULT NULL,
  `side` enum('kafhe','achikhoria') NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `rank` smallint(5) NOT NULL DEFAULT '0',
  `ptos_tueste` smallint(5) NOT NULL DEFAULT '0',
  `ptos_retueste` smallint(5) NOT NULL DEFAULT '0',
  `ptos_relanzamiento` smallint(5) NOT NULL DEFAULT '0',
  `ptos_talentos` smallint(5) NOT NULL DEFAULT '0',
  `tostolares` smallint(5) NOT NULL DEFAULT '0',
  `azucarillos` smallint(5) NOT NULL DEFAULT '0',
  `dominio_tueste` smallint(5) NOT NULL DEFAULT '0',
  `dominio_habilidades` smallint(5) NOT NULL DEFAULT '0',
  `dominio_bandos` smallint(5) NOT NULL DEFAULT '0',
  `times` smallint(5) NOT NULL DEFAULT '0',
  `calls` smallint(5) NOT NULL DEFAULT '0',
  `last_regen_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `skill` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` varchar(255) NOT NULL,
  `category` enum('gungubos','batalla','relanzamiento', 'ancestral') NOT NULL,
  `type` enum('ofensiva','mejora','utilidad') NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `duration` smallint(5) NOT NULL DEFAULT '0',
  `duration_type` enum('horas','evento','usos') NOT NULL DEFAULT 'horas',
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
  `require_target` tinyint(1) NOT NULL DEFAULT '0',
  `require_target_side` enum('kafhe','achikhoria') NULL DEFAULT NULL,  
  `require_caller` tinyint(1) NOT NULL DEFAULT '0',    
  `require_user_side` enum('kafhe','achikhoria') NULL DEFAULT NULL,
  `require_user_min_rank` smallint(5) NOT NULL DEFAULT '0',
  `require_user_status` tinyint(1) NULL DEFAULT NULL,
  `talent_id_required` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `modifier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `caster_id` int(10) NOT NULL,
  `target_final_id` int(10) NOT NULL,
  `skill_id` int(10) NULL DEFAULT NULL,
  `item_id` int(10) NULL DEFAULT NULL,
  `keyword` varchar(50) NOT NULL,
  `value` int(10) NULL DEFAULT NULL,
  `duration` smallint(5) NOT NULL DEFAULT '0',
  `duration_type` enum('horas','evento') NOT NULL DEFAULT 'horas',
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `user` (`id`, `username`, `password`, `alias`, `email`, `birthdate`, `role`, `group_id`, `side`, `status`, `rank`, `ptos_tueste`, `ptos_retueste`, `ptos_relanzamiento`, `ptos_talentos`, `tostolares`, `azucarillos`, `dominio_tueste`, `dominio_habilidades`, `dominio_bandos`, `times`, `calls`) VALUES
(1, 'admin', '$2a$10$lEkw/VyX4WJOpJrhKAqkoeQvI/ugLjJTmqutbSNKHnL3ysamayGYe', 'Administrador', 'admin@mail.com', NULL, 'admin', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

INSERT INTO `configuration` (`id`, `param`, `value`, `category`, `description`) VALUES(1, 'tiempoRegeneracionTueste', '600', 'Juego', 'Intervalo de tiempo, en segundos, entre una regeneración automática de tueste y la siguiente.');
INSERT INTO `configuration` (`id`, `param`, `value`, `category`, `description`) VALUES(2, 'tuesteRegeneradoIntervalo', '100', 'Juego', 'Puntos de tueste regenerado en cada intervalo de regeneración.');
INSERT INTO `configuration` (`id`, `param`, `value`, `category`, `description`) VALUES(3, 'maxTuesteUsuario', '1000', 'Juego', 'Máximo puntos de tueste que puede tener un usuario.');

