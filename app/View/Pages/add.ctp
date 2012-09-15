<?php echo $this->Html->script(array('jquery', 'pageEdit', 'ckeditor/ckeditor'), FALSE); ?>
<div class="form">
	<?php echo $this->Form->create(); ?>
	<fieldset>
        <legend><?php echo __('New page'); ?></legend>
		<?php
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
			<?php echo $this->Form->input('command_value', array('label' => 'Link to page')); ?>
		</div>
		<div id="text">
			<?php echo $this->Form->input('text', array('class' => 'ckeditor', 'value' => '<h1>Default heading</h1><p>Default text</p>')); ?>
		</div>

		<?php
		echo $this->Form->hidden('created_by_id');
		echo $this->Form->hidden('latest_update_by_id');
		?>
	</fieldset>
	<?php echo $this->Form->end('Save Page'); ?>
</div>