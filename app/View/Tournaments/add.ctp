<?php echo $this->Html->script(array('ckeditor/ckeditor'), FALSE); ?>
<div>
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Create Tournament in ' . $lan['Lan']['title']); ?></legend>
		<?php
		echo $this->Form->input('title');
		echo $this->Form->input('team_size');
		echo $this->Form->input('time_start', array('timeFormat' => '24'));
		echo $this->Form->input('game_id');
		echo $this->Form->input('description', array('class' => 'ckeditor', 'value' => '<p>No description available</p>'));
		echo $this->Form->input('rules', array('class' => 'ckeditor', 'value' => '<p>Rules not defined yet</p>'));
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>