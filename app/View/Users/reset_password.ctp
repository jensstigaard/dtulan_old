<div>
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Enter new password'); ?></legend>
		<p>Please enter a personal password to set new password.</p>
		<?php
		echo $this->Form->input('password');
		echo $this->Form->input('password_confirmation', array('type' => 'password'));
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Save')); ?>
</div>