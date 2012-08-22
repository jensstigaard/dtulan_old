<div class="form">
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
	<?php // pr($lan); ?>
</div>
