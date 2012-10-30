<div>
	<?php if (!count($lans)): ?>
		<p>Not signed up for any LANs</p>
	<?php else: ?>
		<table>
			<tr>
				<th>Title</th>
				<th>Days attending</th>
				<?php if ($is_auth): ?>
					<th>Guests of you</th>
				<?php endif; ?>
			</tr>


			<?php foreach ($lans as $lan): ?>
				<?php if ($lan['Lan']['published'] || $is_auth): ?>
					<tr>
						<td>
							<?php echo $this->Html->link($lan['Lan']['title'], array('controller' => 'lans', 'action' => 'view', $lan['Lan']['slug'])); ?>

							<?php if ($is_you && $lan['Lan']['sign_up_open']): ?>
								<br />
								<?php
								echo $this->Html->link(
										$this->Html->image('16x16_GIF/reply.gif') . ' Edit your signup', array('controller' => 'lan_signups', 'action' => 'edit', $lan['Lan']['id']), array('escape' => false)
								);
								?>
							<?php endif; ?>
							<?php if (isset($lan['LanInvite']['Student'])): ?>
								<br />
								<small>Invited by: <?php echo $this->Html->link($lan['LanInvite']['Student']['name'], array('controller' => 'users', 'action' => 'profile', $lan['LanInvite']['Student']['id'])); ?></small>
							<?php endif; ?>
							<?php if ($is_auth && count($lan['Lan']['LanInvite'])): ?>

							<?php endif; ?>
						</td>
						<td>
							<?php foreach ($lan['LanSignupDay'] as $day): ?>
								<?php echo $this->Time->format('M jS (l)', $day['LanDay']['date']); ?><br />
							<?php endforeach; ?>
						</td>

						<?php if ($is_auth): ?>
							<td>
								<?php if (isset($lan_invites_accepted) && count($lan_invites_accepted)): ?>
									<?php foreach ($lan_invites_accepted[$lan['Lan']['id']] as $invite_accepted): ?>
										<?php echo $this->Html->link($invite_accepted['Guest']['name'], array('controller' => 'users', 'action' => 'profile', $invite_accepted['Guest']['id'])); ?><br />
									<?php endforeach; ?>
								<?php endif; ?>
							</td>
						<?php endif; ?>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>