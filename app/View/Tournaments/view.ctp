<div>

	<?php if($is_admin): ?>
	<div style="float:right">
		<?php echo $this->Html->link('Edit tournament', array('action' => 'edit', $tournament['Tournament']['id'])); ?>
	</div>
	<?php endif; ?>

	<h1><?php echo $tournament['Tournament']['title']; ?></h1>
	<p>
		<?php echo $this->Html->link($tournament['Lan']['title'], array('controller' => 'lans', 'action' => 'view', $tournament['Lan']['id'])); ?>
	</p>
	<table>
		<tbody>
			<tr>
				<td>Team size:</td>
				<td><?php echo $tournament['Tournament']['team_size']; ?> persons</td>
			</tr>
			<tr>
				<td>Start time:</td>
				<td>
					<?php if ($this->Time->isToday($tournament['Tournament']['time_start'])): ?>
						Today
					<?php else: ?>
						<?php echo $this->Time->format('D jM f', $tournament['Tournament']['time_start']); ?>
					<?php endif; ?>

					<?php echo $this->Time->format('H:i', $tournament['Tournament']['time_start']); ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div>
	<h2>Tournament description</h2>
	<div>
		<?php echo $tournament['Tournament']['description']; ?>
	</div>
</div>

<div>
	<h2>Rules</h2>
	<div>
		<?php echo $tournament['Tournament']['rules']; ?>
	</div>
</div>

<div>
	<?php if($tournament['Tournament']['time_start'] > date('Y-m-d H:i:s')): ?>
	<div style="float:right">
		<?php echo $this->Html->link('Create team', array('controller' => 'teams', 'action' => 'add', $tournament['Tournament']['id'])); ?>
	</div>
	<?php endif; ?>
	<h2>Teams participating</h2>
	<?php if (!count($tournament['Team'])): ?>
		<p>No teams participating in this tournament yet</p>
	<?php else: ?>
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
	<?php endif; ?>
</div>
<?php pr($tournament); ?>