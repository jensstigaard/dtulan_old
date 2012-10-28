<?php echo $this->Html->script('payments/index_user', FALSE); ?>

<div>
	<h2>
		<?php echo $this->Html->image('32x32_PNG/payment_cash.png'); ?>
		Payments
	</h2>

	<?php if (!count($payments)): ?>
		<p>
			No payments registered
		</p>
	<?php else: ?>
		<table>
			<tr>
				<th>Time</th>
				<th>Amount</th>
			</tr>

			<?php $total_balance = 0; ?>
			<?php foreach ($payments as $payment): ?>
				<tr>
					<td>
						<?php echo $payment['Payment']['time_nice']; ?>

					</td>
					<td><?php echo $payment['Payment']['amount']; ?> DKK</td>
				</tr>
				<?php $total_balance += $payment['Payment']['amount'];
				?>

			<?php endforeach; ?>
			<tr>
				<td>
					<div style="float:right">Total payments:</div>
					Payments made: <?php echo count($payments); ?>
				</td>
				<td><?php echo $total_balance; ?> DKK</td>
			</tr>
		</table>
	<?php endif; ?>
</div>