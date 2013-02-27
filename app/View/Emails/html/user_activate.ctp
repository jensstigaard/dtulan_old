<?php $activate_url = $this->Html->url(array(
	 'controller' => 'users',
	 'action' => 'activate',
	 $activate_id,
	 'full_base' => true
)); ?>
<div>
	<?php
	echo $this->Html->image(
			  'http://dtu-lan.dk/img/logo_black.png', array(
		 'style' => 'display:block;'
			  )
	);
	?>
	<h2>Hey <?php echo $name; ?></h2>
	<p>To finish the activation process, you have to select a personal password for Your account by using the link below:</p>
	<p><?php echo $this->Html->link($activate_url, $activate_url); ?></p>
	<p>Best regards</p>
	<p>The DTU LAN crew</p>
</div>
