<?php echo $this->Html->script(array('ckeditor/ckeditor'), FALSE); ?>
<div>
	<div style="float:right">
		<?php echo $this->Html->link('Back to Food Menus', array('action' => 'index')); ?>
	</div>
	<?php
	echo $this->Form->create();
	echo $this->Form->inputs(array(
		'title',
		'description' => array(
			'class' => 'ckeditor'
		)
	));
	echo $this->Form->end(__('Submit'));
	?>
</div>