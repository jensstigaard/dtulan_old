<div>
	<h1>
		<?php
		if (!empty($user['User']['email_gravatar'])) {
			echo $this->Html->image(
					'http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=64&amp;r=r', array('style' => 'margin-right:10px;'));
		}
		echo $user['User']['name'];
		?>
	</h1>

	<?php if (isset($make_payment_crew_id)): ?>
		<div style="float:right;width:200px;background-color:rgba(0,0,0,.2);padding:10px;">
			<?php echo $this->Form->create('Payment', array('controller' => 'payments', 'action' => 'add')); ?>
			<?php echo $this->Form->input('amount', array('label' => 'Make payment')); ?>
			<?php echo $this->Form->hidden('user_id', array('value' => $user['User']['id'])); ?>
			<?php echo $this->Form->hidden('crew_id', array('value' => $make_payment_crew_id)); ?>
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
				<?php if ($is_auth): ?>
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
						<td>Phonenumber:</td>
						<td><?php echo $user['User']['phonenumber']; ?></td>
					</tr>
					<tr style="font-size:13pt;">
						<td>Balance:</td>
						<td><?php echo $user['User']['balance']; ?></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<div style="clear:both"></div>
</div>

<?php if ($user['User']['activated'] != 1 && $is_admin): ?>
	<div class="message">
		This user is not activated!
	</div>
<?php endif; ?>



