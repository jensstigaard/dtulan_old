<div>
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Signup for ' . $lan['Lan']['title']); ?></legend>
		<table>
			<tbody>
				<tr>
					<td>Price:</td>
					<td><?php echo $lan['Lan']['price']; ?> DKK</td>
				</tr>
				<tr>
					<td>Start-date:</td>
					<td><?php echo $this->Time->nice($lan['Lan']['time_start']); ?></td>
				</tr>
				<tr>
					<td>End-date:</td>
					<td><?php echo $this->Time->nice($lan['Lan']['time_end']); ?></td>
				</tr>
			</tbody>

		</table>

		<h2>Select attending days</h2>
		<?php // echo $this->Form->input('LanDay'); ?>
		<?php
		$x = 0;
		foreach ($lan_days as $x => $day):
			$conditions = array('value' => $day['id']);

			if (!$day['seats_left']) {
				$conditions['disabled'] = 'disabled';
			} else {
				$conditions['checked'] = true;
			}
			?>
			<div>
				<label>
					<?php echo $this->Form->checkbox('LanSignupDay.' . $x . '.lan_day_id', $conditions); ?>
					<?php echo $day['value']; ?> (<?php echo ($day['seats_left']); ?> seats left)
				</label>
			</div>
		<?php endforeach; ?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>

	<?php // pr($user);     ?>
</div>