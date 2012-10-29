<div>
	<?php if (!count($teams)): ?>
		<p>No teams participating in this tournament yet</p>
	<?php else: ?>
		<table>
			<tr>
				<th>Team Name</th>
				<th>Member count</th>
			</tr>
			<?php foreach ($teams as $team): ?>
				<tr>
					<td><?php echo $this->Html->link($team['Team']['name'], array('controller' => 'teams', 'action' => 'view', $team['Team']['id'])); ?></td>
					<td><?php echo count($team['TeamUser']); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>