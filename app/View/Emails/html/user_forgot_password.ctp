<div>
	<?php
	echo $this->Html->image(
			  'http://dtu-lan.dk/img/logo_black.png', array(
		 'style' => 'display:block;'
			  )
	);
	?>
	<h2>Hey <?php echo $name; ?></h2>
	<p>To reset your password, please follow this <a href="http://dtu-lan.dk/users/reset_password/<?php echo $ticket_id ?>">link</a></p>
	<p>Best regards</p>
	<p>The DTU LAN Crew</p>
</div>