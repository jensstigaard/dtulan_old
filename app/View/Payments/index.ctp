<div class="form">
	<div style="float:right;">
		<?php echo $this->Html->link('New LAN', array('action' => 'add')); ?>
	</div>

	<h2>Payments</h2>

	<table>
		<tr>
			<th>User</th>
			<th>Payment occurred</th>
			<th>Amount</th>
			<th>Actions</th>
		</tr>

		<?php foreach ($payments as $payment): ?>
			<tr>
				<td><?php echo $payment['User']['name']; ?></td>
				<td><?php echo $payment['Payment']['time']; ?></td>
				<td><?php echo $payment['Payment']['amount']; ?></td>
				<td>
					<?php echo $this->Html->link('Edit', array('action' => 'edit', $payment['Payment']['id'])); ?>
				</td>

			</tr>
		<?php endforeach; ?>

	</table>
	<?php pr($payments); ?>
	<?php echo $payments[0]['User']['name']; ?>	
</div>
