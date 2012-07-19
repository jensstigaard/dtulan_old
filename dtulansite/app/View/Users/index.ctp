<div style="float:right;">
	<?php echo $this->Html->link('New user', array('action' => 'add')); ?>
</div>

<h2>Users</h2>

<table>
    <tr>
		<th>Name</th>
		<th>Email</th>
		<th>Gamertag</th>
		<th>Type</th>
		<th>Id-number</th>
		<th>Activated</th>
		<th>Time created</th>
		<th>Time activated</th>
	</tr>

	<?php foreach ($users as $user): ?>
		<tr>
			<td><?php echo $this->Html->link($user['User']['name'], array('action' => 'profile', $user['User']['id'])); ?></td>
			<td><?php echo $user['User']['email']; ?></td>
			<td><?php echo $user['User']['gamertag']; ?></td>
			<td><?php echo $user['User']['type']; ?></td>
			<td><?php echo $user['User']['id_number']; ?></td>
			<td><?php echo ($user['User']['activated'] != 1) ? $this->Html->link('Not activated', array('action' => 'activate', $user['User']['id'])) : '<span style="color:green">Activated</span>'; ?></td>
			<td><?php echo $user['User']['time_created']; ?></td>
			<td><?php echo $user['User']['time_activated']; ?></td>
		</tr>
	<?php endforeach; ?>

</table>

<!--Users:
<pre>
<?php // print_r($users); ?>
</pre>-->
