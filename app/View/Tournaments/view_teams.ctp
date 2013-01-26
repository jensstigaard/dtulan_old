<div style="padding: 5px;">
	<?php if (!count($teams)): ?>
		<p>No teams participating in this tournament yet</p>
	<?php else: ?>
		<table>
			<tr>
				<th></th>
				<th>Team Name</th>
				<th>Member count</th>
			</tr>
			<?php foreach ($teams as $team): ?>
				<tr>
					<td><?php
		if ($team['Team']['place'] == 1) {
			echo $this->Html->image('32x32_PNG/trophy_gold.png');
		} elseif ($team['Team']['place'] == 2) {
			echo $this->Html->image('32x32_PNG/trophy_gold.png');
		} elseif ($team['Team']['place'] == 3) {
			echo $this->Html->image('32x32_PNG/trophy_gold.png');
		}
				?>
					</td>
					<td><?php echo $this->Html->link($team['Team']['name'], array('controller' => 'teams', 'action' => 'view', $team['Team']['id'])); ?></td>
					<td><?php echo count($team['TeamUser']); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>