<?php
echo $this->Html->script(array('users/index'), array('inline' => false));
echo $this->Html->css(array('admin/users/index'), null, array('inline' => false));
?>

<div class="box">
	<div style="float:right;">
		<?php echo $this->Html->link('New user', array('action' => 'add')); ?>
	</div>

	<h2>Users</h2>

	<table>
		<tr>
			<th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
			<th><?php echo $this->Paginator->sort('id_number', 'ID-number'); ?></th>
			<th><?php echo $this->Paginator->sort('balance', 'Balance'); ?></th>
			<th><?php echo $this->Paginator->sort('activated', 'Activated'); ?></th>
		</tr>

		<?php foreach ($users as $user): ?>
			<tr>
				<td><?php echo $this->Html->link($user['User']['name'], array('action' => 'view', $user['User']['id'])); ?></td>
				<td><?php echo $user['User']['id_number']; ?></td>
				<td><?php echo $user['User']['balance']; ?></td>
				<td><?php echo (!$user['User']['activated']) ? 'Not activated' : '<span style="color:green">Activated</span>'; ?></td>
			</tr>
		<?php endforeach; ?>

	</table>

	<?php echo $this->Paginator->numbers(); ?>
</div>

<div id="chart-user-register" class="box" data-source-url="<?php
	echo $this->Html->url(array(
		 'controller' => 'users',
		 'action' => 'statistics',
		 'api' => true,
		 'ext' => 'json'
	));
	?>">

</div>