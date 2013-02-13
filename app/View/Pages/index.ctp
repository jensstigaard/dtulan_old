<div class="form">
	<h2>Pages</h2>
	<p><?php echo $this->Html->link('Add Page', array('action' => 'add')); ?></p>
	<table class="">
		<tr>
			<th>Title</th>
			<th><small>Public</small></th>
			<th><small>Menu</small></th>
			<th>Actions</th>
			<th>Created</th>
			<th>Latest update</th>
		</tr>

		<!-- Here's where we loop through our $posts array, printing out post info -->

		<?php foreach ($pages as $page): ?>
			<?php
			switch ($page['Page']['command']) {
				case'uri':
					$img = 'application';
					$title = 'URI';
					$url = $page['Page']['command_value'];
					break;
				default:
					$img = 'file';
					$title = 'Text';
					$url = array(
						 'controller' => 'pages',
						 'action' => 'view',
						 'slug' => $page['Page']['slug']
					);
					break;
			}
			?>
			<tr>
				<td>
					<?php echo $this->Html->image('16x16_GIF/' . $img . '.gif', array('title' => $title)); ?>
					<?php echo $this->Html->link($page['Page']['title'], $url); ?>
				</td>

				<td style="text-align: center;">
					<?php echo $page['Page']['public'] ? $this->Html->image('16x16_GIF/action_check.gif') : $this->Html->image('16x16_GIF/login.gif'); ?>
				</td>
				<td style="text-align: center;">
					<?php echo $page['Page']['in_menu'] ? $this->Html->image('16x16_GIF/action_check.gif') : $this->Html->image('16x16_GIF/action_delete.gif'); ?>
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
					<?php echo $page['Page']['time_created_nice']; ?>
					<br />
					<small>
						(<?php echo $page['CreatedBy']['name']; ?>)
					</small>
				</td>
				<td>
					<?php echo $page['Page']['time_latest_update_nice']; ?>
					<br />
					<small>
						(<?php echo $page['LatestUpdateBy']['name']; ?>)
					</small>
				</td>
			</tr>
		<?php endforeach; ?>

	</table>
</div>
