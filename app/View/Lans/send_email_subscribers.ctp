<?php echo $this->Html->script(array('ckeditor/ckeditor'), FALSE); ?>

<div>
	<?php
	echo $this->Form->create();

	echo $this->Form->inputs(array(
		 'legend' => 'Send email to subscribers for '.$title,
		 'text' => array(
			  'label' => false,
			  'class' => 'ckeditor',
			  'type' => 'textarea',
			  'value' => '<h1>Default heading</h1><p>Default text</p>'
		 )
	));

	echo $this->Form->end(__('Submit'));
	?>
</div>