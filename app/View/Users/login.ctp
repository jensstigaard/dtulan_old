<div>
	<?php
	echo $this->Form->create();
	echo $this->Form->inputs(array(
		 'legend' => 'Log in',
		 'email',
		 'password'
	));
	?>
	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong>Notice!</strong> Only admins have access to the site at the moment.
	</div>
	<?php
	echo $this->Form->end(__('Log in'));
	?>
</div>
