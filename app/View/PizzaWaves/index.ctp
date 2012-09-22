<div class="form">
	<div style="float:right;">
		<?php echo $this->Html->link('New PizzaWave', array('action' => 'add')); ?>
	</div>

	<h1>Pizza waves</h1>

	<?php if (!count($pizza_waves)): ?>
		<p>No pizza waves found</p>
	<?php else: ?>
		<table>
			<tr>
				<th>Lan</th>
				<th>
					<small>Time Start</small>
				</th>
				<th>
					<small>Time End</small>
				</th>
				<th>
					<small>Status</small>
				</th>
				<th></th>
			</tr>
			<?php foreach ($pizza_waves as $pizza_wave): ?>
				<tr>
					<td><?php echo $pizza_wave['Lan']['title']; ?></td>
					<td><?php echo $this->Time->nice($pizza_wave['PizzaWave']['time_start']); ?></td>
					<td><?php echo $this->Time->nice($pizza_wave['PizzaWave']['time_end']); ?></td>
					<td>
						<?php
						switch ($pizza_wave['PizzaWave']['status']) {
							case 0:
								echo'Not open';
								break;
							case 1:
								echo'Open';
								break;
							case 2:
								echo'Waiting for delivering';
								break;
							case 3:
								echo'Pizza wave received';
								break;
							case 4:
								echo'Finished';
								break;
							default:
								echo'Not proceded';
								break;
						}
						?>
					</td>
					<td>
						<?php
						echo $this->Html->link(
								'View', array(
							'action' => 'view',
							$pizza_wave['PizzaWave']['id']
								)
						);
						?>
					</td>
				</tr>
		<?php endforeach; ?>
		</table>
<?php endif; ?>
</div>
