<div class="box">
	<?php
	echo $this->Form->create();
	echo $this->Form->inputs(array(
		'legend' => 'Log in',
		'email',
		'password',
		'remember_me' => array(
			'type' => 'checkbox',
			'label' => 'Stay logged in'
		)
	));

	echo $this->Form->end(__('Log in'));
	?>
</div>
