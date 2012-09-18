<div>
	<h1><?php echo $lan['Lan']['title']; ?></h1>
	<div style="float:left; width:54%">
		<h2 style="text-align: center"><?php echo $this->Html->image('32x32_PNG/globe.png'); ?> General info</h2>
		<table>
			<tbody>
				<tr style="font-size:110%">
					<td>Price:</td>
					<td><?php echo $lan['Lan']['price']; ?> DKK</td>
				</tr>
				<tr>
					<td>Date start:</td>
					<td><?php echo $this->Time->nice($lan['Lan']['time_start']); ?></td>
				</tr>
				<tr>
					<td>Date end:</td>
					<td><?php echo $this->Time->nice($lan['Lan']['time_end']); ?></td>
				</tr>
				<tr>
					<td>Participants:</td>
					<td><?php echo ($count_lan_signups - $count_lan_signups_guests) . 's + ' . $count_lan_signups_guests . 'g = ' . $count_lan_signups; ?></td>
				</tr>
				<?php if ($is_admin): ?>
                                <tr>
                                    <th colspan="2">Crew only</th>
                                </tr>
					<tr>
						<td>Invited (not accepted):</td>
						<td><?php echo count($lan_invites); ?></td>
					</tr>
					<tr>
						<td>Tournaments:</td>
						<td><?php echo count($tournaments); ?></td>
					</tr>
					<tr>
						<td>Public:</td>
						<td><?php echo $lan['Lan']['published'] ? 'Yes' : 'No'; ?></td>
					</tr>
					<tr>
						<td>Sign up open:</td>
						<td><?php echo $lan['Lan']['sign_up_open'] ? 'Yes' : 'No'; ?></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

	<div style="float:right; width:44%">
		<h2 style="text-align: center"><?php echo $this->Html->image('32x32_PNG/clock.png'); ?> Days in LAN</h2>
		<table>
			<thead>
				<tr>
					<th>Date</th>
					<th>Seats left</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($lan_days as $lan_day): ?>
					<?php $seats_left = $lan_day['Lan']['max_participants'] - count($lan_day['LanSignupDay']); ?>
					<tr>
						<td><?php echo $this->Time->format('D, M jS', $lan_day['LanDay']['date']); ?></td>
						<td>
							<?php
							if (!$seats_left) {
								$color = 'red';
							} elseif ($seats_left < 10) {
								$color = 'yellow';
							} else {
								$color = 'green';
							}
							?>
							<span style="color:<?php echo $color; ?>;"><?php echo $seats_left; ?></span>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div style="clear:both;"></div>
</div>

<?php if (isset($user_guests) && count($user_guests)): ?>
	<div>
		<h2><?php echo $this->Html->image('32x32_PNG/user_add.png'); ?> Invite guest</h2>
		<?php echo $this->Form->create('LanInvite'); ?>
		<?php echo $this->Form->input('user_guest_id', array('label' => 'Invite guest to LAN', 'options' => $user_guests)); ?>
		<?php echo $this->Form->end('Invite'); ?>
	</div>
<?php endif; ?>


<?php if (isset($is_not_attending) && $is_not_attending): ?>
	<div>
		<h2><?php echo $this->Html->image('24x24_PNG/001_01.png'); ?> Sign up now!</h2>
		<p>You are not anticipating to this LAN.</p>
		<?php echo $this->Html->link('Sign up this LAN!', array('controller' => 'lan_signups', 'action' => 'add', $lan['Lan']['id'])); ?>
	</div>
<?php endif; ?>
<?php if ($is_admin): ?>
<div>
    <h1>Crew only</h1>
</div>
	<?php
// Reset total
	$total_lan = 0;

// For signups
	$total_lan += $count_lan_signups * $lan['Lan']['price'];

// For pizzas
	$total_lan += $total_pizzas;
	?>
	<div>
		<h2>Economics</h2>
		<table>
			<thead>
				<tr>
					<th>Post</th>
					<th style="text-align: center">Quantity</th>
					<th></th>
					<th style="text-align: left">Price</th>
					<th></th>
					<th style="text-align: right">Total</th>

				</tr>
			</thead>
			<tbody>
				<tr>
					<td>User signups</td>
					<td style="text-align: center"><?php echo $count_lan_signups; ?></td>
					<td style="text-align: right">DKK</td>
					<td style="text-align: right"><?php echo $lan['Lan']['price']; ?></td>
					<td style="text-align: right">DKK</td>
					<td style="text-align: right"><?php echo $count_lan_signups * $lan['Lan']['price']; ?></td>

				</tr>
				<tr>
					<td>Candy &amp; soda</td>
					<td style="text-align: center">0</td>
					<td style="text-align: right">DKK</td>
					<td style="text-align: right">0</td>
					<td style="text-align: right">DKK</td>
					<td style="text-align: right">0</td>

				</tr>
				<tr>
					<td>Pizza orders</td>
					<td style="text-align: center"><?php echo $total_pizza_orders; ?></td>
					<td style="text-align: right">DKK</td>
					<td style="text-align: right">~ <?php echo $total_pizza_orders > 0 ? floor($total_pizzas / $total_pizza_orders) : 0; ?></td>
					<td style="text-align: right">DKK</td>
					<td style="text-align: right"><?php echo $total_pizzas; ?></td>

				</tr>
				<tr>
					<th>Total</th>
					<th colspan="3"></th>
					<th style="text-align: right">DKK</th>
					<th style="text-align: right"><?php echo $total_lan; ?></th>

				</tr>
			</tbody>
		</table>
	</div>
<?php endif; ?>

