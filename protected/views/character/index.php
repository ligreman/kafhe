<?php
/* @var $this CharacterController */

$this->breadcrumbs=array(
	'Character',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php
  
        $this->widget('zii.widgets.CDetailView', array(
            'data'=>$user,
            'attributes'=>array(
                'id',             // title attribute (in plain text)
                'username',        // an attribute of the related object "owner"
                'alias','brithdate','email','role',
                'group_id','side','status','rank',
                'ptos_tueste','ptos_retueste','ptos_relanzamiento',
                'ptos_talentos','tostolares','azucarillos','dominio_tueste',
                'dominio_habilidades','dominio_bandos','times','calls'
            ),
        ));

  
?>

