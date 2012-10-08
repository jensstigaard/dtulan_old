<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Add sweet'); ?></legend>
		<?php echo $this->Html->link('Back to sweets', array('action' => 'index')); ?>
		<div>
			<?php
			echo $this->Form->input('Sweet.title');
			echo $this->Form->input('Sweet.description', array('rows' => 3));
			echo $this->Form->input('Sweet.price');
			echo $this->Form->input('Sweet.available');
			?>
		</div>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>