<?php $isAuth = ($user['User']['id'] == $current_user['id'] || $is_admin); ?>

<div>
	<h1>
		<?php
		if (!empty($user['User']['email_gravatar'])) {
			echo $this->Html->image(
					'http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=64', array('style' => 'margin-right:10px;'));
		}
		echo $user['User']['name'];
		?>
	</h1>

	<?php if ($is_admin): ?>
		<div style="float:right;width:200px;background-color:rgba(0,0,0,.2);padding:10px;">
			<?php echo $this->Form->create('Payment', array('controller' => 'payments', 'action' => 'add')); ?>
			<?php echo $this->Form->input('amount', array('label' => 'Make payment')); ?>
			<?php echo $this->Form->input('user_id', array('value' => $user['User']['id'], 'type' => 'hidden')); ?>
			<?php echo $this->Form->input('crew_id', array('value' => $current_user['id'], 'type' => 'hidden')); ?>
			<?php echo $this->Form->end(__('Submit')); ?>
		</div>
	<?php endif; ?>

	<div>
		<table style="width:400px;clear:none;">
			<tbody>
				<tr>
					<td>Gamertag:</td>
					<td><?php echo $user['User']['gamertag']; ?></td>
				</tr>
				<?php if ($isAuth): ?>
					<tr>
						<td>Email:</td>
						<td><?php echo $user['User']['email']; ?></td>
					</tr>
					<tr>
						<td>Type:</td>
						<td><?php echo $user['User']['type']; ?></td>
					</tr>
					<tr>
						<td>ID-number:</td>
						<td><?php echo $user['User']['id_number']; ?></td>
					</tr>
					<tr>
						<td>Time created:</td>
						<td><?php echo $this->Time->nice($user['User']['time_created']); ?></td>
					</tr>
					<tr>
						<td>Time activated:</td>
						<td><?php echo $this->Time->nice($user['User']['time_activated']); ?></td>
					</tr>
					<tr style="font-size:13pt;">
						<td>Balance:</td>
						<td><?php echo $user['User']['balance']; ?></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<?php if ($user['User']['activated'] != 1 && $is_admin): ?>
	<div class="message">
		This user is not activated!
	</div>
<?php endif; ?>


<div>
	<?php
	/*
	  Teams that this profile is part of
	 */
	?>
	<div>
		<h2><?php echo $this->Html->image('32x32_PNG/trophy_gold.png'); ?> Tournaments</h2>
		<table>
			<tr>
				<th>Tournament</th>
				<th>Name</th>
				<th>Leader</th>
				<th>Members</th>
			</tr>
			<?php if (!count($teams)): ?>
				<tr>
					<td colspan="4">
						Not anticipating in any tournaments
					</td>
				</tr>
			<?php else: ?>
				<?php foreach ($teams as $team): ?>
					<tr>
						<td><?php echo $this->Html->link($team['Team']['Tournament']['title'], array('controller' => 'tournaments', 'action' => 'view', $team['Team']['Tournament']['id'])); ?></td>
						<td><?php echo $this->Html->link($team['Team']['name'], array('controller' => 'teams', 'action' => 'view', $team['Team']['id'])); ?></td>
						<td><?php echo $team['TeamUser']['is_leader'] ? 'Is leader' : ''; ?></td>
						<td><?php echo count($team['Team']['TeamUser']); ?> </td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>

		</table>
	</div>


</div>

