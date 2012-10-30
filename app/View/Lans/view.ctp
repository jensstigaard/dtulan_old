<?php echo $this->Html->script('lans/view', FALSE); ?>

<div>
	<h1><?php echo $lan['Lan']['title']; ?></h1>

	<div class="tabs">
		<ul>
			<li><a href="#tab-general"><?php echo $this->Html->image('24x24_PNG/001_40.png'); ?></a></li>
			<li><a href="<?php
echo $this->Html->url(array(
	'action' => 'view_crew',
	$lan['Lan']['id']
));
?>"><?php echo $this->Html->image('24x24_PNG/crew.png'); ?></a></li>
			<li><a href="<?php
				   echo $this->Html->url(array(
					   'action' => 'view_participants',
					   $lan['Lan']['id']
				   ));
?>"><?php echo $this->Html->image('24x24_PNG/participants.png'); ?></a></li>
			<li><a href="<?php
				   echo $this->Html->url(array(
					   'action' => 'view_tournaments',
					   $lan['Lan']['id']
				   ));
?>"><?php echo $this->Html->image('24x24_PNG/trophy_gold.png'); ?></a></li>
				<?php if ($is_admin): ?>
				<li><a href="<?php
				echo $this->Html->url(array(
					'action' => 'view_pizzawaves',
					$lan['Lan']['id']
				));
					?>"><?php echo $this->Html->image('24x24_PNG/pizza.png'); ?></a></li>
				<li><a href="<?php
				echo $this->Html->url(array(
					'action' => 'view_invites',
					$lan['Lan']['id']
				));
					?>"><?php echo $this->Html->image('24x24_PNG/001_13.png'); ?></a></li>
				<li><a href="<?php
				echo $this->Html->url(array(
					'action' => 'view_economics',
					$lan['Lan']['id']
				));
					?>"><?php echo $this->Html->image('24x24_PNG/payment_cash.png'); ?></a></li>
			<?php endif; ?>
		</ul>

		<div id="tab-general">
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
								<td rowspan="1" colspan="2"></td>
							</tr>
							<tr>
								<th colspan="2">Crew only</th>
							</tr>
							<tr>
								<td>Invited (not accepted):</td>
								<td><?php echo $count_invites; ?></td>
							</tr>
							<tr>
								<td>Tournaments:</td>
								<td><?php echo $count_tournaments; ?></td>
							</tr>
							<tr>
								<td>Public:</td>
								<td><?php echo $lan['Lan']['published'] ? 'Yes' : 'No'; ?></td>
							</tr>
							<tr>
								<td>Sign up open:</td>
								<td><?php echo $lan['Lan']['sign_up_open'] ? 'Yes' : 'No'; ?></td>
							</tr>
							<tr>
								<td>Guests:</td>
								<td><?php echo $count_lan_signups === 0 ? 0 : floor($count_lan_signups_guests / $count_lan_signups * 100) ?> %</td>
							</tr>
							<tr>
								<td>Students:</td>
								<td><?php echo $count_lan_signups_guests === 0 ? 0 : floor(($count_lan_signups - $count_lan_signups_guests) / $count_lan_signups * 100) ?> %</td>
							</tr>
							<?php $total_signup = 0; ?>
							<?php $max_signup = 0; ?>
							<?php foreach ($lan_days as $lan_day): ?>
								<?php $total_signup += count($lan_day['LanSignupDay']); ?>
								<?php $max_signup += $lan_day['Lan']['max_participants']; ?>
							<?php endforeach; ?>
							<tr>
								<td>Fill rate:</td>
								<td><?php echo $max_signup === 0 ? 0 : floor(($total_signup / $max_signup) * 100) ?> %</td>
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
	</div>

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