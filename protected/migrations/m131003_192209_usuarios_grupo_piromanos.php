<?php

class m131003_192209_usuarios_grupo_piromanos extends CDbMigration
{

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->insert('group', array(
            'name'=>'Primeros creyentes',
            'active'=>1
        ));

        $this->insert('user', array(
            'username' 	=> 'fernando',
            'password' 	=> '$2a$13$m3r7U.S2eW2iV/fIAWmmAuOEmgnFINMt7sVfq8XdWfe35KIFigY8O',
            'alias'     => 'Fernando',
            'email'		=> 'mail@mail.com',
            'role'		=> 'user',
            'rank' 		=> 1,
            'ptos_tueste' => 1000,
            'experience'=>0,
            'status'	=> 1,
            'side'		=> 'kafhe',
            'group_id'	=> 1,
            'calls'     => 21,
            'times'     => 117,
            'birthdate' => '2013-10-20'
        ));
        $this->insert('user', array(
            'username' 	=> 'carlos',
            'password' 	=> '$2a$13$m3r7U.S2eW2iV/fIAWmmAuOEmgnFINMt7sVfq8XdWfe35KIFigY8O',
            'alias'     => 'Carlos',
            'email'		=> 'mail@mail.com',
            'role'		=> 'user',
            'rank' 		=> 1,
            'ptos_tueste' => 1000,
            'experience'=>0,
            'status'	=> 1,
            'side'		=> 'kafhe',
            'group_id'	=> 1,
            'calls'     => 25,
            'times'     => 115,
            'birthdate' => '2013-02-07'
        ));
        $this->insert('user', array(
            'username' 	=> 'miguel',
            'password' 	=> '$2a$13$m3r7U.S2eW2iV/fIAWmmAuOEmgnFINMt7sVfq8XdWfe35KIFigY8O',
            'alias'     => 'Miguel',
            'email'		=> 'mail@mail.com',
            'role'		=> 'user',
            'rank' 		=> 1,
            'ptos_tueste' => 1000,
            'experience'=>0,
            'status'	=> 1,
            'side'		=> 'achikhoria',
            'group_id'	=> 1,
            'calls'     => 27,
            'times'     => 115,
            'birthdate' => '2013-08-10'
        ));
        $this->insert('user', array(
            'username' 	=> 'juan',
            'password' 	=> '$2a$13$m3r7U.S2eW2iV/fIAWmmAuOEmgnFINMt7sVfq8XdWfe35KIFigY8O',
            'alias'     => 'Juan',
            'email'		=> 'mail@mail.com',
            'role'		=> 'user',
            'rank' 		=> 1,
            'ptos_tueste' => 1000,
            'experience'=>0,
            'status'	=> 1,
            'side'		=> 'achikhoria',
            'group_id'	=> 1,
            'calls'     => 12,
            'times'     => 71,
            'birthdate' => '2013-06-24'
        ));
        $this->insert('user', array(
            'username' 	=> 'jorge',
            'password' 	=> '$2a$13$m3r7U.S2eW2iV/fIAWmmAuOEmgnFINMt7sVfq8XdWfe35KIFigY8O',
            'alias'     => 'Jorge',
            'email'		=> 'mail@mail.com',
            'role'		=> 'user',
            'rank' 		=> 1,
            'ptos_tueste' => 1000,
            'experience'=>0,
            'status'	=> 1,
            'side'		=> 'kafhe',
            'group_id'	=> 1,
            'calls'     => 25,
            'times'     => 110,
            'birthdate' => '2013-11-21'
        ));
        $this->insert('user', array(
            'username' 	=> 'mangel',
            'password' 	=> '$2a$13$m3r7U.S2eW2iV/fIAWmmAuOEmgnFINMt7sVfq8XdWfe35KIFigY8O',
            'alias'     => 'Miguelo',
            'email'		=> 'mail@mail.com',
            'role'		=> 'user',
            'rank' 		=> 1,
            'ptos_tueste' => 1000,
            'experience'=>0,
            'status'	=> 1,
            'side'		=> 'achikhoria',
            'group_id'	=> 1,
            'calls'     => 9,
            'times'     => 68,
            'birthdate' => '2013-06-15'
        ));
        $this->insert('user', array(
            'username' 	=> 'manuel',
            'password' 	=> '$2a$13$m3r7U.S2eW2iV/fIAWmmAuOEmgnFINMt7sVfq8XdWfe35KIFigY8O',
            'alias'     => 'Manuel',
            'email'		=> 'mail@mail.com',
            'role'		=> 'user',
            'rank' 		=> 1,
            'ptos_tueste' => 1000,
            'experience'=>0,
            'status'	=> 1,
            'side'		=> 'kafhe',
            'group_id'	=> 1,
            'calls'     => 16,
            'times'     => 107,
            'birthdate' => '2013-06-06'
        ));
        $this->insert('user', array(
            'username' 	=> 'alejandro',
            'password' 	=> '$2a$13$m3r7U.S2eW2iV/fIAWmmAuOEmgnFINMt7sVfq8XdWfe35KIFigY8O',
            'alias'     => 'Alejandro',
            'email'		=> 'mail@mail.com',
            'role'		=> 'user',
            'rank' 		=> 1,
            'ptos_tueste' => 1000,
            'experience'=>0,
            'status'	=> 1,
            'side'		=> 'achikhoria',
            'group_id'	=> 1,
            'calls'     => 14,
            'times'     => 97,
            'birthdate' => '2013-12-13'
        ));
        $this->insert('user', array(
            'username' 	=> 'dani',
            'password' 	=> '$2a$13$m3r7U.S2eW2iV/fIAWmmAuOEmgnFINMt7sVfq8XdWfe35KIFigY8O',
            'alias'     => 'Dani',
            'email'		=> 'mail@mail.com',
            'role'		=> 'user',
            'rank' 		=> 1,
            'ptos_tueste' => 1000,
            'experience'=>0,
            'status'	=> 1,
            'side'		=> 'achikhoria',
            'group_id'	=> 1,
            'calls'     => 4,
            'times'     => 27,
            'birthdate' => '2013-10-30'
        ));
        $this->insert('user', array(
            'username' 	=> 'vero',
            'password' 	=> '$2a$13$m3r7U.S2eW2iV/fIAWmmAuOEmgnFINMt7sVfq8XdWfe35KIFigY8O',
            'alias'     => 'Vero',
            'email'		=> 'mail@mail.com',
            'role'		=> 'user',
            'rank' 		=> 1,
            'ptos_tueste' => 1000,
            'experience'=>0,
            'status'	=> 1,
            'side'		=> 'achikhoria',
            'group_id'	=> 1,
            'calls'     => 1,
            'times'     => 25,
            'birthdate' => '2013-02-25'
        ));
        $this->insert('user', array(
            'username' 	=> 'maria',
            'password' 	=> '$2a$13$m3r7U.S2eW2iV/fIAWmmAuOEmgnFINMt7sVfq8XdWfe35KIFigY8O',
            'alias'     => 'María',
            'email'		=> 'mail@mail.com',
            'role'		=> 'user',
            'rank' 		=> 1,
            'ptos_tueste' => 1000,
            'experience'=>0,
            'status'	=> 1,
            'side'		=> 'kafhe',
            'group_id'	=> 1,
            'calls'     => 1,
            'times'     => 11,
            'birthdate' => '2013-10-30'
        ));
        $this->insert('user', array(
            'username' 	=> 'leandro',
            'password' 	=> '$2a$13$m3r7U.S2eW2iV/fIAWmmAuOEmgnFINMt7sVfq8XdWfe35KIFigY8O',
            'alias'     => 'Leandro',
            'email'		=> 'mail@mail.com',
            'role'		=> 'user',
            'rank' 		=> 1,
            'ptos_tueste' => 1000,
            'experience'=>0,
            'status'	=> 1,
            'side'		=> 'kafhe',
            'group_id'	=> 1,
            'calls'     => 0,
            'times'     => 2,
            'birthdate' => '2013-01-01'
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
            'itemname'=>'Usuario',
            'userid'=>8,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Usuario',
            'userid'=>9,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Usuario',
            'userid'=>10,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Usuario',
            'userid'=>11,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Usuario',
            'userid'=>12,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'Usuario',
            'userid'=>13,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));

