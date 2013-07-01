<?php
/* @var $this AdminController */

$this->breadcrumbs=array(
	'Administración',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>


<?php
//var_dump($notifications);

foreach ($notifications as $notification) {
    //echo "Sender: ".$notification->sender."<br>";
    $this->widget('zii.widgets.CDetailView', array(
        'data'=>$notification,
        'attributes'=>array(
            'id',             // title attribute (in plain text)
            'sender',        // an attribute of the related object "owner"
            'message:html',
            'timestamp',  // description attribute in HTML
        ),
    ));

    echo "<br>";
}

?>
