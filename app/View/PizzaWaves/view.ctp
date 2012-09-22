<div>
	<h1><?php echo __('Pizza wave'); ?></h1>
	<table>
		<tbody>
			<tr>
				<td>Time start</td>
				<td><?php echo $this->Time->nice($pizza_wave['PizzaWave']['time_start']); ?></td>
			</tr>
			<tr>
				<td>Time end</td>
				<td><?php echo $this->Time->nice($pizza_wave['PizzaWave']['time_end']); ?></td>
			</tr>
			<tr>
				<td>Status</td>
				<td>
					<?php
					switch ($pizza_wave['PizzaWave']['status']) {
						case 1:
							echo'Waiting for delivering.<br />';
							echo $this->Html->link('Mark as received', array('action' => 'mark_received', $pizza_wave['PizzaWave']['id']));
							break;
						case 2:
							echo'Pizza wave received';
							break;
						default:
							if($pizza_wave['PizzaWave']['time_end'] < date('Y-m-d H:i:s') && count($pizza_wave_items)){
								echo $this->Html->link('Send email to pizzaria now', array('action' => 'send_email', $pizza_wave['PizzaWave']['id']));
							}
							elseif(count($pizza_wave_items)){
								echo'No pizzas in wave';
							}
							else{
								echo'Not proceded';
							}
							break;
					}
					?>
				</td>
			</tr>
		</tbody>

	</table>
</div>
<div>
	<h2><?php echo __('Items in wave'); ?></h2>
	<table>
		<thead>
			<tr>
				<th>Quantity</th>
				<th>Pizza number</th>
				<th>Pizza</th>
				<th>Pizza type</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($pizza_wave_items as $item): ?>
				<tr>
					<td><?php echo $item['quantity']; ?></td>
					<td><?php echo $item['pizza_number']; ?></td>
					<td><?php echo $item['pizza_title']; ?></td>
					<td><?php echo $item['pizza_type']; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php pr($pizza_wave_items); ?>