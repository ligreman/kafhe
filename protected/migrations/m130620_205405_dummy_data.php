<?php

class m130620_205405_dummy_data extends CDbMigration
{
	public function up()
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
			'role'		=> 'moderator',
			'group_id'	=> 1
		));
		
		$this->insert('user', array(
			'username' 	=> 'test1',
			'password' 	=> '$2a$10$81oSkBDpQatWYoPap0aInOCUybJfPL1p6NXZm42ZxYsmneKtGEKhC',
			'alias' => 'Test 1',
			'email'		=> 'test1@mail.com',
			'role'		=> 'user',
			'group_id'	=> 1
		));
		$this->insert('user', array(
			'username' 	=> 'test2',
			'password' 	=> '$2a$10$SIDNO6m56LuuYHjNT0ixjefdzPp99vd3PAnDqNoqA0gukVycYZ3He',
			'alias' => 'Test 2',
			'email'		=> 'test2@mail.com',
			'role'		=> 'user',
			'group_id'	=> 1
		));
		$this->insert('user', array(
			'username' 	=> 'test3',
			'password' 	=> '$2a$10$Up91j4aFbHqRJnWe09YDVeRY8sYARCIKNBXzgqJvEBtY386G8aaVC',
			'alias' => 'Test 3',
			'email'		=> 'test3@mail.com',
			'role'		=> 'user',
			'group_id'	=> 1
		));
		$this->insert('user', array(
			'username' 	=> 'test4',
			'password' 	=> '$2a$10$hjq5EPsndr1/Hifbk3P1MOnAz/nsFNSobYhRz8ZzNv9l1QfYtS1yO',
			'alias' => 'Test 4',
			'email'		=> 'test4@mail.com',
			'role'		=> 'user',
			'group_id'	=> 1
		));
		$this->insert('user', array(
			'username' 	=> 'test5',
			'password' 	=> '$2a$10$wRFm.wMwXzGoXj80cMlw2.NDRzTGn/KZHHfeTNwWLGsBcXx9Cw/Ni',
			'alias' => 'Test 5',
			'email'		=> 'test5@mail.com',
			'role'		=> 'user',
			'group_id'	=> 1
		));



        $this->insert('authitem', array(
            'name'=>'operacion',
            'type'=>'0',
            'description'=>'Operación de prueba',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authitem', array(
            'name'=>'task1',
            'type'=>'1',
            'description'=>'Task de prueba 1',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authitem', array(
            'name'=>'task2',
            'type'=>'1',
            'description'=>'Task de prueba 2',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));

        $this->insert('authassignment', array(
            'itemname'=>'Authenticated',
            'userid'=>'3',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Authenticated',
            'userid'=>'4',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Authenticated',
            'userid'=>'5',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Authenticated',
            'userid'=>'6',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Authenticated',
            'userid'=>'7',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
		$this->insert('authassignment', array(
            'itemname'=>'operacion',
            'userid'=>'1',
            'bizrule'=>NULL,
            'data'=>'N;'
		));
        $this->insert('authassignment', array(
            'itemname'=>'task1',
            'userid'=>'1',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'task2',
            'userid'=>'4',
            'bizrule'=>NULL,
            'data'=>'N;'
        ));


		$this->insert('event', array(
            'group_id'=>'1',
            'status'=>'1',
            'type'=>'desayuno',
            'date'=>date('Y-m-d')
        ));

        $this->insert('meal', array(
            'name'=>'Patata',
            'type'=>'tostada',
            'ito'=>'0'
        ));
        $this->insert('meal', array(
            'name'=>'Perrito Frío',
            'type'=>'pulga',
            'ito'=>'1'
        ));
        $this->insert('drink', array(
            'name'=>'Zumo de alcachofa',
            'type'=>'zumo',
            'ito'=>'1'
        ));
        $this->insert('drink', array(
            'name'=>'Té azul',
            'type'=>'infusion',
            'ito'=>'1'
        ));

		$this->insert('notification', array(
            'sender'=>'2',
            'recipient_original'=>'3',
			'recipient_final'=>'3',
            'message'=>'Hola caracola!',
            'type'=>'kafhe'
        ));
		$this->insert('notification', array(
            'sender'=>'1',
            'recipient_original'=>'3',
			'recipient_final'=>'3',
            'message'=>'Omeletus dice',
            'type'=>'omelettus'
        ));

	}

	public function down()
	{
		$this->delete('user', "username='test1' OR username='test2' OR username='test3' OR username='test4' OR username='test5' OR username='mod'");

		$this->delete('authassignment', "itemname='Authenticated' OR itemname='operacion' OR itemname='task1' OR itemname='task2'");
        $this->delete('authitem', "name='operacion' OR name='task1' OR name='task2'");

		$this->delete('event', "group_id='1'");
        $this->delete('meal', "1=1");
        $this->delete('drink', "1=1");
        $this->delete('notification', "1=1");
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