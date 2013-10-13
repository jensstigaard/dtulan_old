<?php echo $this->Html->script('payments/index_user', FALSE); ?>

<div>
	<?php if (!count($payments)): ?>
		<p>
			No payments registered
		</p>
	<?php else: ?>
		<table>
			<tr>
				<th>Latest payments</th>
				<th style="text-align:right;">Type</th>
				<th>Amount</th>

			</tr>

			<?php $total_balance = 0; ?>
			<?php foreach ($payments as $payment): ?>
				<tr>
					<td>
						<?php echo $payment['Payment']['time_nice']; ?>

					</td>
					<td style="text-align:right;">
						<?php
						switch ($payment['Payment']['type']) {
							case'mobilepay':
								$icon = 'icon-mobile-phone icon-2x';
								break;
							case 'creditcard':
								$icon = 'icon-credit-card icon-large';
								break;
							default:
								$icon = 'icon-money icon-large';
						}

						echo '<i class="' . $icon . '"></i>';
						?>
					</td>
					<td>DKK <?php echo $payment['Payment']['amount']; ?></td>
				</tr>
				<?php $total_balance += $payment['Payment']['amount'];
				?>

			<?php endforeach; ?>
			<tr>
				<td colspan="3">
					Payments made: <?php echo $count_payments; ?>
				</td>
			</tr>
		</table>
	<?php endif; ?>
</div>