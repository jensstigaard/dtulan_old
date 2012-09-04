<?php $isAuth = ($user['User']['id'] == $current_user['id'] || $is_admin); ?>

<div class="form">
	<h1>Profile</h1>
	<?php if ($user['User']['id'] == $current_user['id']): ?>
		<div style="float:right">
			<?php echo $this->Html->link('Edit personal data', array('action' => 'editPersonalData')); ?>
		</div>
	<?php endif; ?>
	<h2><?php echo $user['User']['name']; ?></h2>

	<?php if ($user['User']['activated'] != 1 && $is_admin): ?>
		<div class="message">
			<p style="margin:0;">
				This user is not activated!
			</p>
		</div>
	<?php endif; ?>

	<?php if ($user['User']['id'] == $current_user['id'] && isset($next_lan['Lan']['title'])): ?>
		<div class="message">
			<p style="margin:0;">You are not signed up for <?php echo $next_lan['Lan']['title']; ?>! <?php echo $this->Html->link('Sign up now!', array('controller' => 'lan_signups', 'action' => 'add', $next_lan['Lan']['id'])); ?></p>
		</div>
	<?php endif; ?>

	<?php if ($user['User']['id'] == $current_user['id'] && isset($lan_invites['Lan']['title'])): ?>
		<div class="message success">
			<p style="margin:0;">You are invited to <?php echo $lan_invites['Lan']['title']; ?> by <?php echo $lan_invites['Student']['name']; ?>!
				<?php
				echo $this->Html->link('Accept and signup now', array(
					'controller' => 'lan_signups',
					'action' => 'add',
					$lan_invites['Lan']['id'])
				);
				?>
				|
				<?php
				echo $this->Form->postLink('Decline invite', array(
					'controller' => 'lan_invites',
					'action' => 'decline',
					$lan_invites['LanInvite']['id']), array(
					'confirm' => 'Do you want to DECLINE invite?'
						)
				);
				?></p>
		</div>
	<?php endif; ?>

	<?php // debug($lan_invites);   ?>

	<h3>User info</h3>

	<?php if ($isAuth): ?>
		<div style="float:right;">Balance: <?php echo @$user['User']['balance']; ?></div>
	<?php endif; ?>



	<div>
		<ul style="margin: 0 0 20px;list-style: none;">
			<li>Gamertag: <?php echo $user['User']['gamertag']; ?></li>
			<?php if ($isAuth): ?>
				<li>Email: <?php echo $user['User']['email']; ?></li>
				<li>Type: <?php echo $user['User']['type']; ?></li>
				<li>ID-number: <?php echo $user['User']['id_number']; ?></li>
				<li>Time created: <?php echo $this->Time->nice($user['User']['time_created']); ?></li>
				<li>Time activated: <?php echo $this->Time->nice($user['User']['time_activated']); ?></li>
			<?php endif; ?>
		</ul>
	</div>
	<?php
	/*
	  Teams that this profile is part of
	 */
	?>
	<div>
		<h3>Teams you're in:</h3>
		<table>
			<tr>
				<th>Name</th>
				<th>Tournament</th>
				<th>Leader</th>
				<th>MemberCount</th>
			</tr>
			<?php foreach ($teams as $team): ?>
				<tr>
					<td><?php echo $this->Html->link($team['Team']['name'], array('controller' => 'teams', 'action' => 'view', $team['Team']['id'])); ?></td>
					<td><?php echo $this->Html->link($team['Team']['Tournament']['title'], array('controller' => 'tournaments', 'action' => 'view', $team['Team']['Tournament']['id'])); ?></td>
					<td><?php echo $team['TeamUser']['is_leader'] ? 'Is leader' : ''; ?></td>
					<td><?php echo count($team['Team']['TeamUser']); ?> </td>
				</tr>
			<?php endforeach; ?>

		</table>
	</div>
	<?php
	/*
	  Lans that this profile is part of
	 */
	?>
	<div>
		<h3>LANs:</h3>
		<table>
			<tr>
				<th>Title</th>
				<th>Days attending</th>
			</tr>
			<?php if (!count($lans)): ?>
				<tr>
					<td colspan="2">
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
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</table>
	</div>

	<div>
		<h3>Payments:</h3>
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
						<td><?php echo $this->Time->nice($payment['time']); ?></td>
						<td><?php echo $payment['amount']; ?></td>
					</tr>
					<?php $total_balance += $payment['amount'];
					?>

				<?php endforeach; ?>
				<tr>
					<td>Total payments: <?php echo $total_balance; ?></td>
					<td><?php echo count($user['Payment']); ?></td>
				</tr>
			<?php endif; ?>
		</table>
	</div>


	<div>
		<h3>Pizza orders:</h3>
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
						<td>Total amount spend on pizzas:</td>
						<td><?php echo $total_balance; ?> DKK</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

	<?php // pr($user); ?>
	<?php // pr($next_lan); ?>
	<?php // pr($pizza_orders);  ?>
	<?php // pr($teams);   ?>
</div>
