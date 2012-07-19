<div class="form">
	<div style="float:right;">
		<?php echo $this->Html->link('New LAN', array('action' => 'add')); ?>
	</div>

	<h2>Lans</h2>

	<table>
		<tr>
			<th>Title</th>
			<th><small>Max participants</small></th>
			<th><small>Max guests per student</small></th>
			<th>Date start</th>
			<th>Date end</th>
			<th>Published</th>
			<th>Signup open</th>
			<th>Actions</th>
		</tr>

		<?php foreach ($lans as $lan): ?>
			<tr>
				<td><?php echo $lan['Lan']['title']; ?></td>
				<td><?php echo $lan['Lan']['max_participants']; ?></td>
				<td><?php echo $lan['Lan']['max_guests_per_student']; ?></td>
				<td><?php echo $lan['Lan']['time_start']; ?></td>
				<td><?php echo $lan['Lan']['time_end']; ?></td>
				<td><?php echo $lan['Lan']['published'] == '0' ? 'No' : 'Yes'; ?></td>
				<td><?php echo $lan['Lan']['sign_up_open'] == '0' ? 'No' : 'Yes'; ?></td>
				<td>
					<?php echo $this->Html->link('Edit', array('action' => 'edit', $lan['Lan']['id'])); ?> |
					<?php echo $this->Html->link('List signups', array('action' => 'view', $lan['Lan']['id'])); ?>
				</td>

			</tr>
		<?php endforeach; ?>

	</table>
</div>