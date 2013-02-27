<div class="box">
	<h2>Food orders in <?php echo $lan_food_menu['FoodMenu']['title']; ?> at <?php echo $lan_food_menu['Lan']['title']; ?></h2>

	<?php if (!count($orders)): ?>

		<p>No orders yet</p>

	<?php else: ?>

		<table class="order_list">
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
								'action' => 'view',
								$order['User']['id']
									)
							);
							?>
						</td>
						<td>
							<?php echo $order['FoodOrder']['time_nice']; ?>
						</td>
						<td>
							<?php
							if ($order['FoodOrder']['status'] == 0) {
								echo $this->Html->link($this->Html->image('16x16_GIF/arrow_next.gif') . 'Mark delivered', array(
									'action' => 'mark_delivered',
									$order['FoodOrder']['id'],
										), array(
									'escape' => false
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
											<td><?php echo $item['quantity']; ?> x <?php echo $item['price']; ?> DKK</td>
											<td><?php echo $item['Food']['title']; ?><br />
												<small>(<?php echo $item['Food']['description']; ?>)</small>
											</td>
											<td><?php echo $item['price'] * $item['quantity']; ?> DKK</td>
										</tr>
										<?php $order_total += $item['price'] * $item['quantity']; ?>
									<?php endforeach; ?>
									<tr>
										<td colspan="2" style="text-align:right;">Total:</td>
										<td><?php echo $order_total; ?> DKK</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div style="text-align:center;">
			<?php
			echo $this->Paginator->numbers();
			?>
		</div>
	<?php endif; ?>
</div>