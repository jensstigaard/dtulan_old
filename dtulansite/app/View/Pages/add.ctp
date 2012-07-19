<div class="form">
	<?php echo $this->Form->create(); ?>
	<fieldset>
        <legend><?php echo __('New page'); ?></legend>
		<?php
		echo $this->Form->input('title');

		$options = array(0 => 'Text', 1 => 'URI');
		$attributes = array('legend' => false);
		echo $this->Form->radio('command', $options, $attributes);

		echo $this->Form->input('text', array('rows' => '6'));
		?>
	</fieldset>
	<?php echo $this->Form->end('Save Page'); ?>
</div>