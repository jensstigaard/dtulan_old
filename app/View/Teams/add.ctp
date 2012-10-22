<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Create Team for ' . $tournament['Tournament']['title']); ?></legend>
		<?php
		echo $this->Form->input('Team.name');
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>