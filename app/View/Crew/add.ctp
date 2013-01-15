<div>
	<h1>Add Crew-member to <?php echo $lan_title; ?></h1>
	<?php
	echo $this->Form->create();
	echo $this->Chosen->select(
			'user_id', $users, array(
		'data-placeholder' => 'Pick user...',
			)
	);
	echo $this->Form->end(__('Submit'));
	?>
</div>