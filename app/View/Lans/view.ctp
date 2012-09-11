<div>
	<?php if (isset($user_guests) && count($user_guests)): ?>
		<div style="float:right">
			<?php echo $this->Form->create('LanInvite'); ?>
			<?php echo $this->Form->input('user_guest_id', array('label' => 'Invite guest to LAN', 'options' => $user_guests)); ?>
			<?php echo $this->Form->end('Invite'); ?>
			<?php // debug($user_guests); ?>
		</div>
	<?php endif; ?>

	<h1><?php echo $lan['Lan']['title']; ?></h1>
	<h2>General info</h2>
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
			<?php if ($is_admin): ?>
				<tr>
					<td>Public:</td>
					<td><?php echo $lan['Lan']['published'] ? 'Yes' : 'No'; ?></td>
				</tr>
				<tr>
					<td>Sign up open:</td>
					<td><?php echo $lan['Lan']['sign_up_open'] ? 'Yes' : 'No'; ?></td>
				</tr>
				<tr>
					<td>Price:</td>
					<td><?php echo $lan['Lan']['price']; ?> DKK</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php if (isset($is_not_attending) && $is_not_attending == 1): ?>
	<div>
		<h2><?php echo $this->Html->image('24x24_PNG/001_01.png'); ?> Sign up now!</h2>
		<p>You are not anticipating to this LAN.</p>
		<?php echo $this->Html->link('Sign up this LAN!', array('controller' => 'lan_signups', 'action' => 'add', $lan['Lan']['id'])); ?>
	</div>
<?php endif; ?>

<div>
	<h2><?php echo $this->Html->image('32x32_PNG/trophy_gold.png'); ?> Tournaments (<?php echo count($lan['Tournament']); ?>)</h2>
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
			<?php if (!count($lan['Tournament'])): ?>
				<tr>
					<td colspan="4">
						No tournaments published yet
					</td>
				</tr>
			<?php else: ?>
				<?php foreach ($lan['Tournament'] as $tournament): ?>
					<tr>
						<td><?php echo $this->Html->link($tournament['title'], array('controller' => 'tournaments', 'action' => 'view', $tournament['id'])); ?></td>
						<td><?php echo $tournament['Game']['title'] ?></td>
						<td><?php echo $tournament['max_team_size'] ?></td>
						<td><?php //Participants	            ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<div>
	<h2><?php echo $this->Html->image('32x32_PNG/users_two.png'); ?> Signups for this LAN (<?php echo count($lan['LanSignup']); ?>)</h2>
	<table>
		<thead>
			<tr>
				<th style="width:28px;"></th>
				<th>Name</th>
				<th>Gamertag</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($lan['LanSignup'] as $user): ?>
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
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php // pr($lan); ?>
