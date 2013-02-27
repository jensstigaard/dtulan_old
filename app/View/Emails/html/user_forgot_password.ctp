<?php 
$link = $this->Html->url(array(
	 'controller' => 'users',
	 'action' => 'reset_password',
	 $ticket_id,
	 'full_base' => true
)); 
?>
<div>
	<?php
	echo $this->Html->image(
			  'http://dtu-lan.dk/img/logo_black.png', array(
		 'style' => 'display:block;'
			  )
	);
	?>
	<h2>Hey <?php echo $name; ?></h2>
	<p>To reset your password, please follow the link below: </p>
	<p><?php echo $this->Html->link($link, $link); ?></p>
	<p>Best regards</p>
	<p>The DTU LAN Crew</p>
</div>