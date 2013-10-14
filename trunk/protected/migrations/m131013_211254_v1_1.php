<?php

class m131013_211254_v1_1 extends CDbMigration
{
	/*public function up()
	{
	}

	public function down()
	{
		echo "m131013_211254_v1_1 does not support migration down.\n";
		return false;
	}*/


	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	    $this->update('user', array(rank=>1), 'id=:id', array(':id'=>1));
	}

	public function safeDown()
	{
        $this->update('user', array(rank=>0), 'id=:id', array(':id'=>1));
	}

}