<div>
	<h1><?php echo $team['Team']['name']; ?></h1>
	<p>In tournament: <?php echo $this->Html->link($team['Tournament']['title'], array('controller' => 'tournaments', 'action' => 'view', $team['Tournament']['id'])); ?></p>

	<?php if ($is_leader): ?>
		<p>
			<?php echo $this->Html->link('Delete team', array('action' => 'delete', $team['Team']['id']), array('confirm' => 'Are you sure?')); ?>
		</p>
	<?php endif; ?>
</div>

<?php if ($is_leader): ?>
	<div>
		<h2>Invite to team</h2>
		<?php if (!count($users)): ?>
			<p>No users available</p>
		<?php else: ?>
			<?php echo $this->Form->create('TeamInvite', array('controller' => 'team_invites', 'action' => 'add')); ?>
			<?php echo $this->Form->input('user_id'); ?>
			<?php echo $this->Form->hidden('team_id', array('value' => $team['Team']['id'])); ?>
			<?php echo $this->Form->end(__('Invite')); ?>
		<?php endif; ?>
	</div>
<?php endif; ?>


<div>
	<h3>Members of team</h3>
	<?php if (!count($team['TeamUser'])): ?>
		<p>No members in team</p>
	<?php else: ?>
		<table>
			<thead>
				<tr>
					<th>Gamertag</th>
					<th>Name</th>
					<th>Leader</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($team['TeamUser'] as $user): ?>
					<tr>
						<td>
							<?php
							echo$user['User']['gamertag'];
							?>
						</td>
						<td>
							<?php
							echo $this->Html->link($user['User']['name'], array(
								'controller' => 'users',
								'action' => 'profile',
								$user['User']['id']
									)
							);
							?>
						</td>
						<td>
							<?php
							if ($user['is_leader']) {
								echo $this->Html->image('16x16_PNG/star.png');
							}
							?>
						</td>
					</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
<?php endif; ?>

<?php if (count($team['TeamInvite'])): ?>
		<h3>Invited to team</h3>
		<table>
			<thead>
				<tr>
					<th>User</th>
					<th>Cancel invite</th>
				</tr>
			</thead>

			<tbody>
						<?php foreach ($team['TeamInvite'] as $invite): ?>
					<tr>
						<td><?php echo $invite['User']['name']; ?></td>
						<td>
							<?php
							if ($is_leader) {
								echo $this->Form->postLink($this->Html->image('16x16_PNG/cancel.png') . ' Cancel invite', array(
									'controller' => 'team_invites',
									'action' => 'delete',
									$invite['id']
										), array(
									'confirm' => 'Are you sure?',
									'escape' => false
										)
								);
							}
							?></td>
					</tr>
	<?php endforeach; ?>
			</tbody>
		</table>
<?php endif; ?>
</div>