<?php echo $this->Html->script(array('ajax/all_links')); ?>

<div>
	<?php if ($is_admin): ?>
		<div style="float:right;">
			<?php echo $this->Html->link('Add Crewmember', array('controller' => 'crew', 'action' => 'add', $lan_id)); ?>
		</div>
	<?php endif; ?>
	<?php if (!count($crew)): ?>
		<p>No crew yet</p>
	<?php else: ?>
		<table>
			<thead>
				<tr>
					<th style="width:28px"></th>
					<th>Name</th>
					<?php if ($is_admin): ?>
						<th style="text-align: center;">Days attending</th>
						<th style="text-align: right;">Phone number</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($crew as $user): ?>
					<tr>
						<td style="padding:0 2px;text-align:center;">
							<?php
							if (!empty($user['User']['email_gravatar'])) {
								echo $this->Html->image(
										'http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=24&amp;r=r', array(
									'alt' => $user['User']['name'],
									'title' => $user['User']['name'] . ' gravatar',
									'style' => 'width:24px;height:24px;'
										)
								);
							}
							?>
						</td>
						<td>
							<?php echo $this->Html->link($user['User']['name'], array('controller' => 'users', 'action' => 'profile', $user['User']['id'])); ?>

						</td>
						<?php if ($is_admin): ?>
							<td style="text-align: center">
								<?php echo count($user['LanSignupDay']); ?> days
							</td>
							<td style="text-align: right">
								<?php echo $user['User']['phonenumber'] ?>
							</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>