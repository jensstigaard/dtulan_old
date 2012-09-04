<div class="form">
	<?php echo $this->Form->create('LanSignup', array('url' => array('controller' => 'lansignups', 'action' => 'add', $lan['Lan']['id']))); ?>
    <fieldset>
        <legend><?php echo __('Signup for ' . $lan['Lan']['title']); ?></legend>
		<ul>
			<li>Start time: <?php echo $this->Time->nice($lan['Lan']['time_start']); ?></li>
			<li>End time: <?php echo $this->Time->nice($lan['Lan']['time_end']); ?></li>
		</ul>
		<br /><hr />
		<h2>Select attending days</h2>
		<?php
		$x = 0;
		foreach ($lan_days as $day_id => $day):
			$conditions = array('value' => $day_id);

//			if (!$day['seats_left']) {
//				$conditions['disabled'] = 'disabled';
//			}
			?>
			<div>
				<?php echo $this->Form->checkbox('LanSignupDay.' . $x . '.lan_day_id', $conditions); ?>
				<?php echo $day['value']; ?> (<?php echo ($day['seats_left']); ?> seats left)
				<?php $x++; ?>
			</div>
		<?php endforeach; ?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>

	<?php // pr($user);    ?>
</div>