<?php if ($is_admin && count($lan_invites)): ?>
	<div>
		<h2><?php echo $this->Html->image('32x32_PNG/users_two.png'); ?> Users invited (not accepted)</h2>
		<table>
			<thead>
				<tr>
					<th style="width:28px"></th>
					<th>Name</th>
					<th>Invited by</th>
					<th>Time invited</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($lan_invites as $invite): ?>
					<tr>
						<td style="padding:0 2px;text-align:center;">
							<?php
							if (!empty($invite['Guest']['email_gravatar'])) {
								echo $this->Html->image(
										'http://www.gravatar.com/avatar/' . md5(strtolower($invite['Guest']['email_gravatar'])) . '?s=24&amp;r=r', array(
									'alt' => $invite['Guest']['name'],
									'title' => $invite['Guest']['name'] . ' gravatar',
									'style' => ''
										)
								);
							}
							?>
						</td>
						<td>
							<?php echo $this->Html->link($invite['Guest']['name'], array('controller' => 'users', 'action' => 'profile', $invite['Guest']['id'])); ?>
						</td>
						<td>
							<?php echo $this->Html->link($invite['Student']['name'], array('controller' => 'users', 'action' => 'profile', $invite['Student']['id'])); ?>
						</td>
						<td><?php echo $this->Time->nice($invite['LanInvite']['time_invited']); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>
<?php if($is_admin):?>
<div>
    <h1>Viewable by users</h1>
</div>
<?php endif;?>
<div>
	<h2><?php echo $this->Html->image('32x32_PNG/users_two.png'); ?> Crew</h2>
	<table>
		<thead>
			<tr>
				<th style="width:28px"></th>
				<th>Name</th>
				<th>Gamertag</th>
				<?php if ($is_admin): ?>
					<th style="text-align: center">Days attending</th>
					<th style="text-align: right">Phone number</th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($lan_signups_crew as $user): ?>
				<tr>
					<td style="padding:0 2px;text-align:center;">
						<?php
						if (!empty($user['User']['email_gravatar'])) {
							echo $this->Html->image(
									'http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=24&amp;r=r', array(
								'alt' => $user['User']['name'],
								'title' => $user['User']['name'] . ' gravatar',
								'style' => ''
									)
							);
						}
						?>
					</td>
					<td>
						<?php echo $this->Html->link($user['User']['name'], array('controller' => 'users', 'action' => 'profile', $user['User']['id'])); ?>

					</td>
					<td><?php echo $user['User']['gamertag']; ?></td>
					<?php if ($is_admin): ?>
						<td style="text-align: center">
							<?php echo count($user['LanSignupDay']); ?> days
						</td>
						<td style="text-align: right">
							<?php echo $user['User']['phonenumber'] ?>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<div>
	<h2><?php echo $this->Html->image('32x32_PNG/users_two.png'); ?> Signups for this LAN</h2>
	<table>
		<thead>
			<tr>
				<th style="width:28px;"></th>
				<th><?php echo $this->Paginator->sort('User.name', 'Name'); ?></th>
				<th><?php echo $this->Paginator->sort('User.gamertag', 'Gamertag'); ?></th>
				<?php if ($is_admin): ?>
					<th style="text-align: center">Days attending</th>
					<th style="text-align: right"><?php echo $this->Paginator->sort('User.phonenumber', 'Phone number'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($lan_signups as $user): ?>
				<tr>
					<td style="padding:0 2px;text-align:center;">
						<?php
						if (!empty($user['User']['email_gravatar'])) {
							echo $this->Html->image(
									'http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=24&amp;r=r', array(
								'alt' => $user['User']['name'],
								'title' => $user['User']['name'] . ' gravatar',
								'style' => ''
									)
							);
						}
						?>
					</td>
					<td>
						<?php echo $this->Html->link($user['User']['name'], array('controller' => 'users', 'action' => 'profile', $user['User']['id'])); ?>
						<?php if ($user['User']['type'] == 'guest'): ?>
							(g)
						<?php endif; ?>
					</td>
					<td><?php echo $user['User']['gamertag']; ?></td>
					<?php if ($is_admin): ?>
						<td style="text-align: center">
							<?php echo count($user['LanSignupDay']); ?> days
						</td>
						<td style="text-align: right">
							<?php echo $user['User']['phonenumber'] ?>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div style="text-align: center">
		<?php echo $this->Paginator->numbers(); ?>
	</div>
</div>


<div>
	<?php if ($is_admin): ?>
		<div style="float:right">
			<?php echo $this->Html->link('New tournament', array('controller' => 'tournaments', 'action' => 'add', $lan['Lan']['id'])); ?>
		</div>
	<?php endif; ?>
	<h2><?php echo $this->Html->image('32x32_PNG/trophy_gold.png'); ?> Tournaments (<?php echo count($tournaments); ?>)</h2>
	<table>
		<thead>
			<tr>
				<th>Title</th>
				<th>Game title</th>
				<th style="text-align: center">Team size</th>
				<th style="text-align: right">Participants</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!count($tournaments)): ?>
				<tr>
					<td colspan="4">
						No tournaments published yet
					</td>
				</tr>
			<?php else: ?>
				<?php foreach ($tournaments as $tournament): ?>
					<tr>
						<td><?php echo $this->Html->link($tournament['Tournament']['title'], array('controller' => 'tournaments', 'action' => 'view', $tournament['Tournament']['id'])); ?></td>
						<td><?php echo $tournament['Game']['title'] ?></td>
						<td style="text-align: center"><?php echo $tournament['Tournament']['team_size'] ?></td>
						<td style="text-align: right"><?php //Participants	                              ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php // pr($pizza_waves);  ?>
