<?php echo $this->Html->script(array('pageEdit', 'ckeditor/ckeditor'), array('inline' => false)); ?>

<div>
	<?php
	echo $this->Form->create('NewsItem');
	echo $this->Form->inputs(array(
		 'NewsItem.title',
		 'NewsItem.text',
	));
	echo $this->Form->end(__('Submit'));
	?>
</div>