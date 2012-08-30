<div class="form">
	<?php echo $this->Form->create('Invite'); ?>
    <fieldset>
        <legend><?php echo $team['Team']['name']; ?> member invitation</legend>
		<?php echo $this->Form->input('user_id'); ?>
	</fieldset>
	<?php echo $this->Form->end(__('Invite')); ?>

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
			</tr>
		</thead>

		<tbody>
			<?php foreach ($team['Invite'] as $user): ?>
				<tr>
					<td><?php echo $user['gamertag']; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>

	</table>
	<?php pr($team); ?>
</div>