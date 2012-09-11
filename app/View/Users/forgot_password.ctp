<div>
	<h1>Forgot password?</h1>
	<?php echo $this->Form->create('UserPasswordTicket'); ?>
	<p>Please enter your email below. When submitted, you receive an email with further instructions.</p>
	<?php echo $this->Form->input('User.email'); ?>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>