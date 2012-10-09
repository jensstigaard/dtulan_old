<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Add food'); ?></legend>
		<?php echo $this->Html->link('Back to food', array('action' => 'index')); ?>
		<div>
			<?php
			echo $this->Form->input('title');
			echo $this->Form->input('description', array('rows' => 3));
			echo $this->Form->input('price');
			echo $this->Form->input('available');
			?>
		</div>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>