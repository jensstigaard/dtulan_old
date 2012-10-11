<div>
	<?php if ($is_leader): ?>
		<div style="float:right;">
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

	<h1><?php echo $team['Team']['name']; ?></h1>
	<p>In tournament: <?php echo $this->Html->link($team['Tournament']['title'], array('controller' => 'tournaments', 'action' => 'view', $team['Tournament']['id'])); ?></p>


	<h3>Members of team</h3>
	<?php if (!count($team['TeamUser'])): ?>
		<p>No members in team</p>
	<?php else: ?>
		<table>
			<thead>
				<tr>
					<th>Gamertag</th>
					<th>Leader</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($team['TeamUser'] as $user): ?>
					<tr>
						<td><?php echo $user['User']['gamertag']; ?></td>
						<td><?php echo $user['is_leader']; ?></td>
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
					<th>Username:</th>
					<th>Cancel invite</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($team['TeamInvite'] as $invite): ?>
					<tr>
						<td><?php echo $invite['User']['gamertag']; ?></td>
						<td><?php
			echo $this->Form->postLink('Cancel invite', array(
				'controller' => 'team_invites',
				'action' => 'delete',
				$invite['id']
					), array(
				'confirm' => 'Are you sure?'
					)
			);
					?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>