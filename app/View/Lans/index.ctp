<div class="form">
	<div style="float:right;">
		<?php
		echo $this->Html->link(
				  '<i class="icon-large icon-plus-sign"></i> New LAN', array(
			 'action' => 'add'
				  ), array(
			 'escape' => false,
			 'class' => 'btn btn-success'
		));
		?>
	</div>

	<h2>Lans</h2>

	<table>
		<tr>
			<th>Title</th>
			<th style="text-align: center;"><i class="icon-large icon-group" title="Participants"></i></th>
			<th>Date start</th>
			<th>Date end</th>
			<th style="text-align: center;"><i class="icon-large icon-ok-sign" title="Published"></i></th>
			<th style="text-align: center;"><i class="icon-large icon-plus-sign" title="Is signup open?"></i></th>
			<th style="text-align: center;"><i class="icon-large icon-barcode" title="Need code to signup?"></i></th>
			<th style="text-align: center;"><small>Actions</small></th>
		</tr>

		<?php foreach ($lans as $lan): ?>
			<tr>
				<td>
					<?php
					if ($lan['Lan']['highlighted']) {
						echo $this->Html->image('16x16_PNG/star.png', array('title' => 'Highlighted event'));
					}
					?>
					<?php
					echo $this->Html->link($lan['Lan']['title'], array(
						 'controller' => 'lans',
						 'action' => 'view',
						 'slug' => $lan['Lan']['slug']
					));
					?>
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
						echo $this->Html->link('<i class="icon-large icon-barcode"></i>', array(
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
					<div class="btn-group">
						<?php
						echo $this->Html->link('<i class="icon-large icon-pencil"></i>', array(
							 'action' => 'edit',
							 $lan['Lan']['id']
								  ), array(
							 'escape' => false,
							 'class' => 'btn btn-mini btn-inverse',
							 'title' => 'Edit LAN'
						));

						if ($lan['Lan']['time_end'] > date('Y-m-d H:i:s')) {
							echo $this->Html->link('<i class="icon-large icon-envelope-alt"></i>', array(
								 'action' => 'sendEmailSubscribers',
								 $lan['Lan']['slug']
									  ), array(
								 'escape' => false,
								 'class' => 'btn btn-mini btn-success',
								 'title' => 'Send email to event-subscribers'
							));
						}
						?>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>

	</table>
</div>