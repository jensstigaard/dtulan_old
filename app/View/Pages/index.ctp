<div class="form">
	<h2>Pages</h2>
	<p><?php echo $this->Html->link('Add Page', array('action' => 'add')); ?></p>
	<table class="">
		<tr>
			<th><small>Type</small></th>
			<th><small>Public</small></th>
			<th>Actions</th>
			<th>Title</th>
			<th>Created</th>
			<th>Latest update</th>
		</tr>

		<!-- Here's where we loop through our $posts array, printing out post info -->

		<?php foreach ($pages as $page): ?>
			<tr>
				<td style="text-align: center;">
					<?php
					switch ($page['Page']['command']) {
						case'uri':
							$img = 'application';
							$title = 'URI';
							break;
						default:
							$img = 'file';
							$title = 'Text';
							break;
					}
					echo $this->Html->image('16x16_GIF/' . $img . '.gif', array('title' => $title));
					?>
				</td>
				<td style="text-align: center;">
					<?php echo $page['Page']['public'] ? $this->Html->image('16x16_GIF/action_check.gif') : $this->Html->image('16x16_GIF/login.gif'); ?>
				</td>
				<td style="text-align: center;">
					<?php echo $this->Html->image('16x16_GIF/reply.gif', array('url' => array('action' => 'edit', $page['Page']['id']), 'title' => 'Edit this page')); ?>
					<?php
					echo $this->Form->postLink(
							$this->Html->image('16x16_GIF/action_delete.gif', array('title' => 'Delete this page')), array('action' => 'delete', $page['Page']['id']), array('confirm' => 'Are you sure?',
						'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
					));
					?>
				</td>
				<td>
					<?php echo $this->Html->link($page['Page']['title'], array('action' => 'view', $page['Page']['id'])); ?>
				</td>


				<td>
					<?php echo $this->Time->nice($page['Page']['time_created']); ?>
					<br />
					<small>
						(<?php echo $page['CreatedBy']['name']; ?>)
					</small>
				</td>
				<td><?php echo $this->Time->nice($page['Page']['time_latest_update']); ?><br /><small>(<?php echo $page['LatestUpdateBy']['name']; ?>)</small></td>
			</tr>
		<?php endforeach; ?>

	</table>
</div>
