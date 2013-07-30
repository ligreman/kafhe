<?php
/* @var $this CharacterController */

$this->breadcrumbs=array(
	'Character'=>array('/character'),
	'Skills',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php
$flashMessages = Yii::app()->user->getFlashes();
if ($flashMessages) {
    echo '<ul class="flashes">';
    foreach($flashMessages as $key => $message) {
        echo '<li><div class="flash-' . $key . '">' . $message . "</div></li>\n";
    }
    echo '</ul>';
}
?>

<?php

	//Validador de habilidades
	$validator = new SkillValidator;
	$user = User::model()->findByPk(Yii::app()->user->id);

    foreach ($skills as $skill) {
		if ($validator->canExecute($skill, $user)) {
			echo CHtml::link($skill->name, Yii::app()->createUrl('skill/execute', array('skill_id'=>$skill->id)), array('style'=>'color:blue;'));
			$this->widget('zii.widgets.CDetailView', array(
				'data'=>$skill,
				'attributes'=>array(
					'id',             // title attribute (in plain text)
					'name',        // an attribute of the related object "owner"
					'category', 'cost_tueste', 'cost_retueste', 'cost_relanzamiento', 'cost_tostolares'
				),
			));
		}
    }
?>
