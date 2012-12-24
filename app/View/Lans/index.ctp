<div class="form">
	<div style="float:right;">
		<?php echo $this->Html->link('New LAN', array('action' => 'add')); ?>
	</div>

	<h2>Lans</h2>

	<table>
		<tr>
			<th>Title</th>
			<th>Date start</th>
			<th>Date end</th>
			<th><small>Pub-<br />lished</small></th>
			<th><small>Signup<br />open</small></th>
		</tr>

		<?php foreach ($lans as $lan): ?>
			<tr>
				<td>
					<?php
					if ($lan['Lan']['highlighted']) {
						echo $this->Html->image('16x16_PNG/star.png', array('title' => 'Highlighted event'));
					}
					?>
	<?php echo $this->Html->link($lan['Lan']['title'], array('action' => 'view', $lan['Lan']['slug'])); ?>
				</td>
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
						<strong style="color:grey">No</strong>
	<?php endif; ?>
				</td>
			</tr>
<?php endforeach; ?>

	</table>
</div>