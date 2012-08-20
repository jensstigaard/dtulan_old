<?php echo $this->Html->script(array('jquery', 'pageEdit', 'ckeditor/ckeditor'), FALSE); ?>

<div class="form">
	<h1>Edit Page</h1>
	<?php
	echo $this->Form->create('Page', array('action' => 'edit'));
	echo $this->Form->input('title');
	?>

	<table>
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
	?>
	<?php // pr($parents); ?>
</div>