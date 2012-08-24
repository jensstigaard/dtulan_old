<div class="view">
	<h1><?php echo $tournament['Tournament']['title']; ?></h1>
	<ul>
		<li>Full team size: <?php echo $tournament['Tournament']['max_team_size']; ?></li>
		<li>Description:</li>
		<?php echo $tournament['Tournament']['description']; ?>
	</ul>
<div style="float:right"><?php echo $this->Html->link('create team', array('controller' => 'teams', 'action' => 'add', $tournament['Tournament']['id'])); ?></div>
	<table>
		<tr>
			<th>Team Name</th>
			<th>Member count</th>
		</tr>
		<?php foreach ($tournament['Team'] as $team): ?>
			<tr>
				<td><?php echo $this->Html->link($team['name'], array('controller' => 'teams', 'action' => 'view', $team['id']));?></td>
				<td><?php echo count($team['User']); ?></td>
			</tr>
		<?php endforeach; ?>
	</table>

	<?php pr($tournament) ?>
</div>