<div>
	<h1>Anmeldelse om overnatning: <?php echo $lan['Lan']['title']; ?></h1>
	<div style="float:left; width:54%">
		<h2 style="text-align: center">General info</h2>
		<table>
			<tbody>
				<tr>
					<td>Lokation:</td>
					<td>
						Danmarks Tekniske Universitet<br />
						Oticon salen, Anker Engelundsvej 1, 101E<br />
						2800 Kgs. Lyngby
					</td>
				</tr>

				<tr>
					<td>Startdato:</td>
					<td><?php echo $this->Time->format('j. M Y H:i', $lan['Lan']['time_start']); ?></td>
				</tr>
				<tr>
					<td>Slutdato:</td>
					<td><?php echo $this->Time->format('j. M Y H:i', $lan['Lan']['time_end']); ?></td>
				</tr>

				<tr>
					<td>Antal deltagere:</td>
					<td><?php echo $count_lan_signups; ?></td>
				</tr>

				<tr>
					<td>Hovedansvarlig:</td>
					<td>Jens Grønhøj Stigaard,<br />
						+45 2172 1600<br />
						jens@stigaard.info
					</td>
				</tr>

			</tbody>
		</table>
	</div>

	<div style="float:right; width:44%">
		<h2 style="text-align: center">Dage</h2>
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

					<td>
						<?php echo $user['User']['phonenumber'] ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<div>
	<h2><?php echo $this->Html->image('32x32_PNG/users_two.png'); ?> Deltagerliste</h2>
	<p>
		Herunder ses en liste over deltagere. Folk har meldt sig til specifikke dage, men der er ingen garanti for at folk dukker op alle dage.
	</p>

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
						<?php
						echo $this->Html->link($user['User']['name'], array('controller' => 'users', 'action' => 'profile', $user['User']['id'])); ?>
					</td>
					<td>
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
								<?php echo $this->Html->image('16x16_PNG/add.png'); ?>
							<?php else: ?>
								<?php // echo $this->Html->image('16x16_PNG/cancel.png'); ?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>