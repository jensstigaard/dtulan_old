<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('New LAN signup'); ?></legend>
		<?php
		echo $this->Form->input('Lan');
		echo $this->Form->input('User');
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>