<div>
	<?php echo $this->Html->image('http://dtu-lan.dk/logo_black.png'); ?>
	<p>Hey <?php echo $user['name']; ?>!</p>
	<h2>New event!</h2>
	<h1><?php echo $lan['title']; ?></h1>
	<p>The event starts <strong><?php echo $lan['time_start']; ?></strong> and will end <strong><?php echo $lan['time_end']; ?></strong></p>
	<p><?php
	echo $this->Html->link('View event-details here', array(
		 'controller' => 'lans',
		 'action' => 'view',
		 $lan['slug'],
		 'full_base' => true
	));
	?></p>
	<div>
		<?php echo $text; ?>
	</div>
	<p>Best regards</p>
	<p>The DTU LAN Crew</p>
</div>
