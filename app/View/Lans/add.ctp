<div>
	<?php
	echo $this->Form->create();

	echo $this->Form->inputs(array(
		 'title',
		 'max_participants',
		 'max_guests_per_student',
		 'time_start' => array('timeFormat' => '24'),
		 'time_end' => array('timeFormat' => '24'),
		 'price',
		 'published',
		 'sign_up_open',
		 'sign_up_specific_days',
	));

	echo $this->Form->end(__('Submit'));
	?>
</div>