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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `param` varchar(50) NOT NULL,
  `value` varchar(250) NOT NULL,
  `category` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `drink` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` enum('cafe','infusion','zumo','otro') NOT NULL DEFAULT 'otro',
  `ito` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL,
  `caller_id` int(11) unsigned NULL DEFAULT NULL,
  `relauncher_id` int(11) unsigned NULL DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` enum('desayuno') NOT NULL DEFAULT 'desayuno',
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `meal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` enum('tostada','pulga','bolleria','otro') NOT NULL DEFAULT 'otro',
  `ito` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender` int(11) unsigned NULL DEFAULT NULL,
  `recipient_original` int(11) unsigned NULL DEFAULT NULL,
  `recipient_final` int(11) unsigned NULL DEFAULT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('kafhe','achikhoria','omelettus','system') NOT NULL,
  `read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `enrollment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `event_id` int(11) unsigned NOT NULL,
  `meal_id` int(11) unsigned NOT NULL,
  `drink_id` int(11) unsigned NOT NULL,
  `ito` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `alias` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `birthdate` date NULL DEFAULT NULL,
  `role` enum('admin','moderator','user') NOT NULL DEFAULT 'user',
  `group_id` int(10) unsigned NULL DEFAULT NULL,
  `side` enum('kafhe','achikhoria') NULL DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `rank` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ptos_tueste` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ptos_retueste` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ptos_relanzamiento` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ptos_talentos` smallint(5) unsigned NOT NULL DEFAULT '0',
  `tostolares` smallint(5) unsigned NOT NULL DEFAULT '0',
  `azucarillos` smallint(5) unsigned NOT NULL DEFAULT '0',
  `dominio_tueste` smallint(5) unsigned NOT NULL DEFAULT '0',
  `dominio_habilidades` smallint(5) unsigned NOT NULL DEFAULT '0',
  `dominio_bandos` smallint(5) unsigned NOT NULL DEFAULT '0',
  `times` smallint(5) unsigned NOT NULL DEFAULT '0',
  `calls` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `user` (`id`, `username`, `password`, `alias`, `email`, `birthdate`, `role`, `group_id`, `side`, `status`, `rank`, `ptos_tueste`, `ptos_retueste`, `ptos_relanzamiento`, `ptos_talentos`, `tostolares`, `azucarillos`, `dominio_tueste`, `dominio_habilidades`, `dominio_bandos`, `times`, `calls`) VALUES
(1, 'admin', '$2a$10$lEkw/VyX4WJOpJrhKAqkoeQvI/ugLjJTmqutbSNKHnL3ysamayGYe', 'Administrador', 'admin@mail.com', NULL, 'admin', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);



