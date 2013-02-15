<div>
	<?php
	echo $this->Html->image(
			  'http://dtu-lan.dk/img/logo_black.png', array(
		 'style' => 'display:block;'
			  )
	);
	?>
	<h2>Hey <?php echo $name; ?></h2>
	<p>To finish the activation process, you have to select a personal password for Your account by using this <a href="http://dtu-lan.dk/users/activate/<?php echo $activate_id ?>">link</a></p>
	<p>Best regards</p>
	<p>The DTU LAN crew</p>
</div>
