<div>
	<h1>Anmeldelse om overnatning: <?php echo $lan['Lan']['title']; ?></h1>
	<h2>Generel information</h2>
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
				<td>Start-dato:</td>
				<td><?php echo $this->Time->format('j. M Y H:i', $lan['Lan']['time_start']); ?></td>
			</tr>
			<tr>
				<td>Slut-dato:</td>
				<td><?php echo $this->Time->format('j. M Y H:i', $lan['Lan']['time_end']); ?></td>
			</tr>

			<tr>
				<td>Antal deltagere:</td>
				<td><?php echo count($lan['LanSignup']); ?></td>
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
			<?php foreach ($lan['Crew'] as $crew): ?>
				<tr>
					<td style="padding:0 2px;text-align:center;">
						<?php
						if (!empty($crew['User']['email_gravatar'])) {
							echo $this->Html->image(
									  'http://www.gravatar.com/avatar/' . md5(strtolower($crew['User']['email_gravatar'])) . '?s=24&amp;r=r', array(
								 'alt' => $crew['User']['name'],
								 'title' => $crew['User']['name'] . ' gravatar',
								 'style' => 'width:24px;height:24px;'
									  )
							);
						}
						?>
					</td>
					<td>
						<?php echo $crew['User']['name']; ?>
					</td>

					<td>
						<?php echo $crew['User']['phonenumber'] ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<div>
	<h2><?php echo $this->Html->image('32x32_PNG/users_two.png'); ?> Deltagerliste</h2>
	<p>Herunder ses en liste over deltagere. Folk har meldt sig til specifikke dage, men der er ingen garanti for at folk dukker op alle dage.</p>
	<table>
		<thead>
			<tr>
				<th>Navn</th>
				<th>Telefonnummer</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($lan['LanSignup'] as $user): ?>
				<tr>
					<td>
						<?php echo $user['User']['name']; ?>
					</td>
					<td>
						<?php echo $user['User']['phonenumber'] ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>