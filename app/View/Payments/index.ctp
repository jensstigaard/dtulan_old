<div class="form">
	<div style="float:right;">
		<?php echo $this->Html->link('New Payment', array('action' => 'add')); ?>
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
				<td><?php echo $this->Html->link($payment['User']['name'], array('controller' => 'users', 'action' => 'profile', $payment['User']['id'])); ?></td>
				<td><?php echo $this->Time->nice($payment['Payment']['time']); ?></td>
				<td><?php echo $payment['Payment']['amount']; ?> DKK</td>
				<td>
					<?php echo $this->Html->link('Edit', array('action' => 'edit', $payment['Payment']['id'])); ?>
				</td>

			</tr>
		<?php endforeach; ?>

	</table>
	<?php pr($payments); ?>
</div>
