<div class="form">
	<h2>Pages</h2>
	<p><?php echo $this->Html->link('Add Page', array('action' => 'add')); ?></p>
	<table>
		<tr>
			<th>Id</th>
			<th>Title</th>
			<th>Actions</th>
			<th>Created</th>
			<th>Latest update</th>
		</tr>

		<!-- Here's where we loop through our $posts array, printing out post info -->

		<?php foreach ($pages as $page): ?>
			<tr>
				<td><?php echo $page['Page']['id']; ?></td>
				<td>
					<?php echo $this->Html->link($page['Page']['title'], array('action' => 'view', $page['Page']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link('Edit', array('action' => 'edit', $page['Page']['id'])); ?> |
					<?php
					echo $this->Form->postLink(
							'Delete', array('action' => 'delete', $page['Page']['id']), array('confirm' => 'Are you sure?'));
					?>
				</td>
				<td>
					<?php echo $page['Page']['time_created']; ?>
				</td>

				<td>
					<?php echo $page['Page']['time_latest_update']; ?>
				</td>
			</tr>
		<?php endforeach; ?>

	</table>
</div>