<div>
	<?php
	/*
	  Lans that this profile is part of
	 */
	?>
	<div>
		<h2><?php echo $this->Html->image('32x32_PNG/games.png'); ?> LANs</h2>
		<table>
			<tr>
				<th>Title</th>
				<th>Days attending</th>
				<?php if ($isAuth): ?>
					<th>Actions</th>
				<?php endif; ?>
			</tr>
			<?php if (!count($lans)): ?>
				<tr>
					<td colspan="3">
						Not signed up for any LANs
					</td>
				</tr>
			<?php else: ?>
				<?php foreach ($lans as $lan): ?>
					<tr>
						<td><?php echo $this->Html->link($lan['Lan']['title'], array('controller' => 'lans', 'action' => 'view', $lan['Lan']['id'])); ?></td>
						<td>
							<ul>


								<?php foreach ($lan['LanSignupDay'] as $day): ?>
									<li><?php echo $this->Time->format('D M jS', $day['LanDay']['date']); ?></li>
								<?php endforeach; ?>
							</ul>
						</td>
						<?php if ($isAuth): ?>
							<td>
								<?php if ($user['User']['id'] == $current_user['id'] && $lan['Lan']['sign_up_open']): ?>
									<?php
									echo $this->Html->link(
											$this->Html->image('16x16_GIF/reply.gif') . ' Edit your signup', array('controller' => 'lan_signups', 'action' => 'edit', $lan['Lan']['id']), array('escape' => false)
									);
									?>
									<br />
									<?php
									echo $this->Form->postLink(
											$this->Html->image('16x16_GIF/action_delete.gif') . ' Delete signup', array('controller' => 'lan_signups', 'action' => 'delete', $lan['Lan']['id']), array('confirm' => 'Are You sure you will delete the signup?', 'escape' => false)
									);
									?>
								<?php endif; ?>
							</td>
							<?php endif; ?>
					</tr>
					<?php endforeach; ?>
			<?php endif; ?>
		</table>
	</div>

</div>


<?php if ($isAuth): ?>
	<div>
		<div>
			<h2><?php echo $this->Html->image('32x32_PNG/payment_cash.png'); ?> Payments</h2>
			<table>
				<tr>
					<th>Time</th>
					<th>Amount</th>
				</tr>
	<?php if (!count($user['Payment'])): ?>
					<tr>
						<td colspan="2">
							No payments registered
						</td>
					</tr>
	<?php else: ?>
					<?php $total_balance = 0; ?>
					<?php foreach ($user['Payment'] as $payment): ?>
						<tr>
							<td><?php echo $this->Time->isToday($payment['time']) ? 'Today, ' . $this->Time->format('H:i', $payment['time']) : $this->Time->nice($payment['time']); ?></td>
							<td><?php echo $payment['amount']; ?> DKK</td>
						</tr>
			<?php $total_balance += $payment['amount'];
			?>

					<?php endforeach; ?>
					<tr>
						<td>
							<div style="float:right">Total payments:</div>
							Payments made: <?php echo count($user['Payment']); ?>
						</td>
						<td><?php echo $total_balance; ?> DKK</td>
					</tr>
	<?php endif; ?>
			</table>
		</div>
	</div>

	<div>
		<div>
			<h2><?php echo $this->Html->image('32x32_PNG/pizza.png'); ?> Pizza orders</h2>
			<table>
				<thead>
					<tr>
						<th>Time</th>
						<th>Items</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
	<?php if (!count($pizza_orders)): ?>
						<tr>
							<td colspan="2">
								No orders registered
							</td>
						</tr>
	<?php else: ?>
						<?php $total_balance = 0; ?>
						<?php foreach ($pizza_orders as $pizza_order): ?>
							<?php $order_balance = 0; ?>
							<tr>
								<td><?php echo $this->Time->nice($pizza_order['PizzaOrder']['time']); ?></td>
								<td><?php foreach ($pizza_order['PizzaOrderItem'] as $item): ?>
										<div>
											<div style="float:right"><?php echo $item['price']; ?> DKK =</div>
				<?php echo $item['amount']; ?> x <?php echo $item['PizzaPrice']['Pizza']['title']; ?>
											<small>(<?php echo $item['PizzaPrice']['PizzaType']['title']; ?>)</small>
										</div>
				<?php $order_balance += $item['amount'] * $item['price']; ?>
									<?php endforeach; ?></td>
								<td><?php echo $order_balance; ?> DKK</td>
							</tr>
			<?php $total_balance += $order_balance; ?>
						<?php endforeach; ?>
						<tr>
							<td>Orders: <?php echo count($pizza_orders); ?></td>
							<td style="text-align:right;">Total amount spend on pizzas:</td>
							<td style="text-decoration: underline"><?php echo $total_balance; ?> DKK</td>
						</tr>
	<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php endif; ?>

<?php // pr($user); ?>
<?php // pr($next_lan);  ?>
<?php // pr($pizza_orders);  ?>
<?php
// pr($teams); ?>