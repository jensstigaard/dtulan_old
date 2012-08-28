<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Create PizzaWave for '. $lan['Lan']['title']); ?></legend>
		<?php
		echo $this->Form->input('time_start', array('timeFormat' => '24'));
		echo $this->Form->input('time_end', array('timeFormat' => '24'));
		echo $this->Form->input('status');
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>