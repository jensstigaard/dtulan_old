<div class="view">
	<h1><?php echo $tournament['Tournament']['title']; ?></h1>
	<ul>
		<li>Team size: <?php echo $tournament['Tournament']['max_team_size']; ?> persons</li>
		<li>Description:
			<?php echo $tournament['Tournament']['description']; ?></li>
	</ul>
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

		<?php // pr($tournament) ?>
	</div>
</div>