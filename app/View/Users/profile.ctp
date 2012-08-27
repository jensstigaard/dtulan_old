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

	<?php if ($user['User']['id'] == $current_user['id'] && $next_lan): ?>
		<div class="message">
			<p style="margin:0;">You are not signed up for <?php echo $next_lan['Lan']['title']; ?>! <?php echo $this->Html->link('Sign up now!', array('controller' => 'lan_signups', 'action' => 'add', $next_lan['Lan']['id'])); ?></p>
		</div>
	<?php endif; ?>


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
				<th>Leader</th>
				<th>MemberCount</th>
			</tr>
			<?php foreach ($user['Team'] as $team): ?>
				<tr>
					<td><?php echo $this->Html->link($team['name'], array('controller' => 'teams', 'action' => 'view', $team['id'])); ?></td>
					<td><?php //Leader ?></td>
					<td><?php //Member count ?> </td>
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
			<?php if (!count($user['LanSignup'])): ?>
				<tr>
					<td colspan="2">
						Not signed up for any LANs
					</td>
				</tr>
			<?php else: ?>
				<?php foreach ($user['LanSignup'] as $lan_signup): ?>
					<tr>
						<td><?php echo $this->Html->link($lan_signup['Lan']['title'], array('controller' => 'lans', 'action' => 'view', $lan_signup['Lan']['id'])); ?></td>
						<td>
							<ul>


								<?php foreach ($lan_signup['LanSignupDay'] as $day): ?>
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

	<?php  pr($user); ?>
	<?php // pr($next_lan); ?>
</div>
