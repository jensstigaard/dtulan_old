<?php echo $this->Html->script(array('jquery', 'pageEdit'), FALSE); ?>
<div class="form">
	<?php echo $this->Form->create(); ?>
	<fieldset>
        <legend><?php echo __('New page'); ?></legend>
		<?php
		echo $this->Form->input('title');

		echo $this->Form->input('parent_id');

		$options = array('text' => 'text', 'uri' => 'uri');
		echo $this->Form->input('command', array('options' => $options, 'id' => 'command'));
		?>
		<div id="command_value">
			<?php echo $this->Form->input('command_value', array('label' => 'Link to page')); ?>
		</div>
		<div id="text">
			<?php echo $this->Form->input('text', array('rows' => '6')); ?>
		</div>

		<?php
		echo $this->Form->hidden('created_by_id');
		echo $this->Form->hidden('latest_update_by_id');
		?>
	</fieldset>
	<?php echo $this->Form->end('Save Page'); ?>
</div>