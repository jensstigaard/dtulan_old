<?php echo $this->Html->script('foods/index_user', FALSE); ?>

<div>
	<?php if (!count($food_orders)): ?>
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
				<?php foreach ($food_orders as $food_order): ?>
					<?php $order_balance = 0; ?>
					<tr>
						<td>
							<?php echo $food_order['FoodOrder']['time_nice']; ?>
						</td>
						<td><?php foreach ($food_order['FoodOrderItem'] as $item): ?>
								<div>
									<div style="float:right"><?php echo $item['price']; ?> DKK =</div>
									<?php echo $item['quantity']; ?> x <?php echo $item['Food']['title']; ?>
									<small>(<?php echo $item['Food']['description']; ?>)</small>
								</div>
								<?php $order_balance += $item['quantity'] * $item['price']; ?>
							<?php endforeach; ?></td>
						<td><?php echo $order_balance; ?> DKK</td>
						<?php if ($is_you): ?>
							<td>
								<?php
								if ($food_order['FoodOrder']['status'] == 0) {
									echo $this->Form->postLink(
											$this->Html->image('16x16_PNG/cancel.png') . ' Cancel order', array('controller' => 'food_orders', 'action' => 'delete', $food_order['FoodOrder']['id']), array('confirm' => "Are You sure you will delete this order?", 'escape' => false)
									);
								}
								?>
							</td>
						<?php endif; ?>
					</tr>
					<?php $total_balance += $order_balance; ?>
				<?php endforeach; ?>
				<tr>
					<td>Orders: <?php echo count($food_orders); ?></td>
					<td style="text-align:right;">Total amount spend on Sweets n' soda:</td>
					<td style="text-decoration: underline"><?php echo $total_balance; ?> DKK</td>
					<?php if ($is_you): ?>
						<td></td>
					<?php endif; ?>
				</tr>
			</tbody>
		</table>
	<?php endif; ?>
</div>