<div class="view">
	<h1>Team: <?php echo $team['Team']['name']?></h1>
	<h2>Game:</h2>
	<li><?php echo $team['Tournament']['Game']['title']; ?></li><br>
	<h3>Members:</h3>
	<div style="float:right"><?php echo $this->Html->link('invite people', array('controller' => 'teams', 'action' => 'invite', $team['Team']['id'])); ?></div>
	<table>
		<tr>
		<th>Username:</th>
		<th>Leader:</th>
		</tr>
		<?php foreach($team['User'] as $user): ?>
		<tr>
			<td><?php echo $user['gamertag']; ?><td>
		</tr>
		<?php endforeach;?>
	</table>
	<?php pr($team) ?>
</div>