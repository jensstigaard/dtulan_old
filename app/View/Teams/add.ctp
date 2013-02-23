<div>
	
	<?php echo $this->Form->create(); ?>
	<fieldset>
		<legend>Create Team</legend>
	<?php echo $this->Form->input('Team.name');  ?>
	<p>Notice: You are able to invite people to your team when it's created.</p>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>