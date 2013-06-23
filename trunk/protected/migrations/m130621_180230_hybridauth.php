<?php

class m130621_180230_hybridauth extends CDbMigration
{
	public function up()
	{
	}

	public function down()
	{
		//echo "m130621_180230_hybridauth does not support migration down.\n";
		//return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	
	
	
	
	
	CREATE TABLE IF NOT EXISTS `ha_logins` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `userId` int(11) NOT NULL,
	  `loginProvider` varchar(50) NOT NULL,
	  `loginProviderIdentifier` varchar(100) NOT NULL,
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `loginProvider_2` (`loginProvider`,`loginProviderIdentifier`),
	  KEY `loginProvider` (`loginProvider`),
	  KEY `loginProviderIdentifier` (`loginProviderIdentifier`),
	  KEY `userId` (`userId`),
	  KEY `id` (`id`)
	) ENGINE=InnoDB
	*/
}