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
			<th title="Activated"><small>Ac.</small></th>
			<th>
				<?php echo $this->Paginator->sort('name', 'Name'); ?>
				<small><?php echo $this->Paginator->sort('id_number', 'ID-number'); ?></small>
			</th>
			<th><small>Pizzas</small></th>
			<th><small>Sweets</small></th>
			<th><small>Lans</small></th>
			<th><small>Payments</small></th>
			<th><small>Balance calc.</small></th>
			<th><?php echo $this->Paginator->sort('balance', 'Balance'); ?></th>
			<th><small>Participating in</small></th>
		</tr>

		<?php foreach ($users as $user): ?>
			<tr<?php echo $user['MoneyActivities']['balance']!=$user['User']['balance'] ? ' style="background:rgba(255,0,0,.1);"' : ''; ?>>
				<td>
					<?php echo $user['User']['activated'] ? '<i class="icon-ok" style="color:green"></i>' : '<i class="icon-minus-sign" style="color:red;"></i>'; ?>
				</td>
				<td>
					<?php
					echo $this->Html->link($user['User']['name'], array(
						 'controller' => 'users',
						 'action' => 'view',
						 $user['User']['id'],
						 'admin' => true
							  )
					);
					?><br />
					<?php echo $user['User']['id_number']; ?>
				</td>
				<td><?php echo $user['MoneyActivities']['pizzas']; ?></td>
				<td><?php echo $user['MoneyActivities']['sweets']; ?></td>
				<td><?php echo $user['MoneyActivities']['lans']; ?></td>
				<td><?php echo $user['MoneyActivities']['payments']; ?></td>
				<td><strong><?php echo $user['MoneyActivities']['balance']; ?></strong></td>
				<td><strong><?php echo $user['User']['balance']; ?></strong></td>
				<td>
					<?php foreach ($user['LanSignup'] as $lan_signup): ?>
						<?php echo $lan_signup['Lan']['title']; ?><br />
					<?php endforeach; ?>
					<?php foreach ($user['Crew'] as $lan_signup): ?>
						<?php echo $lan_signup['Lan']['title']; ?><br />
					<?php endforeach; ?>
				</td>
			</tr>
		<?php endforeach; ?>

	</table>

	<?php // echo $this->Paginator->numbers(); ?>
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

<?php
// pr($users); ?>