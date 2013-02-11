<div>
	<?php echo $this->Form->create(); ?>
	<?php
	echo $this->Form->inputs(array(
		 'Team.name',
//		 'TeamInvite.user_id',
	));
	?>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>