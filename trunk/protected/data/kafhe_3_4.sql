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

CREATE TABLE IF NOT EXISTS `gunbudo` (
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


INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES('maxGungubosCorral', '50', 'Juego', 'Máxima cantidad de Gungubos que los jugadores pueden tener en el corral.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES('defaultGunguboHealth', '5', 'Juego', 'Contadores de vida por defecto de los Gungubos de corral.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES('defaultGunbudoGuardianActions', '1', 'Juego', 'Contadores de acción que indica el número de veces que los Gunbudos Guardianes pueden defender cada hora.');
INSERT INTO `configuration` (`param`, `value`, `category`, `description`) VALUES('defaultGunbudoAsaltanteActions', '1', 'Juego', 'Contadores de acción que indica el número de veces que los Gunbudos Asaltantes pueden atacar cada hora.');
