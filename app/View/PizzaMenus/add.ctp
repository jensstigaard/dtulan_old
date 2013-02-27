<?php echo $this->Html->script(array('ckeditor/ckeditor'), FALSE); ?>
<div class="box">
	<div style="float:right">
		<?php echo $this->Html->link('Back to pizza menus', array('action' => 'index')); ?>
	</div>
	<?php
	echo $this->Form->create();
	echo $this->Form->inputs(array(
		'title',
		'description' => array(
			'class' => 'ckeditor'
		),
		'email',
		'phonenumber'
	));
	echo $this->Form->end(__('Submit'));
	?>
</div>