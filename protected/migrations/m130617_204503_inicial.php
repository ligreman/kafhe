<?php

class m130617_204503_inicial extends TXDbMigration
{
	public function safeUp()
	{
		/*$this->createTable('group', array(
            'id' 		=> 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name' 		=> 'VARCHAR(20) NOT NULL',
            'active' 	=> 'TINYINT(1) NOT NULL DEFAULT 0'
        ));

		$this->createTable('configuration', array(
            'id' 		=> 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'param' 	=> 'VARCHAR(50) NOT NULL',
            'value' 	=> 'VARCHAR(250) NOT NULL',
            'category' 	=> 'VARCHAR(50) NOT NULL'
        ));

		$this->createTable('rank', array(
            'id' 	=> 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'title' => 'VARCHAR(100) NOT NULL'
        ));

		$this->createTable('notification', array(
            'id' 					=> 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'sender' 				=> 'INT NOT NULL',
            'recipient_original' 	=> 'INT NOT NULL',
            'recipient_final' 		=> 'INT NOT NULL',
            'message' 				=> 'TEXT NOT NULL',
            'timestamp'			 	=> 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'type' 					=> "ENUM('kafhe','achikhoria','omelettus','system') NOT NULL",
			'read' 					=> 'TINYINT(1) NOT NULL DEFAULT 0'
        ));

		$this->createTable('event', array(
            'id' 				=> 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'group_id' 			=> 'INT NOT NULL',
            'caller_id' 		=> 'INT NOT NULL',
            'relauncher_id' 	=> 'INT NOT NULL',
            'open' 				=> 'TINYINT(1) NOT NULL DEFAULT 0',
            'date' 				=> 'DATE NOT NULL'
        ));

		$this->createTable('order', array(
            'id' 		=> 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'user_id' 	=> 'INT NOT NULL',
            'event_id' 	=> 'INT NOT NULL',
            'meal_id' 	=> 'INT NOT NULL',
            'drink_id' 	=> 'INT NOT NULL',
            'ito' 		=> 'TINYINT(1) NOT NULL DEFAULT 0',
            'timestamp' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ));

		$this->createTable('meal', array(
            'id' 	=> 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name' 	=> 'VARCHAR(50) NOT NULL',
            'price' => 'INT NOT NULL',
            'ito' 	=> 'TINYINT(1) NOT NULL DEFAULT 0'
        ));

		$this->createTable('drink', array(
            'id' 	=> 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name' 	=> 'VARCHAR(50) NOT NULL',
            'price' => 'INT NOT NULL',
            'ito' 	=> 'TINYINT(1) NOT NULL DEFAULT 0'
        ));
        
        $this->createTable('user', array(
        	'id' 		=> 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'username' 	=> 'VARCHAR(128) NOT NULL',
            'password' 	=> 'VARCHAR(128) NOT NULL',        	
            'email'		=> 'VARCHAR(128) NOT NULL',
        	'birthdate'	=> 'DATE',
        	'role' 		=> "ENUM ('admin','moderator','user') NOT NULL DEFAULT 'user'",
        	'group_id'	=> 'INT UNSIGNED DEFAULT NULL',
        	'side' 		=> "ENUM ('kafhe','achikhoria') DEFAULT NULL",
        	'rank'	=> 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
        	'ptos_tueste'			=> 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
        	'ptos_retueste'			=> 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
        	'ptos_relanzamiento'	=> 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
        	'ptos_talentos'			=> 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
        	'tostolares'			=> 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
        	'azucarillos'			=> 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
        	'dominio_tueste'		=> 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
        	'dominio_habilidades'	=> 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
        	'dominio_bandos'		=> 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
        	'times'					=> 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
        	'calls'					=> 'SMALLINT UNSIGNED NOT NULL DEFAULT 0'
        ));*/

        $this->executeFile('data/carga_incial.sql');

        $this->execute("INSERT INTO authitem VALUES ('Administrador',2,NULL,NULL,'N;'),('Usuario','2',NULL,NULL,'N;'),('Invitado',2,NULL,NULL,'N;');");
        $this->execute("INSERT INTO authassignment VALUES ('Administrador',1,NULL,'N;');");
		
		$this->insert('authitem', array(
            'name'=>'lanzar_evento',
            'type'=>0,
            'description'=>'Iniciar la batalla de un evento',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));

        /*$this->insert('authassignment', array(
            'itemname'=>'Admin',
            'userid'=>'1',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));

        $this->insert('authitem', array(
            'name'=>'Admin',
            'type'=>'2',
            'description'=>NULL,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authitem', array(
            'name'=>'Authenticated',
            'type'=>'2',
            'description'=>NULL,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authitem', array(
            'name'=>'Guest',
            'type'=>'2',
            'description'=>NULL,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));*/

    }
 
	public function safeDown()
	{
        $this->dropTable('configuration');
        $this->dropTable('drink');
        $this->dropTable('enrollment');
        $this->dropTable('event');
        $this->dropTable('group');
        $this->dropTable('meal');
        $this->dropTable('modifier');
        $this->dropTable('notification');
        $this->dropTable('rank');
        $this->dropTable('ranking');
        $this->dropTable('skill');
        $this->dropTable('user');
        $this->dropTable('cronpile');
        $this->dropTable('history_skill_execution');

        $this->dropTable('authassignment');
        $this->dropTable('authitemchild');
        $this->dropTable('rights');
        $this->dropTable('authitem');
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