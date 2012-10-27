<?php echo $this->Html->script(array('jquery', 'ajax/all_links')); ?>

<div class="ajax_area" id="lan_signups">
	<table>
		<thead>
			<tr>
				<th style="width:28px;"></th>
				<th><?php echo $this->Paginator->sort('User.name', 'Name'); ?></th>
				<?php if ($is_admin): ?>
					<th style="text-align: center;">Days attending</th>
					<th style="text-align: right;"><?php echo $this->Paginator->sort('User.phonenumber', 'Phone number'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($lan_signups as $user): ?>
				<tr>
					<td style="padding:0 2px;text-align:center;">
						<?php
						if (!empty($user['User']['email_gravatar'])) {
							echo $this->Html->image(
									'http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=24&amp;r=r', array(
								'alt' => $user['User']['name'],
								'title' => $user['User']['name'] . ' gravatar',
								'style' => ''
									)
							);
						}
						?>
					</td>
					<td>
						<?php echo $this->Html->link($user['User']['name'], array('controller' => 'users', 'action' => 'profile', $user['User']['id'])); ?>
						<?php if ($user['User']['type'] == 'guest'): ?>
							(g)
						<?php endif; ?>
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
	<div style="text-align: center" class="pagination-link">
		<?php echo $this->Paginator->numbers(); ?>
	</div>
</div>