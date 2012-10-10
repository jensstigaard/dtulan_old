<div>
	<h1>Food orders</h1>

	<?php if(!count($orders)): ?>

	<p>No orders yet</p>

	<?php else: ?>

	<table class="order_list">
		<thead>
			<tr>
				<th></th>
				<th>User</th>
				<th>Time</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($orders as $order): ?>
				<tr>
					<td rowspan="2" class="order_status <?php
			switch ($order['FoodOrder']['status']) {
				case 0:
					echo'order_status_yellow';
					break;
				case 1:
					echo'order_status_green';
					break;
				default:
					echo'order_status_red';
					break;
			}
				?>">
					</td>
					<td>
						<?php
						echo $this->Html->link($order['User']['name'], array(
							'controller' => 'users',
							'action' => 'profile',
							$order['User']['id']
								)
						);
						?>
					</td>
					<td>
						<?php
						if ($this->Time->isToday($order['FoodOrder']['time'])) {
							echo'Today';
						} elseif ($this->Time->isTomorrow($order['FoodOrder']['time'])) {
							echo'Tomorrow';
						} elseif ($this->Time->isThisWeek($order['FoodOrder']['time'])) {
							echo $this->Time->format('l', $order['FoodOrder']['time']);
						} else {
							echo $this->Time->format('D, M jS', $order['FoodOrder']['time']);
						}
						?>
						<?php echo $this->Time->format('H:i', $order['FoodOrder']['time']); ?>
					</td>
					<td>
						<?php
						if ($order['FoodOrder']['status'] == 0) {
							echo $this->Html->link('Mark delivered', array(
								'action' => 'mark_delivered',
								$order['FoodOrder']['id']
							));
						}
						?>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<table>
							<tbody>
	<?php $order_total = 0; ?>
	<?php foreach ($order['FoodOrderItem'] as $item): ?>
									<tr>
										<td><?php echo $item['quantity']; ?> x</td>
										<td><?php echo $item['Food']['title']; ?><br />
											<small>(<?php echo $item['Food']['description']; ?>)</small>
										</td>
										<td></td>
										<td><?php echo $item['price']; ?> DKK</td>
										<td><?php echo $item['price'] * $item['quantity']; ?> DKK</td>
									</tr>
		<?php $order_total += $item['price'] * $item['quantity']; ?>
	<?php endforeach; ?>
								<tr>
									<td colspan="4" style="text-align:right;">Total:</td>
									<td><?php echo $order_total; ?> DKK</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</div>