<?php
/* @var $this EventController */

$this->breadcrumbs=array(
	'Event'=>array('/event'),
	'Finish',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php
	$itos = $orders['itos'];
	$noitos = $orders['noitos'];
	$bebidas = $orders['bebidas'];
	$comidas = $orders['comidas'];
?>

<p>Pedidos ITO</p>
	<ul>
		<li>Bebidas
			<ul>
				<?php foreach($itos['bebidas'] as $id=>$cantidad): ?>
					<li><?php echo $cantidad.'x '.$bebidas[$id]; ?></li>
				<?php endforeach; ?>
			</ul>
		</li>
		<li>Comidas
			<ul>
				<?php foreach($itos['comidas'] as $id=>$cantidad): ?>
					<li><?php echo $cantidad.'x '.$comidas[$id]; ?></li>
				<?php endforeach; ?>
			</ul>
		</li>
	</ul>

<p>Pedidos normales</p>
	<ul>
		<li>Bebidas
			<ul>
				<?php foreach($noitos['bebidas'] as $id=>$cantidad): ?>
					<li><?php echo $cantidad.'x '.$bebidas[$id]; ?></li>
				<?php endforeach; ?>
			</ul>
		</li>
		<li>Comidas
			<ul>
				<?php foreach($noitos['comidas'] as $id=>$cantidad): ?>
					<li><?php echo $cantidad.'x '.$comidas[$id]; ?></li>
				<?php endforeach; ?>
			</ul>
		</li>
	</ul>
	
<p>Ya he llamado!</p>
	