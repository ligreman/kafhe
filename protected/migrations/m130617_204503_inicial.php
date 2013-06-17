<?php

class m130617_204503_inicial extends CDbMigration
{
	public function safeUp()
	{
		 $this->createTable('groups', array(
            'id' => 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name' => 'VARCHAR(20) NOT NULL',
            'active' => 'TINYINT(1) NOT NULL DEFAULT 0'
        ));

		$this->createTable('configuration', array(
            'id' => 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'param' => 'VARCHAR(50) NOT NULL',
            'value' => 'VARCHAR(250) NOT NULL',
            'category' => 'VARCHAR(50) NOT NULL'
        ));

		$this->createTable('ranks', array(
            'id' => 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'title' => 'VARCHAR(100) NOT NULL'
        ));

		$this->createTable('notifications', array(
            'id' => 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'sender' => 'INT NOT NULL',
            'recipient_original' => 'INT NOT NULL',
            'recipient_final' => 'INT NOT NULL',
            'message' => 'TEXT NOT NULL',
            'timestamp' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'type' => "ENUM('kafhe','achikhoria','omelettus','system') NOT NULL",
			'read' => 'TINYINT(1) NOT NULL DEFAULT 0'
        ));

		$this->createTable('events', array(
            'id' => 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'group_id' => 'INT NOT NULL',
            'caller_id' => 'INT NOT NULL',
            'relauncher_id' => 'INT NOT NULL',
            'open' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'date' => 'DATE NOT NULL'
        ));

		$this->createTable('orders', array(
            'id' => 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'user_id' => 'INT NOT NULL',
            'event_id' => 'INT NOT NULL',
            'meal_id' => 'INT NOT NULL',
            'drink_id' => 'INT NOT NULL',
            'ito' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'timestamp' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ));

		$this->createTable('meals', array(
            'id' => 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name' => 'VARCHAR(50) NOT NULL',
            'price' => 'INT NOT NULL',
            'ito' => 'TINYINT(1) NOT NULL DEFAULT 0'
        ));

		$this->createTable('drinks', array(
            'id' => 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name' => 'VARCHAR(50) NOT NULL',
            'price' => 'INT NOT NULL',
            'ito' => 'TINYINT(1) NOT NULL DEFAULT 0'
        ));


		/*$this->execute("ALTER TABLE users ADD email VARCHAR(100) NOT NULL;");
		$this->execute("ALTER TABLE users ADD role ENUM('admin','moderator','user') NOT NULL DEFAULT 'user';");
		$this->execute("ALTER TABLE users ADD group_id INT UNSIGNED NOT NULL DEFAULT 0;");
		$this->execute("ALTER TABLE users ADD brithday DATE;");
		$this->execute("ALTER TABLE users ADD ptos_tueste SMALLINT UNSIGNED NOT NULL DEFAULT 0;");
		$this->execute("ALTER TABLE users ADD ptos_retueste SMALLINT UNSIGNED NOT NULL DEFAULT 0;");
		$this->execute("ALTER TABLE users ADD ptos_relanzamiento SMALLINT UNSIGNED NOT NULL DEFAULT 0;");
		$this->execute("ALTER TABLE users ADD tostolares SMALLINT UNSIGNED NOT NULL DEFAULT 0;");
		$this->execute("ALTER TABLE users ADD ptos_talentos SMALLINT UNSIGNED NOT NULL DEFAULT 0;");
		$this->execute("ALTER TABLE users ADD azucarillos SMALLINT UNSIGNED NOT NULL DEFAULT 0;");
		$this->execute("ALTER TABLE users ADD dominio_tueste SMALLINT UNSIGNED NOT NULL DEFAULT 0;");
		$this->execute("ALTER TABLE users ADD dominio_habilidades SMALLINT UNSIGNED NOT NULL DEFAULT 0;");
		$this->execute("ALTER TABLE users ADD dominio_bandos SMALLINT UNSIGNED NOT NULL DEFAULT 0;");
		$this->execute("ALTER TABLE users ADD side ENUM('kafhe','achikhoria') DEFAULT NULL;");
		$this->execute("ALTER TABLE users ADD times SMALLINT UNSIGNED NOT NULL DEFAULT 0;");
		$this->execute("ALTER TABLE users ADD calls SMALLINT UNSIGNED NOT NULL DEFAULT 0;");
		$this->execute("ALTER TABLE users ADD rank_id SMALLINT UNSIGNED NOT NULL DEFAULT 0;");*/

    }
 
	public function safeDown()
	{
        $this->dropTable('groups');
        $this->dropTable('configuration');
        $this->dropTable('ranks');
        $this->dropTable('notifications');
        $this->dropTable('events');
        $this->dropTable('orders');
        $this->dropTable('meals');
        $this->dropTable('drinks');

		//$this->execute('ALTER TABLE users DROP COLUMN email, DROP COLUMN role, DROP COLUMN group_id, DROP COLUMN brithday, DROP COLUMN ptos_tueste, DROP COLUMN ptos_retueste, DROP COLUMN ptos_relanzamiento, DROP COLUMN tostolares, DROP COLUMN ptos_talentos, DROP COLUMN azucarillos, DROP COLUMN dominio_tueste, DROP COLUMN dominio_habilidades, DROP COLUMN dominio_bandos, DROP COLUMN side, DROP COLUMN times, DROP COLUMN calls, DROP COLUMN rank_id;');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}