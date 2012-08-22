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

	<?php if ($user['User']['id'] == $current_user['id'] && $next_lan): ?>
		<div class="message">
			<p style="margin:0;">You are not signed up for <?php echo $next_lan['Lan']['title']; ?>! <?php echo $this->Html->link('Sign up now!', array('controller'=>'lan_signups', 'action'=>'add', $next_lan['Lan']['id'])); ?></p>
		</div>
	<?php endif; ?>


	<h3>User info</h3>

	<?php if ($current_user['id'] == $user['User']['id'] || $is_admin): ?>
		<div style="float:right;">Balance: <?php echo @$user['User']['balance']; ?></div>
	<?php endif; ?>



	<div>
		<ul style="margin: 0 0 20px;list-style: none;">
			<li>Email: <?php echo $user['User']['email']; ?></li>
			<li>Gamertag: <?php echo $user['User']['gamertag']; ?></li>
			<li>Type: <?php echo $user['User']['type']; ?></li>
			<li>ID-number: <?php echo $user['User']['id_number']; ?></li>
		</ul>
	</div>



	<div>
		<h3>LANs:</h3>
		<table>
			<tr>
				<th>Title</th>
				<th>Days attending</th>
			</tr>
			<?php foreach ($user['LanSignup'] as $lan_signup): ?>
				<tr>
					<td><?php echo $this->Html->link($lan_signup['Lan']['title'], array('controller' => 'lans', 'action' => 'view', $lan_signup['Lan']['id'])); ?></td>
					<td>
						<?php foreach($lan_signup['LanSignupDay'] as $day): ?>
							<?php echo $this->Time->format('l F jS Y', $day['LanDay']['date']); ?>,
						<?php endforeach; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>

	<div>
		<h3>Payments:</h3>
		<table>
			<tr>
				<th>Amount</th>
				<th>Time</th>
			</tr>
			<?php $total_balance = 0; ?>
			<?php foreach ($user['Payment'] as $payment): ?>
				<tr>
					<td><?php echo $payment['amount']; ?></td>
						<td><?php echo $payment['time']; ?></td>
				</tr>
				<?php $total_balance += $payment['amount'];
				?>

			<?php endforeach; ?>
				<tr>
					<td>Total payments: <?php echo $total_balance; ?></td>
					<td><?php echo count($user['Payment']); ?></td>
				</tr>
		</table>
	</div>

	<?php // pr($user); ?>
	<?php // pr($next_lan); ?>
</div>
