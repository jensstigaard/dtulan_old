<div>
	<?php if (!count($pizza_orders)): ?>
		<p>
			No orders registered
		</p>
	<?php else: ?>
		<table>
			<thead>
				<tr>
					<th>Time</th>
					<th>Items</th>
					<th>Price</th>
					<?php if ($is_you): ?>
						<th>Actions</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>

				<?php $total_balance = 0; ?>
				<?php foreach ($pizza_orders as $pizza_order): ?>
					<?php $order_balance = 0; ?>
					<tr>
						<td>

							<?php echo $pizza_order['PizzaOrder']['time_nice']; ?>

						</td>
						<td><?php foreach ($pizza_order['PizzaOrderItem'] as $item): ?>
								<div>
									<div style="float:right"><?php echo $item['price']; ?> DKK =</div>
									<?php echo $item['quantity']; ?> x <?php echo $item['PizzaPrice']['Pizza']['title']; ?>
									<small>(<?php echo $item['PizzaPrice']['PizzaType']['title']; ?>)</small>
								</div>
								<?php $order_balance += $item['quantity'] * $item['price']; ?>
							<?php endforeach; ?></td>
						<td><?php echo $order_balance; ?> DKK</td>
						<?php if ($is_you): ?>
							<td>
								<?php
								if ($pizza_order['PizzaOrder']['is_cancelable']) {
									echo $this->Form->postLink(
											  $this->Html->image('16x16_PNG/cancel.png') . ' Cancel order', array('controller' => 'pizza_orders', 'action' => 'delete', $pizza_order['PizzaOrder']['id']), array('confirm' => "Are You sure you will delete this order?", 'escape' => false)
									);
								}
								?>
							</td>
						<?php endif; ?>
					</tr>
					<?php $total_balance += $order_balance; ?>
				<?php endforeach; ?>
				<tr>
					<td>Orders: <?php echo count($pizza_orders); ?></td>
					<td style="text-align:right;">Total amount spend on pizzas:</td>
					<td style="text-decoration: underline"><?php echo $total_balance; ?> DKK</td>
					<?php if ($is_you): ?>
						<td></td>
					<?php endif; ?>
				</tr>
			</tbody>
		</table>
	<?php endif; ?>
</div>