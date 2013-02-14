<div>
	<div style="float:right;">
		<?php
		echo $this->Html->link(
				'Back to ' . $lan_title, array(
			'controller' => 'lans',
			'action' => 'view',
			$lan_slug
				)
		);
		?>
	</div>
	<h1>Add Crew-member to <?php echo $lan_title; ?></h1>
	<?php
	echo $this->Form->create();
	echo $this->Chosen->select(
			'user_id', $users, array(
		'data-placeholder' => 'Pick user...',
			)
	);
	echo $this->Form->end(__('Submit'));
	?>
</div>