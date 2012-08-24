<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Create Team for '.$tournament['Tournament']['title']); ?></legend>
		<?php
		echo $this->Form->input('Team.name');
		echo $this->Form->input('User.0.user_id', array('type'=>'hidden', 'value'=>$current_user['id']));
		echo $this->Form->input('User.0.is_leader', array('type'=>'hidden', 'value'=>true));
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>