<div>
	<h2>Hey <?php echo $user['name']; ?></h2>
	<div>
		<?php echo $text['Lan']['text']; ?>
	</div>
	<div>
		<?php
		echo $this->Html->link(
				  'View event-details', array(
			 'controller' => 'lans',
			 'action' => 'view',
			 $lan['slug']
				  ), array(
				  )
		);
		?>
	</div>
	<div>
		<p>Best regards</p>
		<p>The DTU LAN crew</p>
	</div>
</div>
