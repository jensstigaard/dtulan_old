<div>
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Create Pizza-type'); ?></legend>
		<?php
		echo $this->Form->input('title');
		echo $this->Form->input('title_short');
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>