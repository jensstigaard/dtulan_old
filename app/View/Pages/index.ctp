<div class="form">
	<h2>Pages</h2>
	<p><?php echo $this->Html->link('Add Page', array('action' => 'add')); ?></p>
	<table>
		<tr>
			<th><small>Type</small></th>
			<th>Title</th>
			<th>Public</th>
			<th>Actions</th>
			<th>Created</th>
			<th>Latest update</th>
		</tr>

		<!-- Here's where we loop through our $posts array, printing out post info -->

		<?php foreach ($pages as $page): ?>
			<tr>
				<td style="text-align: center;">
					<?php
					switch($page['Page']['command']){
						case'uri':
							$img = 'application';
							$title = 'URI';
							break;
						default:
							$img = 'file';
							$title = 'Text';
							break;
					}
					echo $this->Html->image('16x16_GIF/'.$img.'.gif', array('title' => $title));
					?>
				</td>
				<td>
					<?php echo $this->Html->link($page['Page']['title'], array('action' => 'view', $page['Page']['id'])); ?>
				</td>
				<td>
					<?php echo $page['Page']['public'] ? 'Yes' : 'No'; ?>
				</td>
				<td>
					<?php echo $this->Html->link('Edit', array('action' => 'edit', $page['Page']['id'])); ?> |
					<?php
					echo $this->Form->postLink(
							'Delete', array('action' => 'delete', $page['Page']['id']), array('confirm' => 'Are you sure?'));
					?>
				</td>
				<td><?php echo $this->Time->nice($page['Page']['time_created']); ?><br /><small>(<?php echo $page['CreatedBy']['name']; ?>)</small></td>
				<td><?php echo $this->Time->nice($page['Page']['time_latest_update']); ?><br /><small>(<?php echo $page['LatestUpdateBy']['name']; ?>)</small></td>
			</tr>
		<?php endforeach; ?>

	</table>

	<?php // pr($pages); ?>
</div>
