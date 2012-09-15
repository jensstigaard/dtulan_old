<div>
    <fieldset>
        <legend>Your signup for <?php echo $this->Html->link($lan_signup['Lan']['title'], array('controller' => 'lans', 'action' => 'view', $lan_signup['Lan']['id'])); ?></legend>
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

				<tr>
					<td colspan="2">
						<?php
						echo $this->Form->postLink(
								$this->Html->image('16x16_GIF/action_delete.gif') . ' Delete your signup', array('controller' => 'lan_signups', 'action' => 'delete', $lan_signup['Lan']['id']), array('confirm' => "Are You sure you will delete the signup?", 'escape' => false)
						);
						?>
						<?php if ($lan_signup['User']['type']): ?>
							<p style="padding-left:5px;">Notice that guests of you or invites you've sent would be deleted too.</p>
						<?php endif; ?>
					</td>
				</tr>
			</tbody>

		</table>
		<?php echo $this->Form->create(); ?>
		<h2>Select attending days</h2>
		<?php
		foreach ($lan_days as $x => $day):
			$conditions = array('value' => $day['id']);

			if ($day['checked']) {
				$conditions['checked'] = true;
			} elseif (!$day['seats_left']) {
				$conditions['disabled'] = 'disabled';
			}
			?>
			<div>
				<label>
					<?php echo $this->Form->checkbox('LanSignupDay.' . $x . '.lan_day_id', $conditions); ?>
					<?php echo $day['value']; ?> (<?php echo ($day['seats_left']); ?> seats left)
				</label>
			</div>
		<?php endforeach; ?>
		<?php echo $this->Form->end(__('Save')); ?>
	</fieldset>
</div>