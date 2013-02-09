<div>
	<h1>Food orders</h1>

	<?php if (!count($orders)): ?>

		<p>No orders yet</p>

	<?php else: ?>

		<table class="order_list">
			<tbody>
				
				<?php foreach ($orders as $order): ?>
				<?php	if(isset($order['header'])): ?>
						<tr>
							<td colspan="4">
								<h3 style="font-weight: normal;"><?php echo $order['header']; ?></h3>
							</td>
						</tr>
						<?php endif; ?>
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
							<?php echo $order['FoodOrder']['time_nice']; ?>
						</td>
						<td style="text-align: right;">
							<?php
							if ($order['FoodOrder']['status'] == 0) {
								echo $this->Html->link('<i class="icon-large icon-ok-sign"></i> Mark delivered', array(
									 'action' => 'mark_delivered',
									 $order['FoodOrder']['id']
										  ), array(
									 'escape' => false,
									 'class' => 'btn btn-small btn-success'
								));
							}
							?>
						</td>
					</tr>
					<tr>
						<td style="vertical-align:middle;">
							<?php
							echo $this->Html->image(
									  'http://www.gravatar.com/avatar/' . md5(strtolower($order['User']['email_gravatar'])) . '?s=64&amp;r=r', array('style' => 'height:64px;width:64px;'));
							?>
						</td>
						<td colspan="2">
							<table>
								<tbody>
									<?php $order_total = 0; ?>
									<?php foreach ($order['FoodOrderItem'] as $item): ?>
										<tr>
											<td style="width:20px;"><?php echo $item['quantity']; ?> x</td>
											<td><?php echo $item['price']; ?> DKK</td>
											<td>
												<div><?php echo $item['Food']['title']; ?></div>
												<?php if (strlen($item['Food']['description'])): ?>
													<small>(<?php echo $item['Food']['description']; ?>)</small>
												<?php endif; ?>
											</td>
											<td></td>

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

		<div style="text-align:center;">
			<?php
			echo $this->Paginator->numbers();
			?>
		</div>


	<?php endif; ?>

</div>