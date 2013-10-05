<?php

class m131003_192140_meals_and_drinks extends CDbMigration
{

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->insert('meal', array(
            'name'=>'Croissant',
            'type'=>'bolleria', //'tostada','pulga','bolleria','otro', tortilla
            'ito'=>0
        ));
        $this->insert('meal', array(
            'name'=>'Croissant a la plancha',
            'type'=>'bolleria', //'tostada','pulga','bolleria','otro', tortilla
            'ito'=>0
        ));
        $this->insert('meal', array(
            'name'=>'Donut',
            'type'=>'bolleria', //'tostada','pulga','bolleria','otro', tortilla
            'ito'=>0
        ));
        $this->insert('meal', array(
            'name'=>'Tostada de molde',
            'type'=>'tostada', //'tostada','pulga','bolleria','otro', tortilla
            'ito'=>0
        ));
        $this->insert('meal', array(
            'name'=>'Pincho de tortilla',
            'type'=>'tortilla', //'tostada','pulga','bolleria','otro', tortilla
            'ito'=>0
        ));
        $this->insert('meal', array(
            'name'=>'Pincho de tortilla vegetal',
            'type'=>'tortilla', //'tostada','pulga','bolleria','otro', tortilla
            'ito'=>0
        ));
        $this->insert('meal', array(
            'name'=>'Pincho de tortilla ali-oli',
            'type'=>'tortilla', //'tostada','pulga','bolleria','otro'
            'ito'=>0
        ));
        $this->insert('meal', array(
            'name'=>'Pulga de lomo y queso',
            'type'=>'pulga', //'tostada','pulga','bolleria','otro'
            'ito'=>1
        ));
        $this->insert('meal', array(
            'name'=>'Pulga vegetal',
            'type'=>'pulga', //'tostada','pulga','bolleria','otro'
            'ito'=>1
        ));
        $this->insert('meal', array(
            'name'=>'Pulga de bacon y queso',
            'type'=>'pulga', //'tostada','pulga','bolleria','otro'
            'ito'=>1
        ));
        $this->insert('meal', array(
            'name'=>'Pulga de salmón y philadelphia',
            'type'=>'pulga', //'tostada','pulga','bolleria','otro'
            'ito'=>1
        ));
        $this->insert('meal', array(
            'name'=>'Pulga de tortilla',
            'type'=>'pulga', //'tostada','pulga','bolleria','otro'
            'ito'=>1
        ));
        $this->insert('meal', array(
            'name'=>'Pulga de pollo y lechuga',
            'type'=>'pulga', //'tostada','pulga','bolleria','otro'
            'ito'=>1
        ));
        $this->insert('meal', array(
            'name'=>'Pulga de pollo y queso',
            'type'=>'pulga', //'tostada','pulga','bolleria','otro'
            'ito'=>1
        ));
        $this->insert('meal', array(
            'name'=>'Pulga de atún',
            'type'=>'pulga', //'tostada','pulga','bolleria','otro'
            'ito'=>1
        ));
        $this->insert('meal', array(
            'name'=>'Pulga de pollo y pimiento',
            'type'=>'pulga', //'tostada','pulga','bolleria','otro'
            'ito'=>1
        ));

        $this->insert('drink', array(
            'name'=>'Manzanilla',
            'type'=>'infusion', //'cafe','infusion','zumo','otro'
            'ito'=>0
        ));
        $this->insert('drink', array(
            'name'=>'Té con leche',
            'type'=>'infusion', //'cafe','infusion','zumo','otro'
            'ito'=>1
        ));
        $this->insert('drink', array(
            'name'=>'Menta poleo',
            'type'=>'infusion', //'cafe','infusion','zumo','otro'
            'ito'=>0
        ));
        $this->insert('drink', array(
            'name'=>'Té',
            'type'=>'infusion', //'cafe','infusion','zumo','otro'
            'ito'=>1
        ));
        $this->insert('drink', array(
            'name'=>'Zumo de melocotón',
            'type'=>'zumo', //'cafe','infusion','zumo','otro'
            'ito'=>0
        ));
        $this->insert('drink', array(
            'name'=>'Zumo de piña',
            'type'=>'zumo', //'cafe','infusion','zumo','otro'
            'ito'=>0
        ));
        $this->insert('drink', array(
            'name'=>'Zumo de pera',
            'type'=>'zumo', //'cafe','infusion','zumo','otro'
            'ito'=>0
        ));
        $this->insert('drink', array(
            'name'=>'Zumo de naranja',
            'type'=>'zumo', //'cafe','infusion','zumo','otro'
            'ito'=>0
        ));
        $this->insert('drink', array(
            'name'=>'Batido de chocolate',
            'type'=>'otro', //'cafe','infusion','zumo','otro'
            'ito'=>0
        ));
        $this->insert('drink', array(
            'name'=>'Descafeinado de máquina',
            'type'=>'cafe', //'cafe','infusion','zumo','otro'
            'ito'=>1
        ));
        $this->insert('drink', array(
            'name'=>'Descafeinado de sobre',
            'type'=>'cafe', //'cafe','infusion','zumo','otro'
            'ito'=>1
        ));
        $this->insert('drink', array(
            'name'=>'Café cortado',
            'type'=>'cafe', //'cafe','infusion','zumo','otro'
            'ito'=>1
        ));
        $this->insert('drink', array(
            'name'=>'Café solo',
            'type'=>'cafe', //'cafe','infusion','zumo','otro'
            'ito'=>1
        ));
        $this->insert('drink', array(
            'name'=>'Café solo grande',
            'type'=>'cafe', //'cafe','infusion','zumo','otro'
            'ito'=>0
        ));
        $this->insert('drink', array(
            'name'=>'Café solo con hielo',
            'type'=>'cafe', //'cafe','infusion','zumo','otro'
            'ito'=>1
        ));
        $this->insert('drink', array(
            'name'=>'Café con leche',
            'type'=>'cafe', //'cafe','infusion','zumo','otro'
            'ito'=>1
        ));
        $this->insert('drink', array(
            'name'=>'Café con leche con hielo',
            'type'=>'cafe', //'cafe','infusion','zumo','otro'
            'ito'=>1
        ));
        $this->insert('drink', array(
            'name'=>'Café ',
            'type'=>'cafe', //'cafe','infusion','zumo','otro'
            'ito'=>1
        ));

	}

	public function safeDown()
	{
        $this->execute("TRUNCATE TABLE meal;");
        $this->execute("TRUNCATE TABLE drink;");
	}

}