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
			</tr>
			<?php foreach ($pizza_waves as $pizza_wave): ?>
				<tr>
					<td><?php echo $pizza_wave['Lan']['title']; ?></td>
					<td><?php echo $this->Time->nice($pizza_wave['PizzaWave']['time_start']); ?></td>
					<td><?php echo $this->Time->nice($pizza_wave['PizzaWave']['time_end']); ?></td>
					<td>
						<?php if (!$pizza_wave['PizzaWave']['status'] && $pizza_wave['PizzaWave']['time_end'] < date('Y-m-d H:i:s')): ?>
							<?php echo $this->Html->link('Send email to pizzaria now', array('action' => 'view', $pizza_wave['PizzaWave']['id'])); ?>
						<?php else: ?>
							<?php echo $pizza_wave['PizzaWave']['status']; ?>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>
