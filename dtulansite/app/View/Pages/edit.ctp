<div class="form">
	<h1>Edit Page</h1>
	<?php
	echo $this->Form->create('Page', array('action' => 'edit'));
	echo $this->Form->input('title');
	echo $this->Form->input('text', array('rows' => '6'));
	echo $this->Form->input('id', array('type' => 'hidden'));
	echo $this->Form->end('Save Page');
	?>
</div>