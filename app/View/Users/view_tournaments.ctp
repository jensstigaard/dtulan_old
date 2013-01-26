<div>
	<?php if (!count($teams)): ?>
		<p>You do not participate in any tournament</p>
	<?php else: ?>
		<table>
			<tr>
				<th>Tournament</th>
				<th>Name</th>
				<th>Leader</th>
				<th>Members</th>
			</tr>

			<?php foreach ($teams as $team): ?>
				<tr>
					<td><?php echo $this->Html->link($team['Team']['Tournament']['title'], array('controller' => 'tournaments', 'action' => 'view', $team['Team']['Tournament']['Lan']['slug'], $team['Team']['Tournament']['slug'])); ?></td>
					<td><?php echo $this->Html->link($team['Team']['name'], array('controller' => 'teams', 'action' => 'view', $team['Team']['id'])); ?></td>
					<td><?php echo $team['TeamUser']['is_leader'] ? $this->Html->image('16x16_PNG/star.png') : ''; ?></td>
					<td><?php echo $team['Team']['count']; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>