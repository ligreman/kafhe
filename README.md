# KAFHE

- [What is it?](#what-is-it)
- [Features](#features)
- [Installation](#installation)
- [Other documentation](#other-documentation)

## What is it?

Kafhe is a game web app made to gamify the organization of the costs of breakfasts, meals, and events among friends. 

Suppose that next weekend you are going to eat with your friends, and someone has to take care of booking a restaurant. You're tired of always being the one in charge of doing it. Kafhe is here to help you!

You and your friend will play to decide whose turn it is to organize the meal this time.

## Features

![Frontend](https://raw.githubusercontent.com/ligreman/kafhe/master/images/Interfaz.png)

	1- Register you and your friends.
	2- Accumulate power to fill your bar.
	3- Follow the event registry to stay up to date with the latest news in the battlefield.
	4- Receive notifications and updates from your army.
	5- Create gungubos.
	6- Use skills.
	7-9 Accumulate special energy to deliver strong attacks and effects.

### Choose a side

**Kafhe**

![Kafhe side](https://raw.githubusercontent.com/ligreman/kafhe/master/images/modifiers/kafhe.png) Kafhe followers are the ones that side with the breakfast force.

**Achikhoria**

![Achikoria side](https://raw.githubusercontent.com/ligreman/kafhe/master/images/modifiers/achikhoria.png) Achikhoria, the god of fasting, the force opposed to breakfast.

### Rise your creatures

You can create Gungubos. They are strange creatures with different powers that helps you in your mission to avoid being the one chosen. Attack and defend yourself with the help of Gungubos. There a lot of different classes!

**Artificer**

![Artificer](https://raw.githubusercontent.com/ligreman/kafhe/master/images/bestiary/artificiero.png)

**Warrior**

![Warrior](https://raw.githubusercontent.com/ligreman/kafhe/master/images/bestiary/asaltante.png)

**Siege**

![Siege](https://raw.githubusercontent.com/ligreman/kafhe/master/images/bestiary/asedio.png)

**Bomb**

![Bomber](https://raw.githubusercontent.com/ligreman/kafhe/master/images/bestiary/bomba.png)

**Breeder**

![Breeder](https://raw.githubusercontent.com/ligreman/kafhe/master/images/bestiary/criador.png)

**Guardian**

![Guardian](https://raw.githubusercontent.com/ligreman/kafhe/master/images/bestiary/guardian.png)

**Rocker**

![Rock](https://raw.githubusercontent.com/ligreman/kafhe/master/images/bestiary/gumbudo.png)

**Necromancer**

![Necromancer](https://raw.githubusercontent.com/ligreman/kafhe/master/images/bestiary/nigromante.png)

And many more!!!

### Accumulate power and use skills

![Dehydrate](https://raw.githubusercontent.com/ligreman/kafhe/master/images/skills/desecar.png) Dehydrate: empty your enemy power bar.

![Hunt](https://raw.githubusercontent.com/ligreman/kafhe/master/images/skills/cazarGungubos.png) Hunt: capture some gungubos to join your forces.

![Rescue](https://raw.githubusercontent.com/ligreman/kafhe/master/images/skills/rescatarGungubos.png) Rescue: rescue and set free some gungubos from an enemy.

There are more than 20 different skills.

## Installation

Kafhe is made with [Yii Framework](https://www.yiiframework.com/). You need two components to install it:

	* Apache + PHP
	* MySQL

### Apache web server

You must enable the these modules:
  * PHP
  * MOD_REWRITE
  
#### Deploying the webapp

Just unzip the zip with the source code (or Release) into the document root folder (eg. /var/www/html/).

Then you must configure the database. Edit /protected/config/main.php file and fill the values:

```
'db'=>array(
	'connectionString' => 'mysql:host=localhost;dbname=kafhe',
	'username' => 'kafhe', //#mysqlUsername
	'password' => '', //#mysqlPassword
	'charset' => 'utf8',
),
```

### MySQL database

* Min version required: 5.7.X
* Create a database called kafhe
* Create a db user called kafhe and the password of your choice, with permissions to read and write in that database.

#### Initial data loading

After creating the database and the user you must proceed to load the initial data of the project. To do this, load the initial migration. In a system console we go to the project's protect directory and write:
`yiic migrate`

It will ask us for confirmation of the initial migration. We give it yes and it should create the entire structure in the database.

Note: For this you must have the php in the system PATH environment variable. If it is not there, you must add it, adding the path of the php folder within xampp to the environment variable.

## Other documentation

[Code of Conduct](https://github.com/ligreman/kafhe/blob/master/CODE_OF_CONDUCT.md)
