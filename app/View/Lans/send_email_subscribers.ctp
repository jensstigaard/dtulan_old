<?php echo $this->Html->script(array('ckeditor/ckeditor'), FALSE); ?>

<div class="box">
	<?php
	echo $this->Form->create();

	echo $this->Form->inputs(array(
		 'legend' => 'Send email to subscribers for ' . $title,
		 'send_to_all_users' => array(
			  'type' => 'checkbox',
			  'label' => 'Send to all users (not only subscribers)'
		 ),
		 'text' => array(
			  'label' => false,
			  'class' => 'ckeditor',
			  'type' => 'textarea',
			  'value' => '<p>Default text</p>'
		 )
	));

	echo $this->Form->end(__('Submit'));
	?>
</div>