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
			'side'		=> 'kafhe',			
			'group_id'	=> 1
		));
		
		$this->insert('user', array(
			'username' 	=> 'test1',
			'password' 	=> '$2a$10$81oSkBDpQatWYoPap0aInOCUybJfPL1p6NXZm42ZxYsmneKtGEKhC',
			'alias' => 'Test 1',
			'email'		=> 'test1@mail.com',
			'role'		=> 'user',
			'side'		=> 'kafhe',
			'ptos_tueste' => 10000,
			'rank' 		=> 1,
			'group_id'	=> 1
		));
		$this->insert('user', array(
			'username' 	=> 'test2',
			'password' 	=> '$2a$10$SIDNO6m56LuuYHjNT0ixjefdzPp99vd3PAnDqNoqA0gukVycYZ3He',
			'alias' => 'Test 2',
			'email'		=> 'test2@mail.com',
			'role'		=> 'user',
			'side'		=> 'achikhoria',
			'rank' 		=> 1,
			'group_id'	=> 1
		));
		$this->insert('user', array(
			'username' 	=> 'test3',
			'password' 	=> '$2a$10$Up91j4aFbHqRJnWe09YDVeRY8sYARCIKNBXzgqJvEBtY386G8aaVC',
			'alias' => 'Test 3',
			'email'		=> 'test3@mail.com',
			'role'		=> 'user',
			'side'		=> 'kafhe',
			'rank' 		=> 2,
			'group_id'	=> 1
		));
		$this->insert('user', array(
			'username' 	=> 'test4',
			'password' 	=> '$2a$10$hjq5EPsndr1/Hifbk3P1MOnAz/nsFNSobYhRz8ZzNv9l1QfYtS1yO',
			'alias' => 'Test 4',
			'email'		=> 'test4@mail.com',
			'role'		=> 'user',
			'side'		=> 'achikhoria',
			'rank' 		=> 3,
			'group_id'	=> 1
		));
		$this->insert('user', array(
			'username' 	=> 'test5',
			'password' 	=> '$2a$10$wRFm.wMwXzGoXj80cMlw2.NDRzTGn/KZHHfeTNwWLGsBcXx9Cw/Ni',
			'alias' => 'Test 5',
			'email'		=> 'test5@mail.com',
			'role'		=> 'user',
			'side'		=> 'achikhoria',
			'rank' 		=> 1,
			'group_id'	=> 1
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
            'status'=>1,
            'type'=>'desayuno',
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

		$this->insert('notification', array(
            'sender'=>2,
            'recipient_original'=>3,
			'recipient_final'=>3,
            'message'=>'Hola caracola!',
            'type'=>'kafhe'
        ));
		$this->insert('notification', array(
            'sender'=>1,
            'recipient_original'=>3,
			'recipient_final'=>3,
            'message'=>'Omeletus dice',
            'type'=>'omelettus'
        ));

        $this->execute("INSERT INTO `skill` (`name`, `description`, `category`, `type`, `keyword`, `duration`, `duration_type`, `critic`, `fail`, `cost_tueste`, `cost_retueste`, `cost_relanzamiento`, `cost_tostolares`, `is_cooperative`, `cost_tueste_cooperate`, `cost_tostolares_cooperate`, `cooperate_benefit`, `require_target`, `require_caller`, `require_target_side`, `require_user_side`, `require_user_min_rank`, `require_user_status`, `talent_id_required`) VALUES('Hidratar', 'Te hidratas', 'batalla', 'mejora', 'hidratar', 1, 'horas', 10, 15, 20, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL);");
        $this->execute("INSERT INTO `skill` (`name`, `description`, `category`, `type`, `keyword`, `duration`, `duration_type`, `critic`, `fail`, `cost_tueste`, `cost_retueste`, `cost_relanzamiento`, `cost_tostolares`, `is_cooperative`, `cost_tueste_cooperate`, `cost_tostolares_cooperate`, `cooperate_benefit`, `require_target`, `require_caller`, `require_target_side`, `require_user_side`, `require_user_min_rank`, `require_user_status`, `talent_id_required`) VALUES ('Disimular', 'Disimulas', 'batalla', 'utilidad', 'disimular', 1, 'usos', 5, 10, 10, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL);");

	}

	public function safeDown()
	{
		$this->delete('user', "username='test1' OR username='test2' OR username='test3' OR username='test4' OR username='test5' OR username='mod'");

		$this->delete('authassignment', "itemname='Usuario' OR itemname='operacion' OR itemname='task1' OR itemname='task2'");
        $this->delete('authitem', "name='operacion' OR name='task1' OR name='task2'");

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