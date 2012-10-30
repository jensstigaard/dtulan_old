<div id="tab-invites">
	<?php if (!count($lan_invites)): ?>
		<p>No invites found</p>
	<?php else: ?>
		<table>
			<thead>
				<tr>
					<th style="width:28px"></th>
					<th>Name</th>
					<th>Invited by</th>
					<th>Time invited</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($lan_invites as $invite): ?>
					<tr>
						<td style="padding:0 2px;text-align:center;">
							<?php
							if (!empty($invite['Guest']['email_gravatar'])) {
								echo $this->Html->image(
										'http://www.gravatar.com/avatar/' . md5(strtolower($invite['Guest']['email_gravatar'])) . '?s=24&amp;r=r', array(
									'alt' => $invite['Guest']['name'],
									'title' => $invite['Guest']['name'] . ' gravatar',
									'style' => ''
										)
								);
							}
							?>
						</td>
						<td>
							<?php echo $this->Html->link($invite['Guest']['name'], array('controller' => 'users', 'action' => 'profile', $invite['Guest']['id'])); ?>
						</td>
						<td>
							<?php echo $this->Html->link($invite['Student']['name'], array('controller' => 'users', 'action' => 'profile', $invite['Student']['id'])); ?>
						</td>
						<td><?php echo $this->Time->nice($invite['LanInvite']['time_invited']); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>