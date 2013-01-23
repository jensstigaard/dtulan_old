<div>
	<?php
	echo $this->Form->create('Image', array('type' => 'file'));

	echo $this->Form->inputs(array(
		 'Image.title',
		 'Image.file' => array('type' => 'file'),
	));

	echo $this->Form->end(__('Submit'));
	?>
</div>