<div>
	<h1>Give admin permissions</h1>

	<?php
	if (!count($users)) {
		?>
	<p>No users to make admin.</p>
	<?php
	}
	else{
		echo $this->Form->create();
		echo $this->Form->input('user_id');
		echo $this->Form->hidden('user_by_id', array('value' => $current_user['id']));
		echo $this->Form->end('Submit');
	}
	?>
</div>