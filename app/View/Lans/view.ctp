<div>
	<h1><?php echo $lan['Lan']['title']; ?></h1>
	<div style="float:left; width:54%">
		<h2 style="text-align: center"><?php echo $this->Html->image('32x32_PNG/globe.png'); ?> General info</h2>
		<table>
			<tbody>
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
					<td><?php echo ($count_lan_signups - $count_lan_signups_guests).'s + '. $count_lan_signups_guests .'g = '. $count_lan_signups; ?></td>
				</tr>
				<tr style="font-size:110%">
					<td>Price:</td>
					<td><?php echo $lan['Lan']['price']; ?> DKK</td>
				</tr>
				<?php if ($is_admin): ?>
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


<?php if (isset($is_not_attending) && $is_not_attending == 1): ?>
	<div>
		<h2><?php echo $this->Html->image('24x24_PNG/001_01.png'); ?> Sign up now!</h2>
		<p>You are not anticipating to this LAN.</p>
		<?php echo $this->Html->link('Sign up this LAN!', array('controller' => 'lan_signups', 'action' => 'add', $lan['Lan']['id'])); ?>
	</div>
<?php endif; ?>

<div>
	<h2><?php echo $this->Html->image('32x32_PNG/users_two.png'); ?> Crew</h2>
            <table>
                <thead>
                    <tr>
                        <th style="width:28px"></th>
                        <th>Name</th>
                        <th>Gamertag</th>
                        <?php if($is_admin):?>
                            <th>Days attending</th>
                            <th>Phone number</th>
                        <?php endif;?>
                    </tr>   
                </thead>
                <tbody>
		<?php foreach($lan_signups_crew as $user):?>
                    <tr>
                        <td style="padding:0 2px;text-align:center;">
                            <?php
				if (!empty($user['User']['email_gravatar'])) {
                                    echo $this->Html->image(
                                            'http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=24', array(
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
						<td>
							<?php echo count($user['LanSignupDay']); ?> days
						</td>
                                                <td>
                                                    <?php echo $user['User']['phonenumber']?>
                                                </td>
					<?php endif; ?>
                    </tr>
                <?php endforeach;?>
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
					<th>Days attending</th>
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
									'http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=24', array(
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
						<td>
							<?php echo count($user['LanSignupDay']); ?> days
						</td>
                                                <td>
                                                    <?php echo $user['User']['phonenumber']?>
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
				<th>Team size</th>
				<th>Participants</th>
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
						<td><?php echo $tournament['Tournament']['team_size'] ?></td>
						<td><?php //Participants	                         ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php if($is_admin): ?>
<?php

// Reset total
$total_lan = 0;

// For signups
$total_lan += $count_lan_signups*$lan['Lan']['price'];

// For pizzas
$total_lan += $total_pizzas;


?>
<div>
	<h1>Economics</h1>
	<table>
		<thead>
			<tr>
				<th>Post</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>User signups</td>
				<td><?php echo $count_lan_signups; ?></td>
				<td><?php echo $lan['Lan']['price']; ?> DKK</td>
				<td><?php echo $count_lan_signups*$lan['Lan']['price']; ?> DKK</td>
			</tr>
			<tr>
				<td>Candy &amp; soda</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
			</tr>
			<tr>
				<td>Pizza orders</td>
				<td><?php echo $total_pizza_orders; ?></td>
				<td>~ <?php echo $total_pizza_orders > 0 ? floor($total_pizzas/$total_pizza_orders) : 0; ?> DKK</td>
				<td><?php echo $total_pizzas; ?> DKK</td>
			</tr>
			<tr>
				<th>Total</th>
				<th colspan="2"></th>
				<th><?php echo $total_lan; ?> DKK</th>
			</tr>
		</tbody>
	</table>
</div>
<?php endif; ?>
<?php // pr($pizza_waves); ?>
