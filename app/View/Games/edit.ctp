<div class="box">
	<?php
	echo $this->Form->create();

	echo $this->Form->inputs(array(
		 'title',
		 'image_id'
	));

	echo $this->Form->end(__('Submit'));
	?>
</div>