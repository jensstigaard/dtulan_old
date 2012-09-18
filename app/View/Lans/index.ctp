<div class="form">
	<div style="float:right;">
		<?php echo $this->Html->link('New LAN', array('action' => 'add')); ?>
	</div>

	<h2>Lans</h2>

	<table>
		<tr>
			<th>Title</th>
			<th><small>Part. signed up</small></th>
			<th><small>Max part.</small></th>
			<th>Date start</th>
			<th>Date end</th>
			<th><small>Published</small></th>
			<th>Signup open</th>
			<th>Actions</th>
		</tr>

		<?php foreach ($lans as $lan): ?>
			<tr>
				<td><?php echo $this->Html->link($lan['Lan']['title'], array('action' => 'view', $lan['Lan']['slug'])); ?></td>
				<td><?php echo count($lan['LanSignup']); ?></td>
				<td><?php echo $lan['Lan']['max_participants']; ?></td>
				<td><?php echo $this->Time->nice($lan['Lan']['time_start']); ?></td>
				<td><?php echo $this->Time->nice($lan['Lan']['time_end']); ?></td>
				<td>
					<?php if ($lan['Lan']['published']): ?>
						<strong style="color:green">Yes</strong>
					<?php else: ?>
						<strong style="color:red">No</strong>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($lan['Lan']['sign_up_open']): ?>
						<strong style="color:green">Yes</strong>
					<?php else: ?>
						<?php echo $this->Form->postLink('OPEN NOW', array('action' => 'openForSignup', $lan['Lan']['id']), array('confirm' => 'Are you sure you want to open for sign ups?')); ?>
					<?php endif; ?>
				</td>
				<td>
					<?php echo $this->Html->link('Edit', array('action' => 'edit', $lan['Lan']['id'])); ?>
				</td>

			</tr>
		<?php endforeach; ?>

	</table>
</div>