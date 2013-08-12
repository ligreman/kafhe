<?php

class m130620_205405_dummy_data extends CDbMigration
{
	public function safeUp()
	{
		/*$this->insert('user', array(
			'username' 	=> 'admin',
			'password' 	=> '$2a$10$lEkw/VyX4WJOpJrhKAqkoeQvI/ugLjJTmqutbSNKHnL3ysamayGYe',
			'email'		=> 'admin@mail.com',
			'role'		=> 'admin',			
			'group_id'	=> 0
		));*/
		$this->insert('user', array(
			'username' 	=> 'mod',
			'password' 	=> '$2a$10$3z0vZnmZxN0tE49uV0J8Ju5udzy3fkbFETVoSOxmlCTEli3R5mlsW',
			'alias' => 'Moderador',
			'email'		=> 'mod@mail.com',
			'role'		=> 'user',
            'rank' 		=> 1,
            'experience'=>0,
			'status'	=> 2,
			'side'		=> 'kafhe',			
			'group_id'	=> 1,
			'last_notification_read' => '2013-08-08 12:20:38'
		));
		
		$this->insert('user', array(
			'username' 	=> 'test1',
			'password' 	=> '$2a$10$81oSkBDpQatWYoPap0aInOCUybJfPL1p6NXZm42ZxYsmneKtGEKhC',
			'alias' => 'Test 1',
			'email'		=> 'test1@mail.com',
			'role'		=> 'user',
			'side'		=> 'kafhe',
			'ptos_tueste' => 990,
			'rank' 		=> 1,
            'experience'=>0,
			'status'	=> 2,
			'group_id'	=> 1,
			'last_notification_read' => '2013-08-08 12:20:38'
		));
		$this->insert('user', array(
			'username' 	=> 'test2',
			'password' 	=> '$2a$10$SIDNO6m56LuuYHjNT0ixjefdzPp99vd3PAnDqNoqA0gukVycYZ3He',
			'alias' => 'Test 2',
			'email'		=> 'test2@mail.com',
			'role'		=> 'user',
			'side'		=> 'achikhoria',
            'ptos_tueste' => 1000,
			'rank' 		=> 1,
			'experience'=>0,
			'status'	=> 2,
			'group_id'	=> 1,
			'last_notification_read' => '2013-08-08 12:20:38'
		));
		$this->insert('user', array(
			'username' 	=> 'test3',
			'password' 	=> '$2a$10$Up91j4aFbHqRJnWe09YDVeRY8sYARCIKNBXzgqJvEBtY386G8aaVC',
			'alias' => 'Test 3',
			'email'		=> 'test3@mail.com',
			'role'		=> 'user',
			'side'		=> 'kafhe',
            'ptos_tueste' => 1000,
			'rank' 		=> 2,
            'experience'=>59,
			'status'	=> 2,
			'group_id'	=> 1,
			'last_notification_read' => '2013-08-08 12:20:38'
		));
		$this->insert('user', array(
			'username' 	=> 'test4',
			'password' 	=> '$2a$10$hjq5EPsndr1/Hifbk3P1MOnAz/nsFNSobYhRz8ZzNv9l1QfYtS1yO',
			'alias' => 'Test 4',
			'email'		=> 'test4@mail.com',
			'role'		=> 'user',
			'side'		=> 'achikhoria',
            'ptos_tueste' => 1000,
			'rank' 		=> 3,
            'experience'=>0,
			'status'	=> 2,
			'group_id'	=> 1,
			'last_notification_read' => '2013-08-08 12:20:38'
		));
		$this->insert('user', array(
			'username' 	=> 'test5',
			'password' 	=> '$2a$10$wRFm.wMwXzGoXj80cMlw2.NDRzTGn/KZHHfeTNwWLGsBcXx9Cw/Ni',
			'alias' => 'Test 5',
			'email'		=> 'test5@mail.com',
			'role'		=> 'user',
			'side'		=> 'libre',
            'ptos_tueste' => 1000,
            'experience'=>0,
			'rank' 		=> 1,
			'status'	=> 5,
			'group_id'	=> 1,
			'last_notification_read' => '2013-08-08 12:20:38'
		));



        $this->insert('authitem', array(
            'name'=>'operacion',
            'type'=>0,
            'description'=>'Operación de prueba',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authitem', array(
            'name'=>'task1',
            'type'=>1,
            'description'=>'Task de prueba 1',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authitem', array(
            'name'=>'task2',
            'type'=>1,
            'description'=>'Task de prueba 2',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));

		$this->insert('authassignment', array(
            'itemname'=>'Usuario',
            'userid'=>2,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Usuario',
            'userid'=>3,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Usuario',
            'userid'=>4,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Usuario',
            'userid'=>5,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Usuario',
            'userid'=>6,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Usuario',
            'userid'=>7,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
		$this->insert('authassignment', array(
            'itemname'=>'operacion',
            'userid'=>1,
            'bizrule'=>NULL,
            'data'=>'N;'
		));
        $this->insert('authassignment', array(
            'itemname'=>'task1',
            'userid'=>1,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'task2',
            'userid'=>4,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
		$this->insert('authassignment', array(
            'itemname'=>'lanzar_evento',
            'userid'=>2,
            'bizrule'=>NULL,
            'data'=>'N;'
		));
		
		$this->insert('group', array(
            'name'=>'Los Sobaos',
            'active'=>1
        ));


		$this->insert('event', array(
            'group_id'=>1,
            'status'=>0,
			'caller_id'=>7,
			'caller_side'=>'kafhe',
            'type'=>'desayuno',
			'gungubos_kafhe'=>150,
			'gungubos_achikhoria'=>200,
            'date'=>date('2013-05-20')
        ));
		$this->insert('event', array(
            'group_id'=>1,
            'status'=>1,
            'type'=>'desayuno',
			'gungubos_kafhe'=>150,
			'gungubos_achikhoria'=>200,
            'date'=>date('Y-m-d')
        ));

        $this->insert('meal', array(
            'name'=>'Patata',
            'type'=>'tostada',
            'ito'=>0
        ));
        $this->insert('meal', array(
            'name'=>'Perrito Frío',
            'type'=>'pulga',
            'ito'=>1
        ));
        $this->insert('drink', array(
            'name'=>'Zumo de alcachofa',
            'type'=>'zumo',
            'ito'=>1
        ));
        $this->insert('drink', array(
            'name'=>'Té azul',
            'type'=>'infusion',
            'ito'=>1
        ));
		
		$this->insert('enrollment', array(
			'user_id'=>2,
			'event_id'=>2,
			'meal_id'=>2,
			'drink_id'=>2,            
            'ito'=>0
        ));
		$this->insert('enrollment', array(
			'user_id'=>3,
			'event_id'=>2,
			'meal_id'=>NULL,
			'drink_id'=>1,            
            'ito'=>0
        ));
		$this->insert('enrollment', array(
			'user_id'=>4,
			'event_id'=>2,
			'meal_id'=>1,
			'drink_id'=>1,            
            'ito'=>0
        ));
		$this->insert('enrollment', array(
			'user_id'=>5,
			'event_id'=>2,
			'meal_id'=>2,
			'drink_id'=>2,            
            'ito'=>1
        ));
		$this->insert('enrollment', array(
			'user_id'=>6,
			'event_id'=>2,
			'meal_id'=>1,
			'drink_id'=>NULL,            
            'ito'=>0
        ));
		$this->insert('enrollment', array(
			'user_id'=>7,
			'event_id'=>2,
			'meal_id'=>2,
			'drink_id'=>2,            
            'ito'=>0
        ));

		$this->insert('notification', array(
            'sender'=>2,
            'recipient_original'=>3,
			'recipient_final'=>3,
            'message'=>'Hola caracola!',
            'type'=>'kafhe',
			'timestamp' => '2013-08-08 11:20:38'
        ));
		$this->insert('notification', array(
            'sender'=>1,
            'recipient_original'=>3,
			'recipient_final'=>3,
            'message'=>'Omeletus dice',
            'type'=>'omelettus',
			'timestamp' => '2013-08-08 11:20:38'
        ));
		$this->insert('notification', array(
            'sender'=>4,
            'recipient_original'=>3,
			'recipient_final'=>3,
            'message'=>'Notificación nueva',
            'type'=>'achikhoria',
			'timestamp' => '2013-08-08 13:20:38'
        ));

        $this->execute("INSERT INTO `skill` (`name`, `description`, `category`, `type`, `keyword`, `modifier_keyword`, `duration`, `duration_type`, `critic`, `fail`, `cost_tueste`, `cost_retueste`, `cost_relanzamiento`, `cost_tostolares`, `is_cooperative`, `cost_tueste_cooperate`, `cost_tostolares_cooperate`, `cooperate_benefit`, `require_target`, `require_caller`, `require_target_side`, `require_user_side`, `require_user_min_rank`, `require_user_status`, `require_event_status`, `talent_id_required`) VALUES ('Hidratar', 'Te hidratas', 'batalla', 'mejora', 'hidratar', 'hidratado', 1, 'horas', 10, 15, 20, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, NULL);");
        $this->execute("INSERT INTO `skill` (`name`, `description`, `category`, `type`, `keyword`, `modifier_keyword`, `duration`, `duration_type`, `critic`, `fail`, `cost_tueste`, `cost_retueste`, `cost_relanzamiento`, `cost_tostolares`, `is_cooperative`, `cost_tueste_cooperate`, `cost_tostolares_cooperate`, `cooperate_benefit`, `require_target`, `require_caller`, `require_target_side`, `require_user_side`, `require_user_min_rank`, `require_user_status`, `require_event_status`, `talent_id_required`) VALUES ('Disimular', 'Disimulas', 'batalla', 'utilidad', 'disimular', 'disimulando', 1, 'usos', 5, 10, 10, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, NULL);");
		$this->execute("INSERT INTO `skill` (`name`, `description`, `category`, `type`, `keyword`, `modifier_keyword`, `duration`, `duration_type`, `critic`, `fail`, `cost_tueste`, `cost_retueste`, `cost_relanzamiento`, `cost_tostolares`, `is_cooperative`, `cost_tueste_cooperate`, `cost_tostolares_cooperate`, `cooperate_benefit`, `require_target`, `require_target_side`, `require_caller`, `require_user_side`, `require_user_min_rank`, `require_user_status`, `require_event_status`, `talent_id_required`) VALUES ('Cazar gungubos', 'Caza gungubos salvajes', 'gungubos', 'utilidad', 'cazarGungubos', '', '0', 'horas', '5', '5', '12', NULL, NULL, NULL, '0', NULL, NULL, NULL, '0', NULL, '0', NULL, '0', NULL, NULL, NULL);");
		$this->execute("INSERT INTO `skill` (`name`, `description`, `category`, `type`, `keyword`, `modifier_keyword`, `duration`, `duration_type`, `critic`, `fail`, `cost_tueste`, `cost_retueste`, `cost_relanzamiento`, `cost_tostolares`, `is_cooperative`, `cost_tueste_cooperate`, `cost_tostolares_cooperate`, `cooperate_benefit`, `require_target`, `require_target_side`, `require_caller`, `require_user_side`, `require_user_min_rank`, `require_user_status`, `require_event_status`, `talent_id_required`) VALUES ('Escaquearse', 'Te escaqueas de llamar', 'relanzamiento', 'mejora', 'escaquearse', '', '0', 'horas', '10', '10', NULL, NULL, '1', NULL, '0', NULL, NULL, NULL, '0', NULL, '1', NULL, '0', NULL, '2', NULL);");
	}

	public function safeDown()
	{
		$this->delete('user', "username='test1' OR username='test2' OR username='test3' OR username='test4' OR username='test5' OR username='mod'");

		$this->delete('authassignment', "itemname!='Administrador'");
        $this->delete('authitem', "name!='Administrador' AND name!='Invitado' AND name!='Usuario'");

		$this->delete('event', "1=1");
		$this->delete('group', "1=1");
        $this->delete('meal', "1=1");
        $this->delete('drink', "1=1");
        $this->delete('notification', "1=1");
        $this->delete('skill', "1=1");
        $this->delete('modifier', "1=1");
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