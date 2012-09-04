<div class="form">
	<?php if(isset($user_guests) && count($user_guests)): ?>
	<div style="float:right">
		<?php echo $this->Form->create('LanInvite'); ?>
		<?php echo $this->Form->input('user_guest_id', array('label' => 'Invite guest to LAN', 'options' => $user_guests)); ?>
		<?php echo $this->Form->end('Invite'); ?>
		<?php // debug($user_guests); ?>
	</div>
	<?php endif; ?>
	<h1><?php echo $lan['Lan']['title']; ?></h1>
	<h2>General info</h2>
	<ul style="margin-bottom: 20px;">
		<li>Date start: <?php echo $lan['Lan']['time_start']; ?></li>
		<li>Date end: <?php echo $lan['Lan']['time_end']; ?></li>
		<li>Public: <?php echo $lan['Lan']['published'] ? 'Yes' : 'No'; ?></li>
		<li>Sign up open: <?php echo $lan['Lan']['sign_up_open'] ? 'Yes' : 'No'; ?></li>
	</ul>

	<h2>Signups for this LAN (<?php echo count($lan['LanSignup']); ?>)</h2>
	<?php // echo $this->Html->link('User lookup', array('action' => 'lookup')); ?>
	<table>
		<tr>
			<th>Name</th>
			<th>Showed up</th>
		</tr>
		<?php foreach ($lan['LanSignup'] as $user): ?>
			<tr>
				<td><?php echo $this->Html->link($user['User']['name'], array('controller'=>'users','action' => 'profile', $user['User']['id'])); ?></td>
				<td></td>
			</tr>
		<?php endforeach; ?>
	</table>

	<h2>Tournaments (<?php echo count($lan['Tournament']); ?>)</h2>
	<?php // echo $this->Html->link('User lookup', array('action' => 'lookup')); ?>
	<table>
		<tr>
			<th>Title:</th>
			<th>Game title:</th>
			<th>Max team size:</th>
			<th>Participants:</th>
		</tr>
		<?php foreach ($lan['Tournament'] as $tournament): ?>
			<tr>
				<td><?php echo $this->Html->link($tournament['title'], array('controller'=>'tournaments','action' => 'view', $tournament['id'])); ?></td>
				<td><?php echo $tournament['Game']['title'] ?></td>
				<td><?php echo $tournament['max_team_size'] ?></td>
				<td><?php  //Participants	 ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php  pr($lan); ?>
</div>
