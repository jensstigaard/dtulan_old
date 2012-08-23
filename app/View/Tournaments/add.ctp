<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Create Tournament'); ?></legend>
		<?php
		echo $this->Form->input('lan_id');
		echo $this->Form->input('title');
		echo $this->Form->input('max_team_size');
		echo $this->Form->input('time_start', array('timeFormat' => '24'));
		echo $this->Form->input('game_id');
		echo $this->Form->input('description');
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>