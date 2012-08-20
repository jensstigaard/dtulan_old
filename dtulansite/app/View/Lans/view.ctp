<div class="form">
	<h1><?php echo $lan['Lan']['title']; ?></h1>
	<h2>General info</h2>
	<ul style="margin-bottom: 20px;">
		<li>Date start: <?php echo $lan['Lan']['time_start']; ?></li>
		<li>Date end: <?php echo $lan['Lan']['time_end']; ?></li>
		<li>Public: <?php echo $lan['Lan']['published'] ? 'Yes' : 'No'; ?></li>
		<li>Sign up open: <?php echo $lan['Lan']['sign_up_open'] ? 'Yes' : 'No'; ?></li>
	</ul>

	<h2>List of signups for this LAN</h2>
	<?php // echo $this->Html->link('User lookup', array('action' => 'lookup')); ?>
	<table>
		<tr>
			<th>Name</th>
			<th>Paid</th>
		</tr>
		<?php foreach ($users as $user): ?>
			<tr>
				<td><?php echo $user['User']['name']; ?></td>
				<td></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php // pr($lan); ?>
	<?php // pr($users); ?>
</div>
