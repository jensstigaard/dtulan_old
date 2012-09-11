<div>
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend>Your signup for <?php echo $this->Html->link($lan_signup['Lan']['title'], array('controller'=>'lans','action'=>'view',$lan_signup['Lan']['id'])); ?></legend>
		<table>
			<tbody>
				<tr>
					<td>Price:</td>
					<td><?php echo $lan_signup['Lan']['price']; ?> DKK</td>
				</tr>
				<tr>
					<td>Start-date:</td>
					<td><?php echo $this->Time->nice($lan_signup['Lan']['time_start']); ?></td>
				</tr>
				<tr>
					<td>End-date:</td>
					<td><?php echo $this->Time->nice($lan_signup['Lan']['time_end']); ?></td>
				</tr>
			</tbody>

		</table>
		<h2>Select attending days</h2>
		<?php
		foreach ($lan_days as $x => $day):
			$conditions = array('value' => $day['id']);

			if($day['checked']){
				$conditions['checked'] = true;
			}
			elseif (!$day['seats_left']) {
				$conditions['disabled'] = 'disabled';
			}

			?>
			<div>
				<?php echo $this->Form->checkbox('LanSignupDay.' . $x . '.lan_day_id', $conditions); ?>
				<?php echo $day['value']; ?> (<?php echo ($day['seats_left']); ?> seats left)
			</div>
		<?php endforeach; ?>
	</fieldset>
	<?php echo $this->Form->end(__('Save')); ?>
</div>