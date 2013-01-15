<div>
	<?php if (!count($lans)): ?>
		<p>Not signed up for any LANs</p>
	<?php else: ?>
		<table>
			<tr>
				<th>Title</th>
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
								echo $this->Form->postLink(
										$this->Html->image('16x16_GIF/action_delete.gif') . ' Delete your signup', array('controller' => 'lan_signups', 'action' => 'delete', $lan['Lan']['slug']), array('confirm' => "Are You sure you will delete the signup?", 'escape' => false)
								);
								?>
								<?php if ($user_type == 'student'): ?>
									<p style="padding-left:5px;">Notice that guests of you or invites you've sent would be deleted too.</p>
								<?php endif; ?>
							<?php endif; ?>
									
							<?php if (isset($lan['LanInvite']['Student'])): ?>
								<br />
								<small>Invited by: <?php echo $this->Html->link($lan['LanInvite']['Student']['name'], array('controller' => 'users', 'action' => 'profile', $lan['LanInvite']['Student']['id'])); ?></small>
							<?php endif; ?>
							<?php if ($is_auth && count($lan['Lan']['LanInvite'])): ?>

							<?php endif; ?>
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