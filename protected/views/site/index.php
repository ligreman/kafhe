<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>

<?php
	$uss = Yii::app()->user->id;
	var_dump($uss);
var_dump(Yii::app()->user->name);

//Yii::log('patata', 'info', 'aa.yy.zz');
	
	//$password_hash = crypt('test1', Randomness::blowfishSalt());
	$password_hash = crypt('admin', Randomness::blowfishSalt());

	echo $password_hash;
	var_dump(function_exists('openssl_random_pseudo_bytes'));
	
	if (Yii::app()->user->isSuperuser) {
		echo "SUPERGUAY";
        var_dump(Yii::app()->user->email);
	}

    if ($uss !== NULL) {
        //$authorizer = Yii::app()->getModule("rights")->getAuthorizer();
        //$authorizer->authManager->assign('Admin', $uss);
        echo "<p>Logueado ".$uss."</p>";
    } else {
        echo "<p>No logueado ".$uss."</p>";
    }

    if ($uss != NULL) {
	    $roles=Rights::getAssignedRoles($uss); // check for single role
        foreach($roles as $role) {
            if($role->name == 'Admin')
            {
                echo "<p>Soy Admin</p>";
            }
        }
    }

    $data = Rights::getAuthItemSelectOptions(1); //the item type (0: operation, 1: task, 2: role). Defaults to null,
    foreach ($data as $k=>$d) {
        echo "<p>Task: $k + $d</p>";
    }

    if(Yii::app()->user->checkAccess('Admin'))
    {
        echo "<p>Tengo acceso admin</p>";
    }
    if(Yii::app()->user->checkAccess('Authenticated'))
    {
        echo "<p>Tengo acceso autenticado</p>";
    }

    if(Yii::app()->user->checkAccess('operacion'))
    {
        echo "<p>Tengo operacion</p>";
    }

    if(Yii::app()->user->checkAccess('task1'))
    {
        echo "<p>Tengo task 1</p>";
    }
    if(Yii::app()->user->checkAccess('task2'))
    {
        echo "<p>Tengo task 2</p>";
    }

 ?>

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>
