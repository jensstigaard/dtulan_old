<div class="users form">
	<?php echo $this->Form->create('Lan'); ?>
	<fieldset>
		<legend><?php echo __('Edit Lan'); ?></legend>
		<div class="notice" style="margin-top:0;padding:8px;">
			<?php
			echo $this->Html->link('Click here to delete this LAN :-(', array(
				 'action' => 'delete',
				 $id
					  ), array(
				 'confirm' => 'Are you sure you want to delete this LAN and all its data?',
				 'style' => 'color:black'
					  )
			);
			?>
		</div>
		<?php
		echo $this->Form->inputs(array(
			 'title',
			 'time_start' => array('timeFormat' => '24'),
			 'time_end' => array('timeFormat' => '24'),
			 'max_participants',
			 'max_guests_per_student',
			 'published',
			 'highlighted',
		));
		?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>