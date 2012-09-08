<?php echo $this->Html->script(array('jquery', 'pageEdit', 'ckeditor/ckeditor'), FALSE); ?>

<div class="form">
	<h1>Edit Page</h1>
	<?php
	echo $this->Form->create();
	echo $this->Form->input('title');
	?>

	<table>
		<tbody>
			<tr>
				<td>
					<?php
					echo $this->Form->input('command', array(
						'options' => array(
							'text' => 'text',
							'uri' => 'uri'
						),
						'id' => 'command'
					));
					?>
				</td>
				<td>
					<?php echo $this->Form->input('parent_id'); ?>
				</td>
				<td>
					<strong>Public page:</strong>
					<?php echo $this->Form->input('public', array('label' => '')); ?>
				</td>
			</tr>
		</tbody>
	</table>



	<div id="command_value">
		<?php echo $this->Form->input('command_value'); ?>
	</div>
	<div id="text">
		<?php echo $this->Form->input('text', array('rows' => '6', 'class' => 'ckeditor')); ?>
	</div>

	<?php
	echo $this->Form->hidden('id');
	echo $this->Form->hidden('latest_update_by_id');

	echo $this->Form->end('Save Page');
	echo $this->Form->postLink('View page without saving', array('action' => 'view', $id), array('confirm' => 'Are You sure you will leave this page without saving?'));
	?>
</div>