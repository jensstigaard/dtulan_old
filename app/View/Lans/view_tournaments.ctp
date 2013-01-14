<?php echo $this->Html->script(array('ajax/all_links')); ?>

<div class="ajax_area" id="tournaments">
	<?php if ($is_admin): ?>
		<div style="float:right">
			<?php echo $this->Html->link('New tournament', array('controller' => 'tournaments', 'action' => 'add', $lan_id)); ?>
		</div>
	<?php endif; ?>
	<table>
		<thead>
			<tr>
				<th>Title</th>
				<th>Game title</th>
				<th style="text-align: center">Team size</th>
				<th style="text-align: right">Participants</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!count($tournaments)): ?>
				<tr>
					<td colspan="4">
						No tournaments published yet
					</td>
				</tr>
			<?php else: ?>
				<?php foreach ($tournaments as $tournament): ?>
					<tr>
						<td><?php echo $this->Html->link($tournament['Tournament']['title'], array('controller' => 'tournaments', 'action' => 'view', $tournament['Tournament']['id'])); ?></td>
						<td><?php echo $tournament['Game']['title'] ?></td>
						<td style="text-align: center"><?php echo $tournament['Tournament']['team_size'] ?></td>
						<td style="text-align: right"><?php echo count($tournament['Team']); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>