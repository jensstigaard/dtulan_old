<?php echo $this->Html->script(array('ckeditor/ckeditor'), FALSE); ?>
<div>
	<?php echo $this->Form->create(); ?>
	<?php
	echo $this->Form->inputs(array(
		 'legend' => __('New Tournament in ' . $lan['Lan']['title']),
		 'title',
		 'team_size',
		 'is_signup_open',
		 'time_start' => array(
			  'timeFormat' => '24',
			  'label' => 'Time start for tournament'
		 ),
		 'game_id',
		 'description' => array(
			  'class' => 'ckeditor',
			  'value' => '<p>No description available</p>'
		 ),
		 'rules' => array(
			  'class' => 'ckeditor',
			  'value' => '<p>Rules not defined yet</p>'
		 ),
		 'bracket'
	));
	?>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>