        $this->insert('authassignment', array(
            'itemname'=>'lanzar_evento',
            'userid'=>2,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));
        $this->insert('authassignment', array(
            'itemname'=>'lanzar_evento',
            'userid'=>3,
            'bizrule'=>NULL,
            'data'=>'N;'
        ));


        $this->insert('event', array(
            'group_id'=>1,
            'status'=>1,
            'type'=>'desayuno',
            'gungubos_population'=>1000,
            'gungubos_kafhe'=>0,
            'gungubos_achikhoria'=>0,
            'date'=>date('2013-10-11')
        ));

        $this->insert('notification', array(
            'message'=>'Amados fieles adoradores de la mermelada y la mantequilla, os doy la bienvenida a... ¡Kafhe 3.0!',
            'type'=>'omelettus',
            'timestamp' => '2013-10-07 00:00:01'
        ));
	}

	public function safeDown()
	{
        $this->delete('user', "username='carlos' OR username='fernando' OR username='jorge' OR username='alejandro' OR username='dani' OR username='juan' OR username='leandro' OR username='manuel' OR username='maria' OR username='miguel' OR username='mangel' OR username='vero'");
        $this->execute("TRUNCATE TABLE `group`;");
        $this->execute("TRUNCATE TABLE `event`;");
        $this->execute("TRUNCATE TABLE `notification`;");

        $this->delete('authassignment', "itemname!='Administrador'");
        //$this->delete('authitem', "name!='Administrador' AND name!='Invitado' AND name!='Usuario'");
	}

}