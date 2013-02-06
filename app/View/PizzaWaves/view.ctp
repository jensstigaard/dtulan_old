<div>
	<h1><?php echo __('Pizza wave'); ?></h1>
	<table>
		<tbody>
			<tr>
				<td>Time closure</td>
				<td><?php echo $this->Time->nice($pizza_wave['PizzaWave']['time_close']); ?></td>
			</tr>
			<tr>
				<td>Status</td>
				<td>
					<?php
					switch ($pizza_wave['PizzaWave']['status']) {
						case 0:
							echo'Not open<br />';
							echo $this->Html->link($this->Html->image('16x16_PNG/lock_open.png') . ' Mark as public', array('action' => 'mark_open', $pizza_wave['PizzaWave']['id']), array('escape' => false));
							break;
						case 1:
							echo'Open ';
							if ($pizza_wave['PizzaWave']['time_close'] >= date('Y-m-d H:i:s')) {
								echo' (Still taking orders)';
							} elseif (!count($pizza_wave_items)) {
								echo'(No pizzas in wave)';
							} else {
								echo $this->Html->link('Send email to pizzaria now', array('action' => 'send_email', $pizza_wave['PizzaWave']['id']));
							}
							break;
						case 2:
							echo'Waiting for delivering<br />';
							echo $this->Html->link('Mark as received', array('action' => 'mark_received', $pizza_wave['PizzaWave']['id']));
							break;
						case 3:
							echo'Pizza wave received';
							break;
						case 4:
							echo'Finished';
							break;
						default:
							break;
					}
					?>
				</td>
			</tr>
		</tbody>

	</table>
</div>
<?php if ($pizza_wave['PizzaWave']['status'] == 3): ?>
	<div>
		<h1><?php echo __('Orders in wave'); ?></h1>

		<?php if (!count($pizza_wave_orders)): ?>
			<p>No orders in wave</p>
		<?php else: ?>
			<table class="pizza_order_list">
				<thead>
					<tr>
						<th colspan="4">Orders</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($pizza_wave_orders as $order): ?>
						<?php
						switch ($order['PizzaOrder']['status']) {
							case 1:
								$class_status = 'order_status_green';
								break;

							case 2:
								$class_status = 'order_status_red';
								break;

							default:
								$class_status = 'order_status_yellow';
								break;
						}
						?>
						<tr>
							<td class="order_status <?php echo $class_status; ?>"></td>

							<td>
								<?php
								echo $this->Html->link($order['User']['name'], array(
									 'controller' => 'users',
									 'action' => 'profile',
									 $order['User']['id']
										  )
								);
								?><br />

								<?php echo $order['PizzaOrder']['time_nice']; ?>
							</td>
							<td>
								<ul>
									<?php foreach ($order['PizzaOrderItem'] as $item): ?>
										<li>
											<?php echo $item['quantity']; ?> x
											<?php echo $item['PizzaPrice']['Pizza']['title']; ?>
											(<?php echo $item['PizzaPrice']['PizzaType']['title']; ?>) (<?php echo $item['PizzaPrice']['Pizza']['number']; ?>)
										</li>
									<?php endforeach; ?>
								</ul>
							</td>
							<td>
								<?php
								switch ($order['PizzaOrder']['status']) {
									case 0:
										echo'<div class="btn-group">';
										echo $this->Html->link('<i class="icon icon-large icon-ok-sign"></i> Mark delivered', array(
											 'controller' => 'pizza_orders',
											 'action' => 'mark_delivered',
											 $order['PizzaOrder']['id']
												  ), array(
											 'escape' => false,
											 'class' => 'btn btn-mini btn-success'
												  )
										);
										echo $this->Html->link('<i class="icon icon-large icon-exclamation-sign"></i> With errors', array(
											 'controller' => 'pizza_orders',
											 'action' => 'mark_errors',
											 $order['PizzaOrder']['id']
												  ), array(
											 'escape' => false,
											 'class' => 'btn btn-mini btn-danger'
												  )
										);
										echo'</div>';
										break;
									case 1:

										break;
									case 2:
										echo'Marked as delivered with errors';
										break;
									default:
										echo'?';
										break;
								}
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
<?php else: ?>
	<div>
		<h2><?php echo __('Items in wave'); ?></h2>
		<?php if (!count($pizza_wave_items)): ?>
			<p>No items in wave</p>
		<?php else: ?>
			<table>
				<thead>
					<tr>
						<th>Quantity</th>
						<th>Pizza number</th>
						<th>Pizza</th>
						<th>Pizza type</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($pizza_wave_items as $item): ?>
						<tr>
							<td><?php echo $item['quantity']; ?></td>
							<td><?php echo $item['pizza_number']; ?></td>
							<td><?php echo $item['pizza_title']; ?></td>
							<td><?php echo $item['pizza_type']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
<?php endif; ?>