<?php if ($is_auth): ?>
	<div>
		<div>
			<h2>
				<?php echo $this->Html->image('32x32_PNG/payment_cash.png'); ?>
				Payments
			</h2>

			<?php if (!count($user['Payment'])): ?>
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
					<?php foreach ($user['Payment'] as $payment): ?>
						<tr>
							<td>
								<?php
								if ($this->Time->isToday($payment['time'])) {
									echo'Today';
								} elseif ($this->Time->isTomorrow($payment['time'])) {
									echo'Tomorrow';
								} elseif ($this->Time->wasYesterday($payment['time'])) {
									echo'Yesterday';
								} elseif ($this->Time->isThisWeek($payment['time'])) {
									echo $this->Time->format('l', $payment['time']);
								} else {
									echo $this->Time->format('D, M jS', $payment['time']);
								}
								?>
								<?php echo $this->Time->format('H:i', $payment['time']); ?>

							</td>
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
				</table>
			<?php endif; ?>
		</div>
	</div>

	<div>
		<div>
			<h2>
				<?php echo $this->Html->image('32x32_PNG/pizza.png'); ?>
				Pizza orders
			</h2>
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
									<?php
									if ($this->Time->isToday($pizza_order['PizzaOrder']['time'])) {
										echo'Today';
									} elseif ($this->Time->isTomorrow($pizza_order['PizzaOrder']['time'])) {
										echo'Tomorrow';
									} elseif ($this->Time->wasYesterday($pizza_order['PizzaOrder']['time'])) {
										echo'Yesterday';
									} elseif ($this->Time->isThisWeek($pizza_order['PizzaOrder']['time'])) {
										echo $this->Time->format('l', $pizza_order['PizzaOrder']['time']);
									} else {
										echo $this->Time->format('D, M jS', $pizza_order['PizzaOrder']['time']);
									}
									?>
									<?php echo $this->Time->format('H:i', $pizza_order['PizzaOrder']['time']); ?>

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
										if ($pizza_order['is_cancelable']) {
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
	</div>

	<div>
		<div>
			<h2>
				<?php // echo $this->Html->image('32x32_PNG/clock.png'); ?>
				Sweets n' soda - orders
			</h2>
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
									<?php
									if ($this->Time->isToday($food_order['FoodOrder']['time'])) {
										echo'Today';
									} elseif ($this->Time->isTomorrow($food_order['FoodOrder']['time'])) {
										echo'Tomorrow';
									} elseif ($this->Time->wasYesterday($food_order['FoodOrder']['time'])) {
										echo'Yesterday';
									} elseif ($this->Time->isThisWeek($food_order['FoodOrder']['time'])) {
										echo $this->Time->format('l', $food_order['FoodOrder']['time']);
									} else {
										echo $this->Time->format('D, M jS', $food_order['FoodOrder']['time']);
									}
									?>
									<?php echo $this->Time->format('H:i', $food_order['FoodOrder']['time']); ?>
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
	</div>
<?php endif; ?>


<div>
	<?php
	/*
	  Teams that this profile is part of
	 */
	?>
	<div>
		<h2>
			<?php echo $this->Html->image('32x32_PNG/trophy_gold.png'); ?>
			Tournaments
		</h2>
		<?php if (!count($teams)): ?>
			<p>You do not participate in any tournament</p>
		<?php else: ?>
			<table>
				<tr>
					<th>Tournament</th>
					<th>Name</th>
					<th>Leader</th>
					<th>Members</th>
				</tr>

				<?php foreach ($teams as $team): ?>
					<tr>
						<td><?php echo $this->Html->link($team['Team']['Tournament']['title'], array('controller' => 'tournaments', 'action' => 'view', $team['Team']['Tournament']['id'])); ?></td>
						<td><?php echo $this->Html->link($team['Team']['name'], array('controller' => 'teams', 'action' => 'view', $team['Team']['id'])); ?></td>
						<td><?php echo $team['TeamUser']['is_leader'] ? $this->Html->image('16x16_PNG/star.png') : ''; ?></td>
						<td><?php echo count($team['Team']['TeamUser']); ?> </td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>
</div>


<div>
	<?php
	/*
	  Lans that this profile is part of
	 */
	?>
	<div>
		<h2>
			<?php echo $this->Html->image('32x32_PNG/games.png'); ?>
			LANs
		</h2>
		<?php if (!count($lans)): ?>
			<p>Not signed up for any LANs</p>
		<?php else: ?>
			<table>
				<tr>
					<th>Title</th>
					<th>Days attending</th>
					<?php if ($is_auth): ?>
						<th>Guests of you</th>
					<?php endif; ?>
				</tr>


				<?php foreach ($lans as $lan): ?>
					<?php if ($lan['Lan']['published'] || $is_auth): ?>
						<tr>
							<td>
								<?php echo $this->Html->link($lan['Lan']['title'], array('controller' => 'lans', 'action' => 'view', $lan['Lan']['slug'])); ?>

								<?php if ($is_you && $lan['Lan']['sign_up_open']): ?>
									<br />
									<?php
									echo $this->Html->link(
											$this->Html->image('16x16_GIF/reply.gif') . ' Edit your signup', array('controller' => 'lan_signups', 'action' => 'edit', $lan['Lan']['id']), array('escape' => false)
									);
									?>
								<?php endif; ?>
								<?php if (isset($lan['LanInvite']['Student'])): ?>
									<br />
									<small>Invited by: <?php echo $this->Html->link($lan['LanInvite']['Student']['name'], array('controller' => 'users', 'action' => 'profile', $lan['LanInvite']['Student']['id'])); ?></small>
								<?php endif; ?>
								<?php if ($is_auth && count($lan['Lan']['LanInvite'])): ?>

								<?php endif; ?>
							</td>
							<td>
								<?php foreach ($lan['LanSignupDay'] as $day): ?>
									<?php echo $this->Time->format('M jS (l)', $day['LanDay']['date']); ?><br />
								<?php endforeach; ?>
							</td>

							<?php if ($is_auth): ?>
								<td>
									<?php if (isset($lan_invites_accepted) && count($lan_invites_accepted)): ?>
										<?php foreach ($lan_invites_accepted[$lan['Lan']['id']] as $invite_accepted): ?>
											<?php echo $this->Html->link($invite_accepted['Guest']['name'], array('controller' => 'users', 'action' => 'profile', $invite_accepted['Guest']['id'])); ?><br />
										<?php endforeach; ?>
									<?php endif; ?>
								</td>
							<?php endif; ?>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>
</div>