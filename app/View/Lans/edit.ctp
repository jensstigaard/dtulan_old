<div class="users form">
	<?php echo $this->Form->create('Lan'); ?>
    <fieldset>
        <legend><?php echo __('Edit Lan'); ?></legend>
		<?php
		echo $this->Form->input('title');
		echo $this->Form->input('max_participants');
		echo $this->Form->input('max_guests_per_student');
		echo $this->Form->input('published');
		echo $this->Form->input('highlighted');
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>