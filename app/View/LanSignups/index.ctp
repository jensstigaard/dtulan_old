<div class="form">
<div style="float:right;">
	<?php echo $this->Html->link('New signup', array('action' => 'add')); ?>
</div>

<h2>LAN signups</h2>

<table>
    <tr>
		<th>LAN title</th>
		<th>User</th>
		<th>Actions</th>
    </tr>


	<?php foreach ($lan_signups as $lan_signup): ?>
		<tr>
			<td><?php echo $lan_signup['Lan']['title']; ?></td>
			<td><?php echo $lan_signup['User']['name']; ?></td>
			<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $lan_signup['Lan']['id'])); ?></td>

		</tr>
	<?php endforeach; ?>

</table>

<pre>
<?php print_r($lan_signups); ?>
</pre>
</div>