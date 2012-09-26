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


				<tr>
					<td rowspan="1" colspan="2"></td>
				</tr>
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

			</tbody>
		</table>
	</div>

	<div style="float:right; width:44%">
		<h2 style="text-align: center"><?php echo $this->Html->image('32x32_PNG/clock.png'); ?> Dage</h2>
		<table>
			<thead>
				<tr>
					<th>Dato</th>
					<th>Pladser tilbage</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($lan_days as $lan_day): ?>
					<?php $seats_left = $lan_day['Lan']['max_participants'] - count($lan_day['LanSignupDay']); ?>
					<tr>
						<td>
							<?php echo $this->Time->format('j. M Y', $lan_day['LanDay']['date']); ?>
						</td>
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

<div>
	<h2><?php echo $this->Html->image('32x32_PNG/users_two.png'); ?> Ansvarlige</h2>
	<table>
		<thead>
			<tr>
				<th style="width:28px"></th>
				<th>Name</th>
				<th>Telefon nummer</th>
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

					<td style="text-align: right">
						<?php echo $user['User']['phonenumber'] ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<div>
	<h2><?php echo $this->Html->image('32x32_PNG/users_two.png'); ?> Deltagerliste</h2>
	<table>
		<thead>
			<tr>
				<th>Navn</th>
				<th>Telefon nummer</th>
				<?php foreach ($lan_days as $lan_day): ?>
					<th style="text-align: center"><?php echo $this->Time->format('j. M', $lan_day['LanDay']['date']); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($lan_signups as $user): ?>
				<tr>
					<td>
						<?php echo $this->Html->link($user['User']['name'], array('controller' => 'users', 'action' => 'profile', $user['User']['id'])); ?>
						<?php if ($user['User']['type'] == 'guest'): ?>
							(g)
						<?php endif; ?>
					</td>
					<td style="text-align: right">
						<?php echo $user['User']['phonenumber'] ?>
					</td>
					<?php $user_attending_days = array(); ?>
					<?php
					foreach ($user['LanSignupDay'] as $lan_day) {
						$user_attending_days[] = $lan_day['lan_day_id'];
					}
					?>
						<?php foreach ($lan_days as $lan_day): ?>
						<td style="text-align: center">
							<?php if (in_array($lan_day['LanDay']['id'], $user_attending_days)): ?>
								X
						<?php endif; ?>
						</td>
				<?php endforeach; ?>
				</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>