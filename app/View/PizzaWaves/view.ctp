<?php echo $this->Html->script(array('admin/pizza/waves/view'), array('inline' => false)); ?>

<div class="box">

	<div style="float:right;">
		<?php
		echo $this->Html->link('<i class="icon-large icon-pencil"></i> Edit', array(
			 'controller' => 'pizza_waves',
			 'action' => 'edit',
			 $pizza_wave['PizzaWave']['id']
				  ), array(
			 'class' => 'btn btn-small btn-warning',
			 'escape' => false
				  )
		);
		?>
	</div>

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
<div class="box">
	<?php if ($pizza_wave['PizzaWave']['status'] == 3): ?>

		<h1><?php echo __('Orders in wave'); ?></h1>

		<form action="<?php
	echo $this->Html->url(array(
		 'controller' => 'pizza_orders',
		 'action' => 'index',
		 'ext' => 'json'
	));
		?>" data-pizza-wave-id="<?php echo $pizza_wave['PizzaWave']['id']; ?>" id="search_pizza_orders">
			<table>
				<thead>
					<tr>
						<th colspan="2">Search in pizza orders</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><input type="text" name="pizza_orders_search_user" placeholder="Users name" /></td>
						<td><label><input type="checkbox" name="pizza_orders_only_not_delivered" /> Show only "not delivered"</label></td>
					</tr>
				</tbody>
			</table>
		</form>

		<table class="pizza_order_list" id="pizza_order_list" data-link-mark-delivered="<?php
			echo $this->Html->url(array(
				 'controller' => 'pizza_orders',
				 'action' => 'mark_delivered',
			));
			?>" data-link-mark-errors="<?php
			echo $this->Html->url(array(
				 'controller' => 'pizza_orders',
				 'action' => 'mark_errors',
			));
			?>" data-link-user="<?php
			echo $this->Html->url(array(
				 'controller' => 'users',
				 'action' => 'view'
			));
			?>">
			<thead>
				<tr>
					<th colspan="4">Orders</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="color:grey;">Loading orders...</td>
				</tr>
			</tbody>
		</table>

	<?php else: ?>
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
<?php endif; ?>
</div>