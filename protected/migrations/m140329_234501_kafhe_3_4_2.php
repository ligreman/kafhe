<?php

class m140329_234501_kafhe_3_4_2 extends CDbMigration
{
	public function safeUp()
	{
        $this->update('configuration', array('value'=>'15'), 'param=:param', array(':param'=>'gunguboZombieProbabilidadZombificar'));
        $this->update('configuration', array('value'=>'50'), 'param=:param', array(':param'=>'gunguboBombaProbabilidadEstallar'));
        $this->update('skill', array('description'=>'Evoluciona un Gungubo en un Gumbudo Nigromante.<br />Otorga puntos de fama al crear zombies en tu cementerio.'), 'keyword=:k', array(':k'=>'gumbudoNigromante'));
	}

	public function down()
	{
        $this->update('configuration', array('value'=>'30'), 'param=:param', array(':param'=>'gunguboZombieProbabilidadZombificar'));
        $this->update('configuration', array('value'=>'20'), 'param=:param', array(':param'=>'gunguboBombaProbabilidadEstallar'));
        $this->update('skill', array('description'=>'Evoluciona un Gungubo en un Gumbudo Nigromante.<br />Otorga puntos de fama al crear zombies en tu cementerio y al convertirlos en los corrales enemigos.'), 'keyword=:k', array(':k'=>'gumbudoNigromante'));
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