<div class="form">
	<?php echo $this->Form->create('LanSignup', array('url' => array('controller' => 'lansignups', 'action' => 'add', $lan['Lan']['id']))); ?>
    <fieldset>
        <legend><?php echo __('Signup for ' . $lan['Lan']['title']); ?></legend>
		<ul>
			<li>Start time: <?php echo $this->Time->format('l F jS Y H:i', $lan['Lan']['time_start']); ?></li>
			<li>End time: <?php echo $this->Time->format('l F jS Y H:i', $lan['Lan']['time_end']); ?></li>
		</ul>
		<br /><hr />
		<h2>Select attending days</h2>
		<?php
		$x = 0;
		foreach ($lan_days as $day_id => $day_value):
			?>
			<div>
				<?php
				echo $this->Form->checkbox('LanSignupDay.' . $x . '.lan_day_id', array('value' => $day_id));
				echo $day_value;
				$x++;
				?>
			</div>
		<?php endforeach; ?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>

	<?php // pr($user);  ?>
</div>