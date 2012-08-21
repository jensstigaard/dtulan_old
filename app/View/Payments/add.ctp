<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Create Payment'); ?></legend>
		<?php
		echo $this->Form->input('amount');
		echo $this->Form->input('user_id');
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>