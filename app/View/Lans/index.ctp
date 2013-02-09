<div class="form">
	<div style="float:right;">
		<?php echo $this->Html->link('New LAN', array('action' => 'add')); ?>
	</div>

	<h2>Lans</h2>

	<table>
		<tr>
			<th>Title</th>
			<th><i class="icon-group"></i></th>
			<th>Date start</th>
			<th>Date end</th>
			<th><small>Published</small></th>
			<th><small>Signup<br />open</small></th>
			<th><small>Signup<br />codes</small></th>
			<th><small>Edit</small></th>
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
				<td><span class="badge badge-inverse"><?php echo $lan['Lan']['count_participants']; ?></span></td>
				<td><?php echo $this->Time->format('d/m/Y H:i', $lan['Lan']['time_start']); ?></td>
				<td><?php echo $this->Time->format('d/m/Y H:i', $lan['Lan']['time_end']); ?></td>
				<td>
					<?php if ($lan['Lan']['published']): ?>
						<span class="badge badge-success">Yes</span>
					<?php else: ?>
						<span class="badge">No</span>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($lan['Lan']['sign_up_open']): ?>
						<span class="badge badge-success">Yes</span>
					<?php else: ?>
						<span class="badge">No</span>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($lan['Lan']['need_physical_code']): ?>
						<?php
						echo $this->Html->link('<i class="icon-barcode icon-large"></i>', array(
							 'action' => 'view_signup_codes',
							 $lan['Lan']['slug']
								  ), array(
							 'escape' => false,
							 'class' => 'btn btn-mini btn-inverse'
						));
						?>
					<?php else: ?>
						<span class="badge">No</span>
					<?php endif; ?>
				</td>
				<td>
					<?php
					echo $this->Html->link('<i class="icon-edit icon-large"></i>', array(
						 'action' => 'edit',
						 $lan['Lan']['id']
							  ), array(
						 'escape' => false,
						 'class' => 'btn btn-mini btn-inverse'
					));
					?>
				</td>
			</tr>
		<?php endforeach; ?>

	</table>
</div>