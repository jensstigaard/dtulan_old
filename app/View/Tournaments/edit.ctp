<?php echo $this->Html->script(array('jquery', 'ckeditor/ckeditor'), FALSE); ?>
<div>
	<div style="float:right">
		<?php echo $this->Html->link('Back to tournament', array('action' => 'view', $tournament_id)); ?>
	</div>
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Edit tournament'); ?></legend>
		<?php
		echo $this->Form->input('title');
		echo $this->Form->input('team_size');
		echo $this->Form->input('time_start', array('timeFormat' => '24'));
		echo $this->Form->input('description', array('class' => 'ckeditor', 'value' => '<p>No description available</p>'));
		echo $this->Form->input('rules', array('class' => 'ckeditor', 'value' => '<p>Rules not defined yet</p>'));
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>