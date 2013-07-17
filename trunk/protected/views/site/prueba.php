<?php
/* @var $this SiteController */
?>

<p>Hola <?php echo CHtml::ajaxLink('Pincha', CController::createUrl('site/pruebaajax'), array('update'=>'#div')); ?></p>

<?php
    // the data received could look like: {"id":3, "msg":"No error found"}
    //array('success' => 'js:function(data) { $("#newid").val(data.id); $("#message").val(data.msg); }')
?>

<p>Salida: <span id="div">nada</span></p>




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

echo '<hr>';

//Yii::app()->event->setModel('desayuno');

?>
