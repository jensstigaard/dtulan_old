<div>
	<div style="float:right">
		<?php
		echo $this->Html->link('<i class="icon-remove icon-large"></i> Click here to delete this LAN', array(
			 'action' => 'delete',
			 $id
				  ), array(
			 'confirm' => 'Are you sure you want to delete this LAN and all its data?',
			 'class' => 'btn btn-small btn-danger',
						'escape' => false
				  )
		);
		?>
	</div>
	<?php
	echo $this->Form->create();

	echo $this->Form->inputs(array(
		 'title',
		 'time_start' => array('timeFormat' => '24'),
		 'time_end' => array('timeFormat' => '24'),
//		 'max_participants',
//		 'max_guests_per_student',
		 'published',
		 'highlighted',
	));

	echo $this->Form->end(__('Submit'));
	?>
</div>