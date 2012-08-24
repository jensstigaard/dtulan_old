<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Create PizzaWave'); ?></legend>
		<?php
		echo $this->Form->input('time_start');
		echo $this->Form->input('time_end');
		echo $this->Form->input('status');
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>