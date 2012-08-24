<div class="form">
	<div style="float:right;">
		<?php echo $this->Html->link('New PizzaWave', array('action' => 'add')); ?>
	</div>

	<h2>PizzaWaves</h2>

	<table>
		<tr>
                        <th>Lan</th>
                        <th><small>Time Start</small></th>
			<th><small>Time End</small></th>
			<th><small>Status</small></th>
		</tr>

		<?php foreach ($pizzaWaves as $pizzaWave): ?>
			<tr>
				<td><?php echo $pizzaWave['PizzaWave']['lan_id'];?></td>
				<td><?php echo $this->Time->nice($pizzaWave['PizzaWave']['time_start']); ?></td>
				<td><?php echo $this->Time->nice($pizzaWave['PizzaWave']['time_end']); ?></td>
				<td><?php echo $pizzaWave['PizzaWave']['status']?> </td>
			</tr>
		<?php endforeach; ?>

	</table>
</div>
