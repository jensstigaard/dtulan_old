<div>
	<?php
	echo $this->Form->create();
	echo $this->Form->inputs(array(
		 'legend' => 'Log in',
		 'email',
		 'password'
	));

	echo $this->Form->end(__('Log in'));
	?>
</div>
