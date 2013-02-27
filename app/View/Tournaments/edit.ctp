<?php echo $this->Html->script(array('ckeditor/ckeditor'), FALSE); ?>
<div class="box">
	<div style="float:right">
		<?php echo $this->Html->link('Back to tournament', array('action' => 'view', $lan_slug, $tournament_slug)); ?>
	</div>
	<?php
	echo $this->Form->create();

	echo $this->Form->inputs(array(
		 //'legend' => __('Edit tournament'),
		 'title',
		 'team_size',
		 'is_signup_open',
		 'time_start' => array('timeFormat' => '24'),
		 'description' => array('class' => 'ckeditor'),
		 'rules' => array('class' => 'ckeditor'),
		 'bracket'
	));

	echo $this->Form->end(__('Submit'));
	?>
</div>