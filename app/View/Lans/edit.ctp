<div>
	<div style="float:right">
		<?php
		echo $this->Html->link('Click here to delete this LAN', array(
			 'action' => 'delete',
			 $id
				  ), array(
			 'confirm' => 'Are you sure you want to delete this LAN and all its data?',
			 'class' => 'btn btn-danger'
				  )
		);
		?>
	</div>
	<?php
	echo $this->Form->create('Lan');

	echo $this->Form->inputs(array(
		 'title',
		 'time_start' => array('timeFormat' => '24'),
		 'time_end' => array('timeFormat' => '24'),
		 'max_participants',
		 'max_guests_per_student',
		 'published',
		 'highlighted',
	));

	echo $this->Form->end(__('Submit'));
	?>
</div>