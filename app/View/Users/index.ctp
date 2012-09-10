<div class="form">
	<div style="float:right;">
		<?php echo $this->Html->link('New user', array('action' => 'add')); ?>
	</div>

	<h2>Users</h2>

	<table>
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Id-number</th>
			<th>Activated</th>
		</tr>

		<?php foreach ($users as $user): ?>
			<tr>
				<td><?php echo $this->Html->link($user['User']['name'], array('action' => 'profile', $user['User']['id'])); ?></td>
				<td><?php echo $user['User']['email']; ?></td>
				<td><?php echo $user['User']['id_number']; ?></td>
				<td><?php echo ($user['User']['activated'] != 1) ? $this->Html->link('Not activated', array('action' => 'activate', $user['User']['id'])) : '<span style="color:green">Activated</span>'; ?></td>
			</tr>
		<?php endforeach; ?>

	</table>

	<!--Users:
	<pre>
	<?php // print_r($users); ?>
	</pre>-->
</div>