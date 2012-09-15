<div class="view">
	<h1><?php echo $tournament['Tournament']['title']; ?></h1>
	<table>
		<tbody>
			<tr>
				<td>In LAN:</td>
				<td><?php echo $this->Html->link($tournament['Lan']['title'], array('controller' => 'lans', 'action' => 'view', $tournament['Lan']['id'])); ?></td>
			</tr>
			<tr>
				<td>Team size:</td>
				<td><?php echo $tournament['Tournament']['max_team_size']; ?> persons</td>
			</tr>
			<tr>
				<td>Description:</td>
				<td><?php echo $tournament['Tournament']['description']; ?></td>
			</tr>
		</tbody>
	</table>
	<hr />
	<div style="margin-top:20px;">
		<div style="float:right"><?php echo $this->Html->link('Create team', array('controller' => 'teams', 'action' => 'add', $tournament['Tournament']['id'])); ?></div>
		<h2>Teams participating:</h2>
		<table>
			<tr>
				<th>Team Name</th>
				<th>Member count</th>
			</tr>
			<?php foreach ($tournament['Team'] as $team): ?>
				<tr>
					<td><?php echo $this->Html->link($team['name'], array('controller' => 'teams', 'action' => 'view', $team['id'])); ?></td>
					<td><?php echo count($team['TeamUser']); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>

		<?php // pr($tournament); ?>
	</div>
</div>