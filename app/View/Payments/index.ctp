<div>
	<h2>Payments</h2>

	<?php if (!count($payments)): ?>
		<p>No payments yet</p>
	<?php else: ?>
		<table>
			<tr>
				<th>User</th>
				<th>Payment occurred</th>
				<th>Amount</th>
				<th>Made by</th>
			</tr>

			<?php foreach ($payments as $payment): ?>
				<tr>
					<td>
						<?php echo $this->Html->link($payment['User']['name'], array('controller' => 'users', 'action' => 'view', $payment['User']['id'])); ?>
					</td>
					<td>
						<?php echo $payment['Payment']['time_nice']; ?>
					</td>
					<td>
						<?php echo $payment['Payment']['amount']; ?> DKK
					</td>

					<td>
						<?php echo $this->Html->link($payment['Crew']['User']['name'], array('controller' => 'users', 'action' => 'profile', $payment['Crew']['User']['id'])); ?>
					</td>


				</tr>
			<?php endforeach; ?>
		</table>

		<div style="text-align:center;">
			<?php
			echo $this->Paginator->numbers();
			?>
		</div>

	<?php endif; ?>

</div>
<?php // pr($payments); ?>
