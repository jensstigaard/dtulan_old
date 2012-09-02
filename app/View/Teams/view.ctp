<div class="form">
	<div style="float:right;">
		<h2>Invite ppl</h2>
		<?php if (!count($users)): ?>
			<p>Not available</p>
		<?php else: ?>
			<?php echo $this->Form->create('TeamInvite'); ?>
			<?php echo $this->Form->input('user_id', array('label' => '')); ?>
			<?php echo $this->Form->end(__('Invite')); ?>
		<?php endif; ?>
	</div>

	<h1><?php echo $team['Team']['name']; ?></h1>
	<p>In tournament: <?php echo $this->Html->link($team['Tournament']['title'], array('controller' => 'tournaments', 'action' => 'view', $team['Tournament']['id'])); ?></p>


	<h3>Members of team:</h3>
	<table>
		<thead>
			<tr>
				<th>Username:</th>
				<th>Leader:</th>
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

	<h3>Invited to team:</h3>
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
					<td><?php echo $this->Form->postLink('Cancel invite', array('action' => 'deleteInvite', $invite['id']), array('confirm' => 'Are you sure?')); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>

	</table>
	<?php pr($team); ?>
	<?php pr($users); ?>